import { NextRequest, NextResponse } from 'next/server'
import { getServerSession } from 'next-auth'
import { authOptions } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { CloudStorageManager } from '@/lib/cloud-storage'
import { z } from 'zod'

const connectSchema = z.object({
  provider: z.enum(['google', 'onedrive']),
  accessToken: z.string(),
  refreshToken: z.string(),
  folderId: z.string().optional(),
})

export async function POST(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const body = await request.json()
    const { provider, accessToken, refreshToken, folderId } = connectSchema.parse(body)

    // Test the connection
    const cloudManager = CloudStorageManager.getInstance()
    let isConnected = false

    if (provider === 'google') {
      isConnected = await cloudManager.connectGoogleDrive(accessToken, refreshToken)
    } else if (provider === 'onedrive') {
      isConnected = await cloudManager.connectOneDrive(accessToken, refreshToken)
    }

    if (!isConnected) {
      return NextResponse.json(
        { error: 'Failed to connect to cloud storage' },
        { status: 400 }
      )
    }

    // Save or update the configuration
    const existingConfig = await prisma.cloudStorageConfig.findFirst({
      where: {
        userId: session.user.id,
        provider: provider.toUpperCase(),
      },
    })

    if (existingConfig) {
      await prisma.cloudStorageConfig.update({
        where: { id: existingConfig.id },
        data: {
          accessToken,
          refreshToken,
          folderId,
          updatedAt: new Date(),
        },
      })
    } else {
      await prisma.cloudStorageConfig.create({
        data: {
          userId: session.user.id,
          provider: provider.toUpperCase(),
          accessToken,
          refreshToken,
          folderId,
        },
      })
    }

    return NextResponse.json({
      success: true,
      message: `Successfully connected to ${provider === 'google' ? 'Google Drive' : 'OneDrive'}`,
    })

  } catch (error) {
    if (error instanceof z.ZodError) {
      return NextResponse.json(
        { error: 'Validation error', details: error.errors },
        { status: 400 }
      )
    }

    console.error('Cloud storage connection error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}