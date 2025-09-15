import jsPDF from 'jspdf'
import html2canvas from 'html2canvas'
import { TicketData } from '@/types'

export interface ExportOptions {
  format: 'pdf' | 'image'
  quality: 'low' | 'medium' | 'high'
  watermark?: string
  signature?: string
  includeOriginal?: boolean
}

export class TicketExporter {
  private static instance: TicketExporter

  public static getInstance(): TicketExporter {
    if (!TicketExporter.instance) {
      TicketExporter.instance = new TicketExporter()
    }
    return TicketExporter.instance
  }

  async exportToPDF(
    ticketData: TicketData,
    options: ExportOptions = { format: 'pdf', quality: 'high' }
  ): Promise<Blob> {
    try {
      // Create a temporary container for the ticket template
      const container = document.createElement('div')
      container.style.position = 'absolute'
      container.style.left = '-9999px'
      container.style.top = '0'
      container.style.width = '800px'
      container.style.backgroundColor = 'white'
      container.style.padding = '20px'
      
      // Generate the ticket HTML
      container.innerHTML = this.generateTicketHTML(ticketData, options)
      document.body.appendChild(container)

      // Convert to canvas
      const canvas = await html2canvas(container, {
        scale: options.quality === 'high' ? 2 : options.quality === 'medium' ? 1.5 : 1,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
      })

      // Remove the temporary container
      document.body.removeChild(container)

      // Create PDF
      const imgData = canvas.toDataURL('image/png')
      const pdf = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a4',
      })

      const imgWidth = 210 // A4 width in mm
      const pageHeight = 295 // A4 height in mm
      const imgHeight = (canvas.height * imgWidth) / canvas.width
      let heightLeft = imgHeight

      let position = 0

      pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight)
      heightLeft -= pageHeight

      while (heightLeft >= 0) {
        position = heightLeft - imgHeight
        pdf.addPage()
        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight)
        heightLeft -= pageHeight
      }

      // Add watermark if specified
      if (options.watermark) {
        this.addWatermark(pdf, options.watermark)
      }

      // Add signature if specified
      if (options.signature) {
        this.addSignature(pdf, options.signature)
      }

      return pdf.output('blob')
    } catch (error) {
      console.error('PDF export failed:', error)
      throw new Error('Failed to export PDF')
    }
  }

  async exportToImage(
    ticketData: TicketData,
    options: ExportOptions = { format: 'image', quality: 'high' }
  ): Promise<Blob> {
    try {
      // Create a temporary container for the ticket template
      const container = document.createElement('div')
      container.style.position = 'absolute'
      container.style.left = '-9999px'
      container.style.top = '0'
      container.style.width = '800px'
      container.style.backgroundColor = 'white'
      container.style.padding = '20px'
      
      // Generate the ticket HTML
      container.innerHTML = this.generateTicketHTML(ticketData, options)
      document.body.appendChild(container)

      // Convert to canvas
      const canvas = await html2canvas(container, {
        scale: options.quality === 'high' ? 2 : options.quality === 'medium' ? 1.5 : 1,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
      })

      // Remove the temporary container
      document.body.removeChild(container)

      // Convert to blob
      return new Promise((resolve) => {
        canvas.toBlob((blob) => {
          if (blob) {
            resolve(blob)
          } else {
            throw new Error('Failed to create image blob')
          }
        }, 'image/png', 0.95)
      })
    } catch (error) {
      console.error('Image export failed:', error)
      throw new Error('Failed to export image')
    }
  }

  private generateTicketHTML(ticketData: TicketData, options: ExportOptions): string {
    return `
      <!DOCTYPE html>
      <html>
        <head>
          <style>
            body {
              font-family: 'Inter', Arial, sans-serif;
              margin: 0;
              padding: 20px;
              background: white;
            }
            .ticket {
              border: 2px solid #e5e7eb;
              border-radius: 12px;
              overflow: hidden;
              box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            .header {
              background: linear-gradient(135deg, #3b82f6, #1e40af);
              color: white;
              padding: 24px;
            }
            .header h1 {
              margin: 0;
              font-size: 24px;
              font-weight: bold;
            }
            .header p {
              margin: 4px 0 0 0;
              font-size: 14px;
              opacity: 0.9;
            }
            .booking-ref {
              text-align: right;
              font-family: monospace;
              font-size: 18px;
              font-weight: 600;
            }
            .content {
              padding: 24px;
            }
            .section {
              margin-bottom: 24px;
            }
            .section h3 {
              margin: 0 0 16px 0;
              font-size: 18px;
              font-weight: 600;
              color: #111827;
            }
            .grid {
              display: grid;
              grid-template-columns: 1fr 1fr;
              gap: 24px;
            }
            .field {
              margin-bottom: 16px;
            }
            .field label {
              display: block;
              font-size: 14px;
              font-weight: 500;
              color: #374151;
              margin-bottom: 4px;
            }
            .field value {
              display: block;
              font-size: 16px;
              font-weight: 600;
              color: #111827;
            }
            .flight-details {
              background: #f9fafb;
              border-radius: 8px;
              padding: 16px;
            }
            .route {
              display: flex;
              align-items: center;
              gap: 8px;
              font-size: 24px;
              font-weight: bold;
              color: #3b82f6;
            }
            .footer {
              background: #f3f4f6;
              padding: 16px 24px;
              font-size: 12px;
              color: #6b7280;
              display: flex;
              justify-content: space-between;
            }
            .watermark {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%) rotate(-45deg);
              font-size: 48px;
              color: rgba(0, 0, 0, 0.1);
              font-weight: bold;
              pointer-events: none;
              z-index: 1000;
            }
          </style>
        </head>
        <body>
          <div class="ticket">
            <div class="header">
              <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                  <h1>Pro Ticketer</h1>
                  <p>Professional Travel Document</p>
                </div>
                <div class="booking-ref">
                  <div style="font-size: 12px; opacity: 0.8;">Booking Reference</div>
                  <div>${ticketData.bookingReference || 'ABC123'}</div>
                </div>
              </div>
            </div>
            
            <div class="content">
              <div class="section">
                <div class="grid">
                  <div class="field">
                    <label>Passenger Name</label>
                    <value>${ticketData.passengerName || 'John Doe'}</value>
                  </div>
                  <div class="field">
                    <label>Flight Number</label>
                    <value>${ticketData.flightNumber || 'AA1234'}</value>
                  </div>
                </div>
              </div>
              
              <div class="section">
                <div class="flight-details">
                  <h3>Flight Details</h3>
                  <div class="grid">
                    <div>
                      <label>Route</label>
                      <div class="route">
                        <span>${ticketData.departureAirport || 'LAX'}</span>
                        <span>→</span>
                        <span>${ticketData.arrivalAirport || 'JFK'}</span>
                      </div>
                    </div>
                    <div>
                      <label>Airline</label>
                      <value>${ticketData.airline || 'American Airlines'}</value>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="section">
                <div class="grid">
                  <div class="field">
                    <label>Departure Date & Time</label>
                    <value>${this.formatDate(ticketData.departureDate || '2024-12-15')} at ${this.formatTime(ticketData.departureTime || '14:30')}</value>
                  </div>
                  <div class="field">
                    <label>Arrival Date & Time</label>
                    <value>${this.formatDate(ticketData.arrivalDate || '2024-12-15')} at ${this.formatTime(ticketData.arrivalTime || '18:45')}</value>
                  </div>
                </div>
              </div>
              
              <div class="section">
                <div class="grid">
                  <div class="field">
                    <label>Class</label>
                    <value>${(ticketData.class || 'Economy').charAt(0).toUpperCase() + (ticketData.class || 'Economy').slice(1)}</value>
                  </div>
                  <div class="field">
                    <label>Seat</label>
                    <value>${ticketData.seatNumber || '12A'}</value>
                  </div>
                  <div class="field">
                    <label>Price</label>
                    <value>${ticketData.currency || 'USD'} ${ticketData.price || '299.00'}</value>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="footer">
              <div>Generated by Pro Ticketer</div>
              <div>${new Date().toLocaleDateString()}</div>
            </div>
          </div>
          
          ${options.watermark ? `<div class="watermark">${options.watermark}</div>` : ''}
        </body>
      </html>
    `
  }

  private addWatermark(pdf: jsPDF, watermark: string): void {
    const pageCount = pdf.getNumberOfPages()
    
    for (let i = 1; i <= pageCount; i++) {
      pdf.setPage(i)
      
      // Set watermark properties
      pdf.setFontSize(48)
      pdf.setTextColor(200, 200, 200)
      
      // Calculate center position
      const pageWidth = pdf.internal.pageSize.getWidth()
      const pageHeight = pdf.internal.pageSize.getHeight()
      
      // Rotate and position watermark
      pdf.saveGraphicsState()
      pdf.translate(pageWidth / 2, pageHeight / 2)
      pdf.rotate(-45)
      pdf.text(watermark, 0, 0, { align: 'center' })
      pdf.restoreGraphicsState()
    }
  }

  private addSignature(pdf: jsPDF, signature: string): void {
    const pageCount = pdf.getNumberOfPages()
    const lastPage = pageCount
    
    pdf.setPage(lastPage)
    
    // Add signature at bottom right
    pdf.setFontSize(10)
    pdf.setTextColor(100, 100, 100)
    pdf.text(signature, pdf.internal.pageSize.getWidth() - 60, pdf.internal.pageSize.getHeight() - 20)
  }

  private formatDate(dateString: string): string {
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

  private formatTime(timeString: string): string {
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
}