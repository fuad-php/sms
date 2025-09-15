'use client'

import { TicketData } from '@/types'
import { motion } from 'framer-motion'

interface TicketTemplateProps {
  data: TicketData
  isEditable?: boolean
  onDataChange?: (data: TicketData) => void
}

export default function TicketTemplate({ 
  data, 
  isEditable = false, 
  onDataChange 
}: TicketTemplateProps) {
  const handleInputChange = (field: keyof TicketData, value: string) => {
    if (isEditable && onDataChange) {
      onDataChange({
        ...data,
        [field]: value,
      })
    }
  }

  const formatDate = (dateString: string) => {
    if (!dateString) return ''
    try {
      const date = new Date(dateString)
      return date.toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      })
    } catch {
      return dateString
    }
  }

  const formatTime = (timeString: string) => {
    if (!timeString) return ''
    try {
      const time = new Date(`2000-01-01T${timeString}`)
      return time.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
      })
    } catch {
      return timeString
    }
  }

  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.95 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ duration: 0.3 }}
      className="ticket-template max-w-2xl mx-auto"
    >
      {/* Header */}
      <div className="bg-gradient-to-r from-primary-600 to-primary-800 text-white p-6 rounded-t-lg">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold">Pro Ticketer</h1>
            <p className="text-primary-100 text-sm">Professional Travel Document</p>
          </div>
          <div className="text-right">
            <div className="text-xs text-primary-200">Booking Reference</div>
            <div className="font-mono text-lg font-semibold">
              {isEditable ? (
                <input
                  type="text"
                  value={data.bookingReference || ''}
                  onChange={(e) => handleInputChange('bookingReference', e.target.value)}
                  className="bg-transparent border-b border-primary-300 text-white font-mono text-lg font-semibold focus:outline-none focus:border-white"
                  placeholder="ABC123"
                />
              ) : (
                data.bookingReference || 'ABC123'
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="p-6 space-y-6">
        {/* Passenger Information */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Passenger Name
            </label>
            {isEditable ? (
              <input
                type="text"
                value={data.passengerName || ''}
                onChange={(e) => handleInputChange('passengerName', e.target.value)}
                className="input-field"
                placeholder="John Doe"
              />
            ) : (
              <div className="text-lg font-semibold text-gray-900">
                {data.passengerName || 'John Doe'}
              </div>
            )}
          </div>
          
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Flight Number
            </label>
            {isEditable ? (
              <input
                type="text"
                value={data.flightNumber || ''}
                onChange={(e) => handleInputChange('flightNumber', e.target.value)}
                className="input-field"
                placeholder="AA1234"
              />
            ) : (
              <div className="text-lg font-semibold text-gray-900">
                {data.flightNumber || 'AA1234'}
              </div>
            )}
          </div>
        </div>

        {/* Flight Details */}
        <div className="bg-gray-50 rounded-lg p-4">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Flight Details</h3>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-1">
                Departure
              </label>
              <div className="flex items-center space-x-2">
                <div className="text-2xl font-bold text-primary-600">
                  {data.departureAirport || 'LAX'}
                </div>
                <div className="text-gray-400">→</div>
                <div className="text-2xl font-bold text-primary-600">
                  {data.arrivalAirport || 'JFK'}
                </div>
              </div>
              {isEditable && (
                <div className="mt-2 grid grid-cols-2 gap-2">
                  <input
                    type="text"
                    value={data.departureAirport || ''}
                    onChange={(e) => handleInputChange('departureAirport', e.target.value)}
                    className="input-field text-sm"
                    placeholder="LAX"
                  />
                  <input
                    type="text"
                    value={data.arrivalAirport || ''}
                    onChange={(e) => handleInputChange('arrivalAirport', e.target.value)}
                    className="input-field text-sm"
                    placeholder="JFK"
                  />
                </div>
              )}
            </div>
            
            <div>
              <label className="block text-sm font-medium text-gray-600 mb-1">
                Airline
              </label>
              {isEditable ? (
                <input
                  type="text"
                  value={data.airline || ''}
                  onChange={(e) => handleInputChange('airline', e.target.value)}
                  className="input-field"
                  placeholder="American Airlines"
                />
              ) : (
                <div className="text-lg font-semibold text-gray-900">
                  {data.airline || 'American Airlines'}
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Date and Time */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Departure Date & Time
            </label>
            <div className="space-y-2">
              {isEditable ? (
                <>
                  <input
                    type="date"
                    value={data.departureDate || ''}
                    onChange={(e) => handleInputChange('departureDate', e.target.value)}
                    className="input-field"
                  />
                  <input
                    type="time"
                    value={data.departureTime || ''}
                    onChange={(e) => handleInputChange('departureTime', e.target.value)}
                    className="input-field"
                  />
                </>
              ) : (
                <div className="text-lg font-semibold text-gray-900">
                  {formatDate(data.departureDate || '2024-12-15')} at {formatTime(data.departureTime || '14:30')}
                </div>
              )}
            </div>
          </div>
          
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Arrival Date & Time
            </label>
            <div className="space-y-2">
              {isEditable ? (
                <>
                  <input
                    type="date"
                    value={data.arrivalDate || ''}
                    onChange={(e) => handleInputChange('arrivalDate', e.target.value)}
                    className="input-field"
                  />
                  <input
                    type="time"
                    value={data.arrivalTime || ''}
                    onChange={(e) => handleInputChange('arrivalTime', e.target.value)}
                    className="input-field"
                  />
                </>
              ) : (
                <div className="text-lg font-semibold text-gray-900">
                  {formatDate(data.arrivalDate || '2024-12-15')} at {formatTime(data.arrivalTime || '18:45')}
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Additional Information */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Class
            </label>
            {isEditable ? (
              <select
                value={data.class || 'economy'}
                onChange={(e) => handleInputChange('class', e.target.value)}
                className="input-field"
              >
                <option value="economy">Economy</option>
                <option value="premium">Premium Economy</option>
                <option value="business">Business</option>
                <option value="first">First Class</option>
              </select>
            ) : (
              <div className="text-lg font-semibold text-gray-900 capitalize">
                {data.class || 'Economy'}
              </div>
            )}
          </div>
          
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Seat
            </label>
            {isEditable ? (
              <input
                type="text"
                value={data.seatNumber || ''}
                onChange={(e) => handleInputChange('seatNumber', e.target.value)}
                className="input-field"
                placeholder="12A"
              />
            ) : (
              <div className="text-lg font-semibold text-gray-900">
                {data.seatNumber || '12A'}
              </div>
            )}
          </div>
          
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Price
            </label>
            {isEditable ? (
              <div className="flex">
                <select
                  value={data.currency || 'USD'}
                  onChange={(e) => handleInputChange('currency', e.target.value)}
                  className="input-field rounded-r-none border-r-0"
                >
                  <option value="USD">USD</option>
                  <option value="EUR">EUR</option>
                  <option value="GBP">GBP</option>
                </select>
                <input
                  type="number"
                  value={data.price || ''}
                  onChange={(e) => handleInputChange('price', e.target.value)}
                  className="input-field rounded-l-none"
                  placeholder="299.00"
                  step="0.01"
                />
              </div>
            ) : (
              <div className="text-lg font-semibold text-gray-900">
                {data.currency || 'USD'} {data.price || '299.00'}
              </div>
            )}
          </div>
        </div>

        {/* Additional Notes */}
        {isEditable && (
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              Additional Notes
            </label>
            <textarea
              value={data.additionalNotes || ''}
              onChange={(e) => handleInputChange('additionalNotes', e.target.value)}
              className="input-field"
              rows={3}
              placeholder="Any additional information..."
            />
          </div>
        )}
      </div>

      {/* Footer */}
      <div className="bg-gray-100 px-6 py-4 rounded-b-lg">
        <div className="flex justify-between items-center text-sm text-gray-600">
          <div>
            Generated by Pro Ticketer
          </div>
          <div>
            {new Date().toLocaleDateString()}
          </div>
        </div>
      </div>
    </motion.div>
  )
}