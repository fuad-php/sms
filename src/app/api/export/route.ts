import { NextRequest, NextResponse } from 'next/server'
import { getServerSession } from 'next-auth'
import { authOptions } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { TicketExporter } from '@/lib/export'
import { z } from 'zod'

const exportSchema = z.object({
  ticketId: z.string(),
  format: z.enum(['pdf', 'image']).default('pdf'),
  quality: z.enum(['low', 'medium', 'high']).default('high'),
  watermark: z.string().optional(),
  signature: z.string().optional(),
})

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { ticketId, format, quality, watermark, signature } = exportSchema.parse(body)

    // Get ticket from database
    const ticket = await prisma.ticket.findFirst({
      where: {
        id: ticketId,
        userId: session.user.id,
      },
    })

    if (!ticket) {
      return NextResponse.json({ error: 'Ticket not found' }, { status: 404 })
    }

    // Export ticket
    const exporter = TicketExporter.getInstance()
    let blob: Blob

    if (format === 'pdf') {
      blob = await exporter.exportToPDF(ticket.standardizedData as any, {
        format: 'pdf',
        quality,
        watermark,
        signature,
      })
    } else {
      blob = await exporter.exportToImage(ticket.standardizedData as any, {
        format: 'image',
        quality,
        watermark,
        signature,
      })
    }

    // Update ticket status
    await prisma.ticket.update({
      where: { id: ticketId },
      data: {
        status: 'EXPORTED',
        exportedAt: new Date(),
      },
    })

    // Convert blob to buffer
    const buffer = Buffer.from(await blob.arrayBuffer())

    // Return the file
    const filename = `ticket_${ticket.originalFileName.split('.')[0]}_${Date.now()}.${format === 'pdf' ? 'pdf' : 'png'}`
    
    return new NextResponse(buffer, {
      status: 200,
      headers: {
        'Content-Type': format === 'pdf' ? 'application/pdf' : 'image/png',
        'Content-Disposition': `attachment; filename="${filename}"`,
        'Content-Length': buffer.length.toString(),
      },
    })

  } catch (error) {
    if (error instanceof z.ZodError) {
      return NextResponse.json(
        { error: 'Validation error', details: error.errors },
        { status: 400 }
      )
    }

    console.error('Export error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}