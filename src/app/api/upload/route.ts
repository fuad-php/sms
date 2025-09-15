import { NextRequest, NextResponse } from 'next/server'
import { getServerSession } from 'next-auth'
import { authOptions } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { OCRProcessor } from '@/lib/ocr'
import { writeFile, mkdir } from 'fs/promises'
import { join } from 'path'
import { existsSync } from 'fs'

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const formData = await request.formData()
    const file = formData.get('file') as File
    
    if (!file) {
      return NextResponse.json({ error: 'No file provided' }, { status: 400 })
    }

    // Validate file type
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

    if (!allowedTypes.includes(file.type)) {
      return NextResponse.json(
        { error: 'Unsupported file type' },
        { status: 400 }
      )
    }

    // Validate file size (10MB max)
    const maxSize = 10 * 1024 * 1024
    if (file.size > maxSize) {
      return NextResponse.json(
        { error: 'File too large. Maximum size is 10MB' },
        { status: 400 }
      )
    }

    // Create uploads directory if it doesn't exist
    const uploadsDir = join(process.cwd(), 'uploads')
    if (!existsSync(uploadsDir)) {
      await mkdir(uploadsDir, { recursive: true })
    }

    // Generate unique filename
    const timestamp = Date.now()
    const fileExtension = file.name.split('.').pop()
    const fileName = `${timestamp}-${Math.random().toString(36).substr(2, 9)}.${fileExtension}`
    const filePath = join(uploadsDir, fileName)

    // Save file to disk
    const bytes = await file.arrayBuffer()
    const buffer = Buffer.from(bytes)
    await writeFile(filePath, buffer)

    // Process file with OCR
    const ocrProcessor = OCRProcessor.getInstance()
    let extractedData = {}
    
    try {
      if (file.type.startsWith('image/')) {
        const result = await ocrProcessor.processImage(file)
        extractedData = result.extractedData
      } else if (file.type === 'application/pdf') {
        const result = await ocrProcessor.processPDF(file)
        extractedData = result.extractedData
      }
    } catch (ocrError) {
      console.error('OCR processing failed:', ocrError)
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

    return NextResponse.json({
      success: true,
      ticket: {
        id: ticket.id,
        fileName: file.name,
        extractedData,
        status: ticket.status,
      },
    })

  } catch (error) {
    console.error('Upload error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}