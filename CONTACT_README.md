# ContactUs System - School Management System

## Overview

The ContactUs system provides a complete solution for managing contact form submissions in the school management system. It includes public contact forms, admin management interface, email notifications, and comprehensive filtering and search capabilities.

## Features

### Public Contact Form
- **Accessible to all visitors** without requiring login
- **Responsive design** with Tailwind CSS
- **Form validation** with Laravel validation rules
- **Department selection** for proper routing
- **IP address and user agent tracking** for security
- **Success/error message handling**

### Admin Management Interface
- **Dashboard with statistics** (total, unread, today, this week)
- **Comprehensive filtering** by status, department, and search
- **Real-time statistics** with auto-refresh every 30 seconds
- **Detailed inquiry view** with all information
- **Bulk actions** (mark as read/unread, delete)
- **Export functionality** (CSV format)
- **Responsive table design**

### Email Notifications
- **Admin notification emails** for new submissions
- **User confirmation emails** with submission details
- **Professional email templates** with school branding
- **Error handling** to prevent form submission failures

### Security Features
- **CSRF protection** on all forms
- **Input validation** and sanitization
- **IP address logging** for tracking
- **User agent logging** for device identification
- **Admin-only access** to management features

## File Structure

```
app/
├── Http/Controllers/
│   └── ContactController.php          # Main controller for contact functionality
├── Mail/
│   ├── ContactFormSubmission.php      # Admin notification email
│   └── ContactFormConfirmation.php    # User confirmation email
└── Models/
    └── ContactInquiry.php             # Contact inquiry model

resources/views/
├── contact/
│   └── index.blade.php                # Public contact form
├── admin/contact/
│   ├── index.blade.php                # Admin contact list
│   └── show.blade.php                 # Admin contact detail view
└── emails/contact/
    ├── admin-notification.blade.php   # Admin email template
    └── user-confirmation.blade.php    # User email template

database/
├── migrations/
│   └── 2025_08_16_113816_create_contact_inquiries_table.php
└── seeders/
    └── ContactInquirySeeder.php       # Sample data seeder

routes/
└── web.php                            # Contact routes definition
```

## Database Schema

### contact_inquiries Table
```sql
CREATE TABLE contact_inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    department VARCHAR(100) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Routes

### Public Routes
- `GET /contact` - Display contact form
- `POST /contact` - Submit contact form

### Admin Routes (Admin only)
- `GET /admin/contact` - List all contact inquiries
- `GET /admin/contact/{inquiry}` - View inquiry details
- `PATCH /admin/contact/{inquiry}/toggle-read` - Toggle read status
- `DELETE /admin/contact/{inquiry}` - Delete inquiry
- `GET /admin/contact/stats` - Get statistics (JSON)
- `GET /admin/contact/export` - Export inquiries (CSV)

## Usage

### For Visitors
1. Navigate to `/contact`
2. Fill out the contact form with required information
3. Select appropriate department if applicable
4. Submit the form
5. Receive confirmation email

### For Administrators
1. Access `/admin/contact` (requires admin role)
2. View dashboard with real-time statistics
3. Filter inquiries by status, department, or search terms
4. Click on inquiry to view full details
5. Mark as read/unread or delete as needed
6. Export data for reporting purposes

## Configuration

### Email Settings
The system uses Laravel's mail configuration. Ensure these environment variables are set:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourschool.com
MAIL_FROM_NAME="Your School Name"
```

### Admin Email
Set the admin email for notifications:

```env
ADMIN_EMAIL=admin@yourschool.com
```

## Localization

The system supports multiple languages with the following keys:

### English (lang/en/app.php)
- `contact_management` - Contact Management
- `manage_contact_inquiries` - Manage contact form inquiries
- `total_inquiries` - Total Inquiries
- `unread_inquiries` - Unread Inquiries
- And many more...

### Bengali (lang/bn/app.php)
- `contact_management` - যোগাযোগ ব্যবস্থাপনা
- `manage_contact_inquiries` - যোগাযোগ ফর্মের জিজ্ঞাসা ব্যবস্থাপনা
- `total_inquiries` - মোট জিজ্ঞাসা
- `unread_inquiries` - অপঠিত জিজ্ঞাসা
- And many more...

## Testing

### Sample Data
Run the seeder to populate sample contact inquiries:

```bash
php artisan db:seed --class=ContactInquirySeeder
```

### Manual Testing
1. Submit a contact form as a visitor
2. Check admin interface for new inquiry
3. Test filtering and search functionality
4. Verify email notifications (if mail is configured)

## Customization

### Adding New Departments
1. Update the department options in the contact form view
2. Add corresponding localization keys
3. Update the admin filter options

### Modifying Email Templates
1. Edit the Blade templates in `resources/views/emails/contact/`
2. Customize styling and content
3. Update email subjects in the Mail classes

### Adding New Fields
1. Create a new migration to add fields to the table
2. Update the ContactInquiry model's fillable array
3. Modify the contact form view
4. Update the admin views
5. Add corresponding localization keys

## Troubleshooting

### Common Issues

#### Emails Not Sending
- Check mail configuration in `.env`
- Verify SMTP credentials
- Check mail logs in `storage/logs/laravel.log`

#### Form Submission Errors
- Ensure all required fields are filled
- Check CSRF token is present
- Verify database connection

#### Admin Access Issues
- Confirm user has admin role
- Check middleware configuration
- Verify route permissions

### Debug Mode
Enable debug mode in `.env` to see detailed error messages:

```env
APP_DEBUG=true
APP_ENV=local
```

## Security Considerations

- **Input Validation**: All form inputs are validated and sanitized
- **CSRF Protection**: Forms include CSRF tokens
- **Role-Based Access**: Admin features require admin role
- **SQL Injection Protection**: Uses Eloquent ORM with prepared statements
- **XSS Protection**: Output is properly escaped in Blade templates

## Performance

- **Database Indexing**: Consider adding indexes for frequently queried fields
- **Pagination**: Admin list is paginated for large datasets
- **Caching**: Statistics are cached and refreshed every 30 seconds
- **Eager Loading**: Relationships are loaded efficiently

## Future Enhancements

- **File Attachments**: Allow users to upload files with inquiries
- **Priority Levels**: Add priority system for urgent inquiries
- **Response Tracking**: Track admin responses to inquiries
- **Automated Responses**: Set up automated responses for common questions
- **Integration**: Connect with help desk or ticketing systems
- **Analytics**: Advanced reporting and analytics dashboard

## Support

For technical support or questions about the ContactUs system, please refer to the main system documentation or contact the development team.
