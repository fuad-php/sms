import { NextRequest, NextResponse } from 'next/server'
import { getServerSession } from 'next-auth'
import { authOptions } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { OCRProcessor } from '@/lib/ocr'
import { writeFile, mkdir } from 'fs/promises'
import { join } from 'path'
import { existsSync } from 'fs'
import { z } from 'zod'

const bulkUploadSchema = z.object({
  files: z.array(z.object({
    name: z.string(),
    type: z.string(),
    size: z.number(),
    data: z.string(), // base64 encoded file data
  })),
  source: z.enum(['direct', 'google_drive', 'onedrive']).default('direct'),
  metadata: z.object({
    batchId: z.string().optional(),
    description: z.string().optional(),
  }).optional(),
})

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { files, source, metadata } = bulkUploadSchema.parse(body)

    if (files.length === 0) {
      return NextResponse.json(
        { error: 'No files provided' },
        { status: 400 }
      )
    }

    if (files.length > 50) {
      return NextResponse.json(
        { error: 'Maximum 50 files allowed per batch' },
        { status: 400 }
      )
    }

    // Validate file types and sizes
    const allowedTypes = [
      'application/pdf',
      'image/jpeg',
      'image/png',
      'image/gif',
      'image/bmp',
      'image/tiff',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ]

    const maxSize = 10 * 1024 * 1024 // 10MB

    for (const file of files) {
      if (!allowedTypes.includes(file.type)) {
        return NextResponse.json(
          { error: `Unsupported file type: ${file.type}` },
          { status: 400 }
        )
      }

      if (file.size > maxSize) {
        return NextResponse.json(
          { error: `File too large: ${file.name}` },
          { status: 400 }
        )
      }
    }

    // Create uploads directory if it doesn't exist
    const uploadsDir = join(process.cwd(), 'uploads')
    if (!existsSync(uploadsDir)) {
      await mkdir(uploadsDir, { recursive: true })
    }

    const results = []
    const batchId = metadata?.batchId || `batch_${Date.now()}`

    // Process files in parallel (with concurrency limit)
    const concurrencyLimit = 5
    const chunks = []
    for (let i = 0; i < files.length; i += concurrencyLimit) {
      chunks.push(files.slice(i, i + concurrencyLimit))
    }

    for (const chunk of chunks) {
      const chunkPromises = chunk.map(async (file) => {
        try {
          // Decode base64 file data
          const fileBuffer = Buffer.from(file.data, 'base64')
          
          // Generate unique filename
          const timestamp = Date.now()
          const fileExtension = file.name.split('.').pop()
          const fileName = `${timestamp}-${Math.random().toString(36).substr(2, 9)}.${fileExtension}`
          const filePath = join(uploadsDir, fileName)

          // Save file to disk
          await writeFile(filePath, fileBuffer)

          // Process with OCR
          const ocrProcessor = OCRProcessor.getInstance()
          let extractedData = {}
          
          try {
            if (file.type.startsWith('image/')) {
              const result = await ocrProcessor.processImage(new File([fileBuffer], file.name, { type: file.type }))
              extractedData = result.extractedData
            } else if (file.type === 'application/pdf') {
              const result = await ocrProcessor.processPDF(new File([fileBuffer], file.name, { type: file.type }))
              extractedData = result.extractedData
            }
          } catch (ocrError) {
            console.error('OCR processing failed for file:', file.name, ocrError)
            // Continue with empty extracted data
          }

          // Save to database
          const ticket = await prisma.ticket.create({
            data: {
              userId: session.user.id,
              originalFileName: file.name,
              originalFileType: file.type,
              originalFileUrl: `/uploads/${fileName}`,
              extractedData: extractedData,
              standardizedData: extractedData,
              status: 'REVIEW',
            },
          })

          // Create upload history record
          await prisma.uploadHistory.create({
            data: {
              userId: session.user.id,
              fileName: file.name,
              fileSize: file.size,
              fileType: file.type,
              status: 'COMPLETED',
              progress: 100,
              completedAt: new Date(),
            },
          })

          return {
            success: true,
            ticketId: ticket.id,
            fileName: file.name,
            extractedData,
            status: ticket.status,
          }
        } catch (error) {
          console.error('Error processing file:', file.name, error)
          return {
            success: false,
            fileName: file.name,
            error: error instanceof Error ? error.message : 'Unknown error',
          }
        }
      })

      const chunkResults = await Promise.all(chunkPromises)
      results.push(...chunkResults)
    }

    // Calculate summary
    const successful = results.filter(r => r.success).length
    const failed = results.filter(r => !r.success).length

    return NextResponse.json({
      success: true,
      batchId,
      summary: {
        total: files.length,
        successful,
        failed,
      },
      results,
    })

  } catch (error) {
    if (error instanceof z.ZodError) {
      return NextResponse.json(
        { error: 'Validation error', details: error.errors },
        { status: 400 }
      )
    }

    console.error('Bulk upload error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}