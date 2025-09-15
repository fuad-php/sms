'use client'

import { useState, useEffect } from 'react'
import { motion } from 'framer-motion'
import toast from 'react-hot-toast'
import { UploadHistory as UploadHistoryType } from '@/types'
import { 
  CloudArrowUpIcon, 
  CheckCircleIcon, 
  XCircleIcon, 
  ClockIcon,
  DocumentIcon,
  PhotoIcon
} from '@heroicons/react/24/outline'

export default function UploadHistory() {
  const [uploads, setUploads] = useState<UploadHistoryType[]>([])
  const [loading, setLoading] = useState(true)
  const [filter, setFilter] = useState<'all' | 'completed' | 'failed' | 'processing'>('all')

  useEffect(() => {
    fetchUploadHistory()
  }, [])

  const fetchUploadHistory = async () => {
    try {
      setLoading(true)
      // This would fetch from your API
      // For now, we'll use mock data
      const mockUploads: UploadHistoryType[] = [
        {
          id: '1',
          userId: 'user1',
          fileName: 'flight_ticket_1.pdf',
          fileSize: 2048576, // 2MB
          fileType: 'application/pdf',
          status: 'COMPLETED',
          progress: 100,
          createdAt: new Date('2024-12-01T10:30:00'),
          completedAt: new Date('2024-12-01T10:32:00'),
        },
        {
          id: '2',
          userId: 'user1',
          fileName: 'flight_ticket_2.jpg',
          fileSize: 1536000, // 1.5MB
          fileType: 'image/jpeg',
          status: 'COMPLETED',
          progress: 100,
          createdAt: new Date('2024-11-28T14:15:00'),
          completedAt: new Date('2024-11-28T14:18:00'),
        },
        {
          id: '3',
          userId: 'user1',
          fileName: 'corrupted_ticket.pdf',
          fileSize: 512000, // 512KB
          fileType: 'application/pdf',
          status: 'FAILED',
          progress: 45,
          createdAt: new Date('2024-11-25T09:45:00'),
          errorMessage: 'File appears to be corrupted or password protected',
        },
        {
          id: '4',
          userId: 'user1',
          fileName: 'large_ticket.pdf',
          fileSize: 15728640, // 15MB
          fileType: 'application/pdf',
          status: 'PROCESSING',
          progress: 75,
          createdAt: new Date('2024-12-01T15:20:00'),
        },
      ]
      
      setUploads(mockUploads)
    } catch (error) {
      toast.error('Failed to fetch upload history')
    } finally {
      setLoading(false)
    }
  }

  const getStatusIcon = (status: UploadHistoryType['status']) => {
    switch (status) {
      case 'COMPLETED':
        return <CheckCircleIcon className="h-5 w-5 text-green-500" />
      case 'FAILED':
        return <XCircleIcon className="h-5 w-5 text-red-500" />
      case 'PROCESSING':
        return <ClockIcon className="h-5 w-5 text-blue-500" />
      case 'UPLOADING':
        return <CloudArrowUpIcon className="h-5 w-5 text-yellow-500" />
      default:
        return <ClockIcon className="h-5 w-5 text-gray-500" />
    }
  }

  const getStatusColor = (status: UploadHistoryType['status']) => {
    switch (status) {
      case 'COMPLETED':
        return 'bg-green-100 text-green-800'
      case 'FAILED':
        return 'bg-red-100 text-red-800'
      case 'PROCESSING':
        return 'bg-blue-100 text-blue-800'
      case 'UPLOADING':
        return 'bg-yellow-100 text-yellow-800'
      default:
        return 'bg-gray-100 text-gray-800'
    }
  }

  const getFileIcon = (fileType: string) => {
    if (fileType.startsWith('image/')) {
      return <PhotoIcon className="h-5 w-5 text-blue-500" />
    } else if (fileType === 'application/pdf') {
      return <DocumentIcon className="h-5 w-5 text-red-500" />
    } else {
      return <DocumentIcon className="h-5 w-5 text-gray-500" />
    }
  }

  const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
  }

  const formatDuration = (start: Date, end?: Date) => {
    if (!end) return 'In progress'
    const duration = end.getTime() - start.getTime()
    const seconds = Math.floor(duration / 1000)
    if (seconds < 60) return `${seconds}s`
    const minutes = Math.floor(seconds / 60)
    return `${minutes}m ${seconds % 60}s`
  }

  const filteredUploads = uploads.filter(upload => {
    if (filter === 'all') return true
    return upload.status.toLowerCase() === filter.toUpperCase()
  })

  if (loading) {
    return (
      <div className="card">
        <div className="flex items-center justify-center py-12">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          <span className="ml-3 text-gray-600">Loading upload history...</span>
        </div>
      </div>
    )
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-2xl font-bold text-gray-900">Upload History</h2>
          <p className="text-gray-600">Track your file uploads and processing status</p>
        </div>
        <div className="text-sm text-gray-500">
          {filteredUploads.length} upload{filteredUploads.length !== 1 ? 's' : ''}
        </div>
      </div>

      {/* Filter Tabs */}
      <div className="flex space-x-1 bg-gray-100 p-1 rounded-lg w-fit">
        {[
          { key: 'all', label: 'All' },
          { key: 'completed', label: 'Completed' },
          { key: 'processing', label: 'Processing' },
          { key: 'failed', label: 'Failed' },
        ].map(({ key, label }) => (
          <button
            key={key}
            onClick={() => setFilter(key as any)}
            className={`px-3 py-1 text-sm font-medium rounded-md transition-colors ${
              filter === key
                ? 'bg-white text-gray-900 shadow-sm'
                : 'text-gray-600 hover:text-gray-900'
            }`}
          >
            {label}
          </button>
        ))}
      </div>

      {/* Upload History List */}
      {filteredUploads.length === 0 ? (
        <div className="card text-center py-12">
          <CloudArrowUpIcon className="mx-auto h-12 w-12 text-gray-400 mb-4" />
          <h3 className="text-lg font-medium text-gray-900 mb-2">No uploads found</h3>
          <p className="text-gray-600">
            {filter === 'all' 
              ? 'Upload your first file to see it here'
              : `No ${filter} uploads found`
            }
          </p>
        </div>
      ) : (
        <div className="space-y-4">
          {filteredUploads.map((upload, index) => (
            <motion.div
              key={upload.id}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: index * 0.1 }}
              className="card"
            >
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4">
                  <div className="flex-shrink-0">
                    {getFileIcon(upload.fileType)}
                  </div>
                  
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center space-x-2 mb-1">
                      <h3 className="text-lg font-semibold text-gray-900 truncate">
                        {upload.fileName}
                      </h3>
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(upload.status)}`}>
                        {getStatusIcon(upload.status)}
                        <span className="ml-1">{upload.status}</span>
                      </span>
                    </div>
                    
                    <div className="flex items-center space-x-4 text-sm text-gray-600">
                      <span>{formatFileSize(upload.fileSize)}</span>
                      <span>•</span>
                      <span>{upload.fileType}</span>
                      <span>•</span>
                      <span>
                        {formatDuration(upload.createdAt, upload.completedAt)}
                      </span>
                    </div>
                    
                    {upload.errorMessage && (
                      <div className="mt-2 text-sm text-red-600">
                        Error: {upload.errorMessage}
                      </div>
                    )}
                  </div>
                </div>
                
                {/* Progress Bar */}
                {upload.status === 'PROCESSING' && (
                  <div className="flex items-center space-x-3">
                    <div className="w-24 bg-gray-200 rounded-full h-2">
                      <div
                        className="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        style={{ width: `${upload.progress}%` }}
                      />
                    </div>
                    <span className="text-sm text-gray-600">{upload.progress}%</span>
                  </div>
                )}
              </div>
            </motion.div>
          ))}
        </div>
      )}

      {/* Statistics */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="card text-center">
          <div className="text-2xl font-bold text-gray-900">
            {uploads.filter(u => u.status === 'COMPLETED').length}
          </div>
          <div className="text-sm text-gray-600">Completed</div>
        </div>
        
        <div className="card text-center">
          <div className="text-2xl font-bold text-blue-600">
            {uploads.filter(u => u.status === 'PROCESSING').length}
          </div>
          <div className="text-sm text-gray-600">Processing</div>
        </div>
        
        <div className="card text-center">
          <div className="text-2xl font-bold text-red-600">
            {uploads.filter(u => u.status === 'FAILED').length}
          </div>
          <div className="text-sm text-gray-600">Failed</div>
        </div>
        
        <div className="card text-center">
          <div className="text-2xl font-bold text-gray-900">
            {formatFileSize(uploads.reduce((total, u) => total + u.fileSize, 0))}
          </div>
          <div className="text-sm text-gray-600">Total Size</div>
        </div>
      </div>
    </div>
  )
}