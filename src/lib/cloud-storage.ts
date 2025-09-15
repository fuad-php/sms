import { google } from 'googleapis'
import { CloudStorageConfig } from '@/types'

export interface CloudFile {
  id: string
  name: string
  size: number
  mimeType: string
  createdTime: string
  modifiedTime: string
  webViewLink?: string
  downloadUrl?: string
}

export class CloudStorageManager {
  private static instance: CloudStorageManager

  public static getInstance(): CloudStorageManager {
    if (!CloudStorageManager.instance) {
      CloudStorageManager.instance = new CloudStorageManager()
    }
    return CloudStorageManager.instance
  }

  async connectGoogleDrive(accessToken: string, refreshToken: string): Promise<boolean> {
    try {
      const oauth2Client = new google.auth.OAuth2(
        process.env.GOOGLE_DRIVE_CLIENT_ID,
        process.env.GOOGLE_DRIVE_CLIENT_SECRET,
        process.env.NEXTAUTH_URL + '/api/auth/callback/google'
      )

      oauth2Client.setCredentials({
        access_token: accessToken,
        refresh_token: refreshToken,
      })

      const drive = google.drive({ version: 'v3', auth: oauth2Client })
      
      // Test the connection by listing files
      await drive.files.list({ pageSize: 1 })
      
      return true
    } catch (error) {
      console.error('Google Drive connection failed:', error)
      return false
    }
  }

  async listGoogleDriveFiles(
    accessToken: string, 
    refreshToken: string, 
    folderId?: string
  ): Promise<CloudFile[]> {
    try {
      const oauth2Client = new google.auth.OAuth2(
        process.env.GOOGLE_DRIVE_CLIENT_ID,
        process.env.GOOGLE_DRIVE_CLIENT_SECRET,
        process.env.NEXTAUTH_URL + '/api/auth/callback/google'
      )

      oauth2Client.setCredentials({
        access_token: accessToken,
        refresh_token: refreshToken,
      })

      const drive = google.drive({ version: 'v3', auth: oauth2Client })
      
      const query = folderId 
        ? `'${folderId}' in parents and trashed=false`
        : 'trashed=false'
      
      const response = await drive.files.list({
        q: query,
        fields: 'files(id,name,size,mimeType,createdTime,modifiedTime,webViewLink)',
        pageSize: 100,
      })

      return response.data.files?.map(file => ({
        id: file.id!,
        name: file.name!,
        size: parseInt(file.size || '0'),
        mimeType: file.mimeType!,
        createdTime: file.createdTime!,
        modifiedTime: file.modifiedTime!,
        webViewLink: file.webViewLink,
      })) || []
    } catch (error) {
      console.error('Failed to list Google Drive files:', error)
      throw new Error('Failed to list Google Drive files')
    }
  }

  async downloadGoogleDriveFile(
    accessToken: string,
    refreshToken: string,
    fileId: string
  ): Promise<Buffer> {
    try {
      const oauth2Client = new google.auth.OAuth2(
        process.env.GOOGLE_DRIVE_CLIENT_ID,
        process.env.GOOGLE_DRIVE_CLIENT_SECRET,
        process.env.NEXTAUTH_URL + '/api/auth/callback/google'
      )

      oauth2Client.setCredentials({
        access_token: accessToken,
        refresh_token: refreshToken,
      })

      const drive = google.drive({ version: 'v3', auth: oauth2Client })
      
      const response = await drive.files.get({
        fileId: fileId,
        alt: 'media',
      }, {
        responseType: 'arraybuffer',
      })

      return Buffer.from(response.data as ArrayBuffer)
    } catch (error) {
      console.error('Failed to download Google Drive file:', error)
      throw new Error('Failed to download Google Drive file')
    }
  }

