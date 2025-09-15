'use client'

import { useState, useCallback } from 'react'
import { useDropzone } from 'react-dropzone'
import { motion } from 'framer-motion'
import toast from 'react-hot-toast'
import { 
  CloudArrowUpIcon, 
  DocumentIcon, 
  PhotoIcon,
  XMarkIcon,
  CheckCircleIcon
} from '@heroicons/react/24/outline'

interface UploadedFile {
  file: File
  id: string
  status: 'uploading' | 'processing' | 'completed' | 'error'
  progress: number
  error?: string
}

export default function FileUpload() {
  const [uploadedFiles, setUploadedFiles] = useState<UploadedFile[]>([])
  const [isUploading, setIsUploading] = useState(false)

  const onDrop = useCallback((acceptedFiles: File[]) => {
    const newFiles: UploadedFile[] = acceptedFiles.map(file => ({
      file,
      id: Math.random().toString(36).substr(2, 9),
      status: 'uploading',
      progress: 0,
    }))

    setUploadedFiles(prev => [...prev, ...newFiles])
    handleUpload(newFiles)
  }, [])

  const { getRootProps, getInputProps, isDragActive } = useDropzone({
    onDrop,
    accept: {
      'application/pdf': ['.pdf'],
      'image/*': ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.tiff'],
      'application/msword': ['.doc'],
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document': ['.docx'],
    },
    maxSize: 10 * 1024 * 1024, // 10MB
    multiple: true,
  })

  const handleUpload = async (files: UploadedFile[]) => {
    setIsUploading(true)

    for (const fileData of files) {
      try {
        // Simulate upload progress
        for (let progress = 0; progress <= 100; progress += 10) {
          await new Promise(resolve => setTimeout(resolve, 100))
          setUploadedFiles(prev => 
            prev.map(f => 
              f.id === fileData.id 
                ? { ...f, progress }
                : f
            )
          )
        }

        // Update status to processing
        setUploadedFiles(prev => 
          prev.map(f => 
            f.id === fileData.id 
              ? { ...f, status: 'processing' }
              : f
          )
        )

        // Simulate processing
        await new Promise(resolve => setTimeout(resolve, 2000))

        // Update status to completed
        setUploadedFiles(prev => 
          prev.map(f => 
            f.id === fileData.id 
              ? { ...f, status: 'completed' }
              : f
          )
        )

        toast.success(`${fileData.file.name} uploaded successfully!`)
      } catch (error) {
        setUploadedFiles(prev => 
          prev.map(f => 
            f.id === fileData.id 
              ? { 
                  ...f, 
                  status: 'error', 
                  error: 'Upload failed' 
                }
              : f
          )
        )
        toast.error(`Failed to upload ${fileData.file.name}`)
      }
    }

    setIsUploading(false)
  }

  const removeFile = (id: string) => {
    setUploadedFiles(prev => prev.filter(f => f.id !== id))
  }

  const getFileIcon = (file: File) => {
    if (file.type.startsWith('image/')) {
      return <PhotoIcon className="h-8 w-8 text-blue-500" />
    } else if (file.type === 'application/pdf') {
      return <DocumentIcon className="h-8 w-8 text-red-500" />
    } else {
      return <DocumentIcon className="h-8 w-8 text-gray-500" />
    }
  }

  const getStatusIcon = (status: UploadedFile['status']) => {
    switch (status) {
      case 'completed':
        return <CheckCircleIcon className="h-5 w-5 text-green-500" />
      case 'error':
        return <XMarkIcon className="h-5 w-5 text-red-500" />
      default:
        return <div className="h-5 w-5 border-2 border-primary-500 border-t-transparent rounded-full animate-spin" />
    }
  }

  return (
    <div className="space-y-6">
      {/* Upload Area */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="card"
      >
        <div
          {...getRootProps()}
          className={`border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors duration-200 ${
            isDragActive
              ? 'border-primary-500 bg-primary-50'
              : 'border-gray-300 hover:border-primary-400 hover:bg-gray-50'
          }`}
        >
          <input {...getInputProps()} />
          <CloudArrowUpIcon className="mx-auto h-12 w-12 text-gray-400 mb-4" />
          <p className="text-lg font-medium text-gray-900 mb-2">
            {isDragActive
              ? 'Drop your files here'
              : 'Drag & drop your ticket files here'}
          </p>
          <p className="text-sm text-gray-500 mb-4">
            or click to browse files
          </p>
          <p className="text-xs text-gray-400">
            Supports PDF, images (PNG, JPG, JPEG), and Word documents (DOC, DOCX)
            <br />
            Maximum file size: 10MB
          </p>
        </div>
      </motion.div>

      {/* Uploaded Files */}
      {uploadedFiles.length > 0 && (
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          className="card"
        >
          <h3 className="text-lg font-semibold text-gray-900 mb-4">
            Uploaded Files
          </h3>
          <div className="space-y-3">
            {uploadedFiles.map((fileData) => (
              <div
                key={fileData.id}
                className="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
              >
                <div className="flex items-center space-x-3">
                  {getFileIcon(fileData.file)}
                  <div>
                    <p className="text-sm font-medium text-gray-900">
                      {fileData.file.name}
                    </p>
                    <p className="text-xs text-gray-500">
                      {(fileData.file.size / 1024 / 1024).toFixed(2)} MB
                    </p>
                  </div>
                </div>

                <div className="flex items-center space-x-3">
                  {fileData.status === 'uploading' && (
                    <div className="w-24 bg-gray-200 rounded-full h-2">
                      <div
                        className="bg-primary-600 h-2 rounded-full transition-all duration-300"
                        style={{ width: `${fileData.progress}%` }}
                      />
                    </div>
                  )}
                  
                  {getStatusIcon(fileData.status)}
                  
                  <button
                    onClick={() => removeFile(fileData.id)}
                    className="text-gray-400 hover:text-red-500 transition-colors"
                  >
                    <XMarkIcon className="h-5 w-5" />
                  </button>
                </div>
              </div>
            ))}
          </div>
        </motion.div>
      )}

      {/* Upload Tips */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.2 }}
        className="card bg-blue-50 border-blue-200"
      >
        <h4 className="text-sm font-medium text-blue-900 mb-2">
          💡 Upload Tips
        </h4>
        <ul className="text-sm text-blue-800 space-y-1">
          <li>• Ensure your ticket images are clear and well-lit for better OCR accuracy</li>
          <li>• PDF files with selectable text will process faster than scanned images</li>
          <li>• For best results, avoid heavily rotated or skewed images</li>
          <li>• Multiple tickets can be uploaded at once for batch processing</li>
        </ul>
      </motion.div>
    </div>
  )
}