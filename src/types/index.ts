export interface User {
  id: string
  email: string
  name: string
  role: 'admin' | 'user' | 'viewer'
  avatar?: string
  createdAt: Date
  updatedAt: Date
}

export interface Ticket {
  id: string
  userId: string
  originalFileName: string
  originalFileType: string
  originalFileUrl: string
  extractedData: TicketData
  standardizedData: TicketData
  status: 'processing' | 'review' | 'approved' | 'exported'
  createdAt: Date
  updatedAt: Date
  exportedAt?: Date
}

export interface TicketData {
  passengerName: string
  flightNumber: string
  bookingReference: string
  departureDate: string
  departureTime: string
  arrivalDate: string
  arrivalTime: string
  departureAirport: string
  arrivalAirport: string
  airline: string
  price: string
  currency: string
  seatNumber?: string
  class: string
  additionalNotes?: string
}

export interface UploadHistory {
  id: string
  userId: string
  fileName: string
  fileSize: number
  fileType: string
  status: 'uploading' | 'processing' | 'completed' | 'failed'
  progress: number
  createdAt: Date
  completedAt?: Date
  errorMessage?: string
}

export interface CloudStorageConfig {
  provider: 'google' | 'onedrive'
  accessToken: string
  refreshToken: string
  folderId?: string
}

export interface ApiResponse<T = any> {
  success: boolean
  data?: T
  error?: string
  message?: string
}

export interface PaginationParams {
  page: number
  limit: number
  sortBy?: string
  sortOrder?: 'asc' | 'desc'
}

export interface PaginatedResponse<T> {
  data: T[]
  pagination: {
    page: number
    limit: number
    total: number
    totalPages: number
    hasNext: boolean
    hasPrev: boolean
  }
}