'use client'

import { useState, useEffect } from 'react'
import { motion } from 'framer-motion'
import toast from 'react-hot-toast'
import TicketTemplate from './ticket-template'
import { TicketData } from '@/types'
import { 
  CheckIcon, 
  XMarkIcon, 
  DocumentArrowDownIcon,
  ShareIcon,
  EyeIcon,
  PencilIcon
} from '@heroicons/react/24/outline'

interface TicketEditorProps {
  ticketId: string
  initialData: TicketData
  onSave: (data: TicketData) => void
  onCancel: () => void
}

export default function TicketEditor({ 
  ticketId, 
  initialData, 
  onSave, 
  onCancel 
}: TicketEditorProps) {
  const [data, setData] = useState<TicketData>(initialData)
  const [isPreviewMode, setIsPreviewMode] = useState(false)
  const [isSaving, setIsSaving] = useState(false)

  const handleDataChange = (newData: TicketData) => {
    setData(newData)
  }

  const handleSave = async () => {
    setIsSaving(true)
    try {
      await onSave(data)
      toast.success('Ticket saved successfully!')
    } catch (error) {
      toast.error('Failed to save ticket')
    } finally {
      setIsSaving(false)
    }
  }

  const handleExport = async () => {
    try {
      // This would integrate with the export functionality
      toast.success('Export functionality coming soon!')
    } catch (error) {
      toast.error('Failed to export ticket')
    }
  }

  const handleShare = async () => {
    try {
      // This would integrate with the sharing functionality
      toast.success('Share functionality coming soon!')
    } catch (error) {
      toast.error('Failed to share ticket')
    }
  }

  return (
    <div className="max-w-6xl mx-auto p-6">
      {/* Header */}
      <div className="flex justify-between items-center mb-6">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Edit Ticket</h1>
          <p className="text-gray-600">Review and modify the extracted ticket data</p>
        </div>
        
        <div className="flex items-center space-x-3">
          <button
            onClick={() => setIsPreviewMode(!isPreviewMode)}
            className={`flex items-center space-x-2 px-4 py-2 rounded-lg border transition-colors ${
              isPreviewMode
                ? 'bg-primary-50 border-primary-200 text-primary-700'
                : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'
            }`}
          >
            {isPreviewMode ? (
              <>
                <PencilIcon className="h-4 w-4" />
                <span>Edit</span>
              </>
            ) : (
              <>
                <EyeIcon className="h-4 w-4" />
                <span>Preview</span>
              </>
            )}
          </button>
          
          <button
            onClick={handleExport}
            className="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            <DocumentArrowDownIcon className="h-4 w-4" />
            <span>Export</span>
          </button>
          
          <button
            onClick={handleShare}
            className="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            <ShareIcon className="h-4 w-4" />
            <span>Share</span>
          </button>
        </div>
      </div>

      {/* Action Buttons */}
      <div className="flex justify-between items-center mb-6">
        <div className="flex items-center space-x-4">
          <button
            onClick={handleSave}
            disabled={isSaving}
            className="flex items-center space-x-2 btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <CheckIcon className="h-4 w-4" />
            <span>{isSaving ? 'Saving...' : 'Save Changes'}</span>
          </button>
          
          <button
            onClick={onCancel}
            className="flex items-center space-x-2 btn-secondary"
          >
            <XMarkIcon className="h-4 w-4" />
            <span>Cancel</span>
          </button>
        </div>
        
        <div className="text-sm text-gray-500">
          Ticket ID: {ticketId}
        </div>
      </div>

      {/* Editor/Preview */}
      <motion.div
        key={isPreviewMode ? 'preview' : 'edit'}
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3 }}
      >
        <TicketTemplate
          data={data}
          isEditable={!isPreviewMode}
          onDataChange={handleDataChange}
        />
      </motion.div>

      {/* Data Validation */}
      {!isPreviewMode && (
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
          className="mt-8 card"
        >
          <h3 className="text-lg font-semibold text-gray-900 mb-4">
            Data Validation
          </h3>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {[
              { field: 'passengerName', label: 'Passenger Name', required: true },
              { field: 'flightNumber', label: 'Flight Number', required: true },
              { field: 'bookingReference', label: 'Booking Reference', required: true },
              { field: 'departureDate', label: 'Departure Date', required: true },
              { field: 'departureTime', label: 'Departure Time', required: true },
              { field: 'arrivalDate', label: 'Arrival Date', required: true },
              { field: 'arrivalTime', label: 'Arrival Time', required: true },
              { field: 'departureAirport', label: 'Departure Airport', required: true },
              { field: 'arrivalAirport', label: 'Arrival Airport', required: true },
              { field: 'airline', label: 'Airline', required: true },
              { field: 'price', label: 'Price', required: false },
              { field: 'class', label: 'Class', required: false },
            ].map(({ field, label, required }) => {
              const value = data[field as keyof TicketData]
              const isValid = !required || (value && value.toString().trim() !== '')
              
              return (
                <div key={field} className="flex items-center space-x-3">
                  <div className={`w-3 h-3 rounded-full ${isValid ? 'bg-green-500' : 'bg-red-500'}`} />
                  <span className={`text-sm ${isValid ? 'text-gray-700' : 'text-red-600'}`}>
                    {label}
                    {required && <span className="text-red-500 ml-1">*</span>}
                  </span>
                </div>
              )
            })}
          </div>
          
          <div className="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p className="text-sm text-blue-800">
              <strong>Note:</strong> Fields marked with * are required. 
              Make sure all required fields are filled before saving.
            </p>
          </div>
        </motion.div>
      )}
    </div>
  )
}