  async uploadToGoogleDrive(
    accessToken: string,
    refreshToken: string,
    fileBuffer: Buffer,
    fileName: string,
    mimeType: string,
    folderId?: string
  ): Promise<string> {
    try {
      const oauth2Client = new google.auth.OAuth2(
        process.env.GOOGLE_DRIVE_CLIENT_ID,
        process.env.GOOGLE_DRIVE_CLIENT_SECRET,
        process.env.NEXTAUTH_URL + '/api/auth/callback/google'
      )

      oauth2Client.setCredentials({
        access_token: accessToken,
        refresh_token: refreshToken,
      })

      const drive = google.drive({ version: 'v3', auth: oauth2Client })
      
      const fileMetadata = {
        name: fileName,
        parents: folderId ? [folderId] : undefined,
      }

      const media = {
        mimeType: mimeType,
        body: fileBuffer,
      }

      const response = await drive.files.create({
        requestBody: fileMetadata,
        media: media,
        fields: 'id',
      })

      return response.data.id!
    } catch (error) {
      console.error('Failed to upload to Google Drive:', error)
      throw new Error('Failed to upload to Google Drive')
    }
  }

  async connectOneDrive(accessToken: string, refreshToken: string): Promise<boolean> {
    try {
      // Test OneDrive connection
      const response = await fetch('https://graph.microsoft.com/v1.0/me', {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
        },
      })

      return response.ok
    } catch (error) {
      console.error('OneDrive connection failed:', error)
      return false
    }
  }

  async listOneDriveFiles(
    accessToken: string,
    refreshToken: string,
    folderId?: string
  ): Promise<CloudFile[]> {
    try {
      const endpoint = folderId 
        ? `https://graph.microsoft.com/v1.0/me/drive/items/${folderId}/children`
        : 'https://graph.microsoft.com/v1.0/me/drive/root/children'
      
      const response = await fetch(endpoint, {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
        },
      })

      if (!response.ok) {
        throw new Error('Failed to fetch OneDrive files')
      }

      const data = await response.json()
      
      return data.value?.map((file: any) => ({
        id: file.id,
        name: file.name,
        size: file.size,
        mimeType: file.file?.mimeType || 'application/octet-stream',
        createdTime: file.createdDateTime,
        modifiedTime: file.lastModifiedDateTime,
        webViewLink: file.webUrl,
        downloadUrl: file['@microsoft.graph.downloadUrl'],
      })) || []
    } catch (error) {
      console.error('Failed to list OneDrive files:', error)
      throw new Error('Failed to list OneDrive files')
    }
  }

  async downloadOneDriveFile(
    accessToken: string,
    refreshToken: string,
    fileId: string
  ): Promise<Buffer> {
    try {
      const response = await fetch(`https://graph.microsoft.com/v1.0/me/drive/items/${fileId}/content`, {
        headers: {
          'Authorization': `Bearer ${accessToken}`,
        },
      })

      if (!response.ok) {
        throw new Error('Failed to download OneDrive file')
      }

      const arrayBuffer = await response.arrayBuffer()
      return Buffer.from(arrayBuffer)
    } catch (error) {
      console.error('Failed to download OneDrive file:', error)
      throw new Error('Failed to download OneDrive file')
    }
  }

  async uploadToOneDrive(
    accessToken: string,
    refreshToken: string,
    fileBuffer: Buffer,
    fileName: string,
    mimeType: string,
    folderId?: string
  ): Promise<string> {
    try {
      const endpoint = folderId
        ? `https://graph.microsoft.com/v1.0/me/drive/items/${folderId}:/${fileName}:/content`
        : `https://graph.microsoft.com/v1.0/me/drive/root:/${fileName}:/content`

      const response = await fetch(endpoint, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${accessToken}`,
          'Content-Type': mimeType,
        },
        body: fileBuffer,
      })

      if (!response.ok) {
        throw new Error('Failed to upload to OneDrive')
      }

      const data = await response.json()
      return data.id
    } catch (error) {
      console.error('Failed to upload to OneDrive:', error)
      throw new Error('Failed to upload to OneDrive')
    }
  }
}