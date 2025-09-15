import { NextRequest, NextResponse } from 'next/server'
import { getServerSession } from 'next-auth'
import { authOptions } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { z } from 'zod'

const querySchema = z.object({
  page: z.string().transform(Number).default('1'),
  limit: z.string().transform(Number).default('10'),
  status: z.enum(['PROCESSING', 'REVIEW', 'APPROVED', 'EXPORTED']).optional(),
  sortBy: z.enum(['createdAt', 'updatedAt', 'originalFileName']).default('createdAt'),
  sortOrder: z.enum(['asc', 'desc']).default('desc'),
  search: z.string().optional(),
})

export async function GET(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const { searchParams } = new URL(request.url)
    const { page, limit, status, sortBy, sortOrder, search } = querySchema.parse({
      page: searchParams.get('page'),
      limit: searchParams.get('limit'),
      status: searchParams.get('status'),
      sortBy: searchParams.get('sortBy'),
      sortOrder: searchParams.get('sortOrder'),
      search: searchParams.get('search'),
    })

    // Build where clause
    const where: any = {
      userId: session.user.id,
    }

    if (status) {
      where.status = status
    }

    if (search) {
      where.OR = [
        { originalFileName: { contains: search, mode: 'insensitive' } },
        { extractedData: { path: ['passengerName'], string_contains: search } },
        { extractedData: { path: ['flightNumber'], string_contains: search } },
        { extractedData: { path: ['bookingReference'], string_contains: search } },
      ]
    }

    // Calculate pagination
    const skip = (page - 1) * limit

    // Get tickets with pagination
    const [tickets, total] = await Promise.all([
      prisma.ticket.findMany({
        where,
        skip,
        take: limit,
        orderBy: {
          [sortBy]: sortOrder,
        },
        select: {
          id: true,
          originalFileName: true,
          originalFileType: true,
          originalFileUrl: true,
          extractedData: true,
          standardizedData: true,
          status: true,
          createdAt: true,
          updatedAt: true,
          exportedAt: true,
        },
      }),
      prisma.ticket.count({ where }),
    ])

    const totalPages = Math.ceil(total / limit)

    return NextResponse.json({
      success: true,
      data: tickets,
      pagination: {
        page,
        limit,
        total,
        totalPages,
        hasNext: page < totalPages,
        hasPrev: page > 1,
      },
    })

  } catch (error) {
    if (error instanceof z.ZodError) {
      return NextResponse.json(
        { error: 'Validation error', details: error.errors },
        { status: 400 }
      )
    }

    console.error('Tickets fetch error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { ticketId, standardizedData } = body

    if (!ticketId || !standardizedData) {
      return NextResponse.json(
        { error: 'Ticket ID and standardized data are required' },
        { status: 400 }
      )
    }

    // Update ticket
    const ticket = await prisma.ticket.update({
      where: {
        id: ticketId,
        userId: session.user.id,
      },
      data: {
        standardizedData,
        status: 'APPROVED',
        updatedAt: new Date(),
      },
    })

    return NextResponse.json({
      success: true,
      ticket,
    })

  } catch (error) {
    console.error('Ticket update error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}