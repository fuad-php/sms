'use client'

import { useState, useEffect } from 'react'
import { motion } from 'framer-motion'
import toast from 'react-hot-toast'
import { Ticket, TicketData } from '@/types'
import { 
  DocumentTextIcon, 
  EyeIcon, 
  PencilIcon, 
  TrashIcon,
  CalendarIcon,
  ClockIcon,
  CheckCircleIcon,
  ExclamationTriangleIcon
} from '@heroicons/react/24/outline'

export default function TicketList() {
  const [tickets, setTickets] = useState<Ticket[]>([])
  const [loading, setLoading] = useState(true)
  const [selectedTicket, setSelectedTicket] = useState<Ticket | null>(null)
  const [showEditor, setShowEditor] = useState(false)

  useEffect(() => {
    fetchTickets()
  }, [])

  const fetchTickets = async () => {
    try {
      setLoading(true)
      // This would fetch from your API
      // For now, we'll use mock data
      const mockTickets: Ticket[] = [
        {
          id: '1',
          userId: 'user1',
          originalFileName: 'flight_ticket_1.pdf',
          originalFileType: 'application/pdf',
          originalFileUrl: '/uploads/ticket1.pdf',
          extractedData: {
            passengerName: 'John Doe',
            flightNumber: 'AA1234',
            bookingReference: 'ABC123',
            departureDate: '2024-12-15',
            departureTime: '14:30',
            arrivalDate: '2024-12-15',
            arrivalTime: '18:45',
            departureAirport: 'LAX',
            arrivalAirport: 'JFK',
            airline: 'American Airlines',
            price: '299.00',
            currency: 'USD',
            class: 'economy',
          },
          standardizedData: {
            passengerName: 'John Doe',
            flightNumber: 'AA1234',
            bookingReference: 'ABC123',
            departureDate: '2024-12-15',
            departureTime: '14:30',
            arrivalDate: '2024-12-15',
            arrivalTime: '18:45',
            departureAirport: 'LAX',
            arrivalAirport: 'JFK',
            airline: 'American Airlines',
            price: '299.00',
            currency: 'USD',
            class: 'economy',
          },
          status: 'review',
          createdAt: new Date('2024-12-01'),
          updatedAt: new Date('2024-12-01'),
        },
        {
          id: '2',
          userId: 'user1',
          originalFileName: 'flight_ticket_2.jpg',
          originalFileType: 'image/jpeg',
          originalFileUrl: '/uploads/ticket2.jpg',
          extractedData: {
            passengerName: 'Jane Smith',
            flightNumber: 'UA5678',
            bookingReference: 'DEF456',
            departureDate: '2024-12-20',
            departureTime: '09:15',
            arrivalDate: '2024-12-20',
            arrivalTime: '12:30',
            departureAirport: 'SFO',
            arrivalAirport: 'LAX',
            airline: 'United Airlines',
            price: '189.00',
            currency: 'USD',
            class: 'economy',
          },
          standardizedData: {
            passengerName: 'Jane Smith',
            flightNumber: 'UA5678',
            bookingReference: 'DEF456',
            departureDate: '2024-12-20',
            departureTime: '09:15',
            arrivalDate: '2024-12-20',
            arrivalTime: '12:30',
            departureAirport: 'SFO',
            arrivalAirport: 'LAX',
            airline: 'United Airlines',
            price: '189.00',
            currency: 'USD',
            class: 'economy',
          },
          status: 'approved',
          createdAt: new Date('2024-11-28'),
          updatedAt: new Date('2024-11-29'),
        },
      ]
      
      setTickets(mockTickets)
    } catch (error) {
      toast.error('Failed to fetch tickets')
    } finally {
      setLoading(false)
    }
  }

  const getStatusIcon = (status: Ticket['status']) => {
    switch (status) {
      case 'approved':
        return <CheckCircleIcon className="h-5 w-5 text-green-500" />
      case 'review':
        return <ExclamationTriangleIcon className="h-5 w-5 text-yellow-500" />
      case 'processing':
        return <ClockIcon className="h-5 w-5 text-blue-500" />
      case 'exported':
        return <CheckCircleIcon className="h-5 w-5 text-purple-500" />
      default:
        return <ClockIcon className="h-5 w-5 text-gray-500" />
    }
  }

  const getStatusColor = (status: Ticket['status']) => {
    switch (status) {
      case 'approved':
        return 'bg-green-100 text-green-800'
      case 'review':
        return 'bg-yellow-100 text-yellow-800'
      case 'processing':
        return 'bg-blue-100 text-blue-800'
      case 'exported':
        return 'bg-purple-100 text-purple-800'
      default:
        return 'bg-gray-100 text-gray-800'
    }
  }

  const handleEdit = (ticket: Ticket) => {
    setSelectedTicket(ticket)
    setShowEditor(true)
  }

  const handleSave = async (data: TicketData) => {
    if (!selectedTicket) return
    
    try {
      // This would save to your API
      toast.success('Ticket updated successfully!')
      setShowEditor(false)
      setSelectedTicket(null)
      fetchTickets() // Refresh the list
    } catch (error) {
      toast.error('Failed to update ticket')
    }
  }

  const handleDelete = async (ticketId: string) => {
    if (!confirm('Are you sure you want to delete this ticket?')) return
    
    try {
      // This would delete from your API
      setTickets(prev => prev.filter(t => t.id !== ticketId))
      toast.success('Ticket deleted successfully!')
    } catch (error) {
      toast.error('Failed to delete ticket')
    }
  }

  if (showEditor && selectedTicket) {
    return (
      <div>
        <button
          onClick={() => setShowEditor(false)}
          className="mb-4 text-primary-600 hover:text-primary-700 font-medium"
        >
          ← Back to tickets
        </button>
        {/* TicketEditor component would go here */}
        <div className="card">
          <h3 className="text-lg font-semibold mb-4">Edit Ticket</h3>
          <p className="text-gray-600">Editor component would be rendered here</p>
        </div>
      </div>
    )
  }

  if (loading) {
    return (
      <div className="card">
        <div className="flex items-center justify-center py-12">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          <span className="ml-3 text-gray-600">Loading tickets...</span>
        </div>
      </div>
    )
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-2xl font-bold text-gray-900">My Tickets</h2>
          <p className="text-gray-600">Manage your standardized ticket copies</p>
        </div>
        <div className="text-sm text-gray-500">
          {tickets.length} ticket{tickets.length !== 1 ? 's' : ''}
        </div>
      </div>

      {/* Tickets List */}
      {tickets.length === 0 ? (
        <div className="card text-center py-12">
          <DocumentTextIcon className="mx-auto h-12 w-12 text-gray-400 mb-4" />
          <h3 className="text-lg font-medium text-gray-900 mb-2">No tickets yet</h3>
          <p className="text-gray-600 mb-4">
            Upload your first ticket to get started with Pro Ticketer
          </p>
        </div>
      ) : (
        <div className="grid gap-4">
          {tickets.map((ticket, index) => (
            <motion.div
              key={ticket.id}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: index * 0.1 }}
              className="card hover:shadow-md transition-shadow"
            >
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4">
                  <div className="flex-shrink-0">
                    <DocumentTextIcon className="h-8 w-8 text-primary-600" />
                  </div>
                  
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center space-x-2 mb-1">
                      <h3 className="text-lg font-semibold text-gray-900 truncate">
                        {ticket.extractedData.passengerName || 'Unknown Passenger'}
                      </h3>
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(ticket.status)}`}>
                        {getStatusIcon(ticket.status)}
                        <span className="ml-1">{ticket.status}</span>
                      </span>
                    </div>
                    
                    <div className="flex items-center space-x-4 text-sm text-gray-600">
                      <span>Flight: {ticket.extractedData.flightNumber || 'N/A'}</span>
                      <span>•</span>
                      <span>{ticket.extractedData.departureAirport || 'N/A'} → {ticket.extractedData.arrivalAirport || 'N/A'}</span>
                      <span>•</span>
                      <span>{ticket.extractedData.airline || 'N/A'}</span>
                    </div>
                    
                    <div className="flex items-center space-x-4 text-xs text-gray-500 mt-1">
                      <div className="flex items-center space-x-1">
                        <CalendarIcon className="h-3 w-3" />
                        <span>{new Date(ticket.createdAt).toLocaleDateString()}</span>
                      </div>
                      <span>•</span>
                      <span>{ticket.originalFileName}</span>
                    </div>
                  </div>
                </div>
                
                <div className="flex items-center space-x-2">
                  <button
                    onClick={() => handleEdit(ticket)}
                    className="p-2 text-gray-400 hover:text-primary-600 transition-colors"
                    title="Edit ticket"
                  >
                    <PencilIcon className="h-4 w-4" />
                  </button>
                  
                  <button
                    onClick={() => handleDelete(ticket.id)}
                    className="p-2 text-gray-400 hover:text-red-600 transition-colors"
                    title="Delete ticket"
                  >
                    <TrashIcon className="h-4 w-4" />
                  </button>
                </div>
              </div>
            </motion.div>
          ))}
        </div>
      )}
    </div>
  )
}