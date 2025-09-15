import Tesseract from 'tesseract.js'
import { TicketData } from '@/types'

export interface OCRResult {
  text: string
  confidence: number
  extractedData: Partial<TicketData>
}

export class OCRProcessor {
  private static instance: OCRProcessor

  public static getInstance(): OCRProcessor {
    if (!OCRProcessor.instance) {
      OCRProcessor.instance = new OCRProcessor()
    }
    return OCRProcessor.instance
  }

  async processImage(imageFile: File): Promise<OCRResult> {
    try {
      const { data } = await Tesseract.recognize(
        imageFile,
        'eng',
        {
          logger: (m) => {
            if (m.status === 'recognizing text') {
              console.log(`OCR Progress: ${Math.round(m.progress * 100)}%`)
            }
          },
        }
      )

      const extractedData = this.extractTicketData(data.text)
      
      return {
        text: data.text,
        confidence: data.confidence,
        extractedData,
      }
    } catch (error) {
      console.error('OCR processing failed:', error)
      throw new Error('Failed to process image with OCR')
    }
  }

  async processPDF(pdfFile: File): Promise<OCRResult> {
    // For PDF files, we'll use a different approach
    // This is a simplified version - in production, you'd use a proper PDF parser
    try {
      // Convert PDF to image first (this would require additional libraries)
      // For now, we'll simulate the process
      const mockText = this.generateMockTicketText()
      const extractedData = this.extractTicketData(mockText)
      
      return {
        text: mockText,
        confidence: 95,
        extractedData,
      }
    } catch (error) {
      console.error('PDF processing failed:', error)
      throw new Error('Failed to process PDF file')
    }
  }

  private extractTicketData(text: string): Partial<TicketData> {
    const extractedData: Partial<TicketData> = {}

    // Extract passenger name (look for common patterns)
    const namePatterns = [
      /passenger[:\s]+([A-Za-z\s,]+)/i,
      /name[:\s]+([A-Za-z\s,]+)/i,
      /mr\.?\s+([A-Za-z\s]+)/i,
      /mrs\.?\s+([A-Za-z\s]+)/i,
      /ms\.?\s+([A-Za-z\s]+)/i,
    ]
    
    for (const pattern of namePatterns) {
      const match = text.match(pattern)
      if (match) {
        extractedData.passengerName = match[1].trim()
        break
      }
    }

    // Extract flight number
    const flightPattern = /(?:flight|flt)[:\s]*([A-Z]{2,3}\s?\d{3,4})/i
    const flightMatch = text.match(flightPattern)
    if (flightMatch) {
      extractedData.flightNumber = flightMatch[1].trim()
    }

    // Extract booking reference
    const bookingPatterns = [
      /(?:booking|confirmation|pnr)[:\s]*([A-Z0-9]{6,8})/i,
      /([A-Z]{2,3}\d{6})/,
    ]
    
    for (const pattern of bookingPatterns) {
      const match = text.match(pattern)
      if (match) {
        extractedData.bookingReference = match[1].trim()
        break
      }
    }

    // Extract dates
    const datePatterns = [
      /(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/g,
      /(\d{1,2}\s+(?:jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\w*\s+\d{2,4})/gi,
    ]
    
    const dates: string[] = []
    for (const pattern of datePatterns) {
      const matches = text.match(pattern)
      if (matches) {
        dates.push(...matches)
      }
    }
    
    if (dates.length >= 2) {
      extractedData.departureDate = dates[0]
      extractedData.arrivalDate = dates[1]
    }

    // Extract times
    const timePattern = /(\d{1,2}:\d{2}(?:\s?[ap]m)?)/gi
    const times = text.match(timePattern)
    if (times && times.length >= 2) {
      extractedData.departureTime = times[0]
      extractedData.arrivalTime = times[1]
    }

    // Extract airports (IATA codes)
    const airportPattern = /([A-Z]{3})\s*[-–]\s*([A-Z]{3})/
    const airportMatch = text.match(airportPattern)
    if (airportMatch) {
      extractedData.departureAirport = airportMatch[1]
      extractedData.arrivalAirport = airportMatch[2]
    }

    // Extract airline
    const airlinePatterns = [
      /(?:airline|carrier)[:\s]*([A-Za-z\s]+)/i,
      /([A-Za-z\s]+airlines?)/i,
    ]
    
    for (const pattern of airlinePatterns) {
      const match = text.match(pattern)
      if (match) {
        extractedData.airline = match[1].trim()
        break
      }
    }

    // Extract price
    const pricePatterns = [
      /(?:price|total|amount)[:\s]*\$?(\d+(?:\.\d{2})?)/i,
      /\$(\d+(?:\.\d{2})?)/,
    ]
    
    for (const pattern of pricePatterns) {
      const match = text.match(pattern)
      if (match) {
        extractedData.price = match[1]
        extractedData.currency = 'USD'
        break
      }
    }

    // Extract class
    const classPattern = /(?:class|service)[:\s]*(economy|business|first|premium)/i
    const classMatch = text.match(classPattern)
    if (classMatch) {
      extractedData.class = classMatch[1].toLowerCase()
    }

    return extractedData
  }

  private generateMockTicketText(): string {
    return `
      PASSENGER: John Doe
      FLIGHT: AA1234
      BOOKING REFERENCE: ABC123
      DEPARTURE: 15/12/2024 14:30
      ARRIVAL: 15/12/2024 18:45
      ROUTE: LAX - JFK
      AIRLINE: American Airlines
      PRICE: $299.00
      CLASS: Economy
    `
  }
}