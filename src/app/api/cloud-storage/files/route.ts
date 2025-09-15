import { NextRequest, NextResponse } from 'next/server'
import { getServerSession } from 'next-auth'
import { authOptions } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { CloudStorageManager } from '@/lib/cloud-storage'
import { z } from 'zod'

const filesSchema = z.object({
  provider: z.enum(['google', 'onedrive']),
  folderId: z.string().optional(),
})

export async function GET(request: NextRequest) {
  try {
    const session = await getServerSession(authOptions)
    
    if (!session?.user?.id) {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
    }

    const { searchParams } = new URL(request.url)
    const provider = searchParams.get('provider') as 'google' | 'onedrive'
    const folderId = searchParams.get('folderId') || undefined

    if (!provider) {
      return NextResponse.json(
        { error: 'Provider is required' },
        { status: 400 }
      )
    }

    // Get cloud storage configuration
    const config = await prisma.cloudStorageConfig.findFirst({
      where: {
        userId: session.user.id,
        provider: provider.toUpperCase(),
      },
    })

    if (!config) {
      return NextResponse.json(
        { error: 'Cloud storage not connected' },
        { status: 400 }
      )
    }

    // List files
    const cloudManager = CloudStorageManager.getInstance()
    let files

    if (provider === 'google') {
      files = await cloudManager.listGoogleDriveFiles(
        config.accessToken,
        config.refreshToken,
        folderId || config.folderId || undefined
      )
    } else if (provider === 'onedrive') {
      files = await cloudManager.listOneDriveFiles(
        config.accessToken,
        config.refreshToken,
        folderId || config.folderId || undefined
      )
    } else {
      return NextResponse.json(
        { error: 'Unsupported provider' },
        { status: 400 }
      )
    }

    return NextResponse.json({
      success: true,
      files,
    })

  } catch (error) {
    console.error('Cloud storage files error:', error)
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    )
  }
}