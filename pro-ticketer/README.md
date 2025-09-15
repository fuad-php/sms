# Pro Ticketer

A comprehensive web-based application that standardizes ticket copies from multiple sources into a single, branded format. Built with Next.js, TypeScript, and modern web technologies.

## 🚀 Features

### Core Functionality
- **Multi-Format Upload**: Support for PDF, images (PNG, JPG, JPEG), and Word documents
- **Smart OCR Processing**: Automatically extract ticket details using Tesseract.js
- **Professional Branding**: Transform any ticket into your branded format
- **Online Editor**: Review and correct extracted data with an intuitive interface
- **Export & Share**: Export as PDF or share via email with watermarks

### System Features
- **Secure Authentication**: Role-based access control with NextAuth.js
- **Upload History**: Track all file uploads and processing status
- **Cloud Integration**: Connect with Google Drive and OneDrive
- **Mobile Responsive**: Optimized for all device sizes
- **Real-time Updates**: Live progress tracking and status updates

### Advanced Features
- **Bulk Upload**: Process multiple tickets simultaneously
- **API Integration**: RESTful API for external system integration
- **Data Validation**: Comprehensive validation and error handling
- **Analytics Dashboard**: Track usage and manage your ticket library

## 🛠️ Tech Stack

- **Frontend**: Next.js 14, React 18, TypeScript
- **Styling**: Tailwind CSS, Framer Motion
- **Authentication**: NextAuth.js
- **Database**: PostgreSQL with Prisma ORM
- **OCR**: Tesseract.js
- **File Processing**: PDF-parse, Mammoth (Word docs)
- **Export**: jsPDF, HTML2Canvas
- **Cloud Storage**: Google Drive API, OneDrive API

## 📦 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd pro-ticketer
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Set up environment variables**
   ```bash
   cp .env.example .env.local
   ```
   
   Fill in the required environment variables:
   - `DATABASE_URL`: PostgreSQL connection string
   - `NEXTAUTH_SECRET`: Secret key for NextAuth.js
   - `NEXTAUTH_URL`: Your application URL
   - Cloud storage credentials (optional)

4. **Set up the database**
   ```bash
   npx prisma generate
   npx prisma db push
   ```

5. **Run the development server**
   ```bash
   npm run dev
   ```

6. **Open your browser**
   Navigate to [http://localhost:3000](http://localhost:3000)

## 🗄️ Database Schema

The application uses PostgreSQL with the following main entities:

- **Users**: Authentication and user management
- **Tickets**: Ticket data and processing status
- **UploadHistory**: File upload tracking
- **CloudStorageConfig**: Cloud storage integrations

## 🔧 Configuration

### Environment Variables

| Variable | Description | Required |
|----------|-------------|----------|
| `DATABASE_URL` | PostgreSQL connection string | Yes |
| `NEXTAUTH_SECRET` | Secret key for NextAuth.js | Yes |
| `NEXTAUTH_URL` | Application URL | Yes |
| `GOOGLE_DRIVE_CLIENT_ID` | Google Drive API client ID | No |
| `GOOGLE_DRIVE_CLIENT_SECRET` | Google Drive API client secret | No |
| `ONEDRIVE_CLIENT_ID` | OneDrive API client ID | No |
| `ONEDRIVE_CLIENT_SECRET` | OneDrive API client secret | No |
| `SMTP_HOST` | SMTP server for email notifications | No |
| `SMTP_USER` | SMTP username | No |
| `SMTP_PASS` | SMTP password | No |

### File Upload Limits

- Maximum file size: 10MB
- Supported formats: PDF, PNG, JPG, JPEG, GIF, BMP, TIFF, DOC, DOCX
- Batch upload limit: 50 files per request

## 📱 API Endpoints

### Authentication
- `POST /api/auth/signup` - User registration
- `POST /api/auth/signin` - User login
- `GET /api/auth/[...nextauth]` - NextAuth.js endpoints

### File Management
- `POST /api/upload` - Upload single file
- `POST /api/bulk-upload` - Upload multiple files
- `GET /api/tickets` - Get user tickets
- `POST /api/tickets` - Update ticket data

### Export & Sharing
- `POST /api/export` - Export ticket as PDF/image
- `POST /api/email` - Send ticket via email

### Cloud Storage
- `POST /api/cloud-storage/connect` - Connect cloud storage
- `GET /api/cloud-storage/files` - List cloud files

## 🎨 Customization

### Branding
The ticket template can be customized by modifying:
- `src/components/ticket-template.tsx` - Main template component
- `src/lib/export.ts` - Export styling and layout
- `tailwind.config.ts` - Color scheme and design tokens

### OCR Processing
OCR extraction patterns can be customized in:
- `src/lib/ocr.ts` - Text extraction logic
- Add new patterns for different ticket formats

## 🚀 Deployment

### Vercel (Recommended)
1. Push your code to GitHub
2. Connect your repository to Vercel
3. Set environment variables in Vercel dashboard
4. Deploy automatically

### Docker
```bash
# Build the image
docker build -t pro-ticketer .

# Run the container
docker run -p 3000:3000 pro-ticketer
```

### Manual Deployment
1. Build the application: `npm run build`
2. Start the production server: `npm start`
3. Set up reverse proxy (nginx/Apache)
4. Configure SSL certificate

## 🔒 Security

- Password hashing with bcryptjs
- JWT-based session management
- File type validation
- File size limits
- SQL injection protection with Prisma
- XSS protection with Next.js
- CSRF protection with NextAuth.js

## 📊 Performance

- Image optimization with Next.js Image component
- Lazy loading for large file lists
- Efficient database queries with Prisma
- Client-side caching with React Query
- CDN-ready static assets

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -am 'Add feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Contact the development team

## 🔮 Roadmap

- [ ] Advanced OCR with machine learning
- [ ] Multi-language support
- [ ] Advanced analytics and reporting
- [ ] Mobile app (React Native)
- [ ] API rate limiting and monitoring
- [ ] Advanced cloud storage features
- [ ] Team collaboration features
- [ ] Custom template builder

---

Built with ❤️ by the Pro Ticketer Team