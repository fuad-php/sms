# Public Announcement System

## Overview

The Public Announcement System allows schools to display important announcements to the public without requiring authentication. This system provides a modern, responsive interface for viewing school announcements.

## Features

### 1. Public Access
- **No Authentication Required**: Anyone can view public announcements without logging in
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Modern UI**: Clean, professional interface using Tailwind CSS

### 2. Announcement Display
- **Priority-based Sorting**: Announcements are sorted by priority (urgent, high, medium, low)
- **Rich Content**: Support for formatted text content
- **File Attachments**: Download attachments (PDF, DOC, images)
- **Expiration Dates**: Shows time remaining for time-sensitive announcements
- **New Badges**: Highlights announcements created within the last 7 days

### 3. Search and Filtering
- **Text Search**: Search announcements by title or content
- **Priority Filter**: Filter announcements by priority level
- **Clear Filters**: Easy reset of search criteria

### 4. Content Management
- **Show More/Less**: Expandable content for long announcements
- **Truncated Preview**: Shows first 150 characters with option to expand
- **Full Content View**: Complete announcement details on dedicated page

## Routes

### Public Routes
- `GET /announcement` - Public announcements page
- `GET /announcements/{id}` - Individual announcement view
- `GET /announcements/{id}/download` - Download attachment

### Admin Routes (Authenticated)
- `GET /announcements` - Admin announcements list
- `GET /announcements/create` - Create new announcement
- `POST /announcements` - Store new announcement
- `GET /announcements/{id}/edit` - Edit announcement
- `PUT /announcements/{id}` - Update announcement
- `DELETE /announcements/{id}` - Delete announcement
- `PATCH /announcements/{id}/toggle-publish` - Toggle publish status

## Database Schema

```sql
announcements table:
- id (primary key)
- title (string)
- content (text)
- created_by (foreign key to users)
- target_audience (enum: all, students, teachers, parents, staff)
- class_id (nullable, foreign key to classes)
- priority (enum: low, medium, high, urgent)
- publish_date (datetime, nullable)
- expire_date (datetime, nullable)
- is_published (boolean)
- attachment (string, nullable)
- created_at, updated_at (timestamps)
```

## Usage

### For Administrators/Teachers

1. **Create Announcements**:
   - Navigate to `/announcements/create`
   - Fill in title, content, target audience, priority
   - Set publish/expire dates if needed
   - Upload attachments if required
   - Save the announcement

2. **Manage Announcements**:
   - View all announcements at `/announcements`
   - Edit existing announcements
   - Toggle publish status
   - Delete announcements

### For Public Users

1. **View Announcements**:
   - Visit `/announcement` to see all public announcements
   - Use search and filters to find specific announcements
   - Click "Show More" to expand content
   - Click "View Details" for full announcement page

2. **Download Attachments**:
   - Click the attachment icon to download files
   - Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG

## Priority Levels

- **🚨 Urgent**: Critical information requiring immediate attention
- **⚠️ High**: Important announcements with time sensitivity
- **📢 Medium**: General announcements and updates
- **ℹ️ Low**: Informational content

## Target Audiences

- **All**: Visible to everyone (public announcements)
- **Students**: Only visible to students and their parents
- **Teachers**: Only visible to teachers and administrators
- **Parents**: Only visible to parents and administrators
- **Staff**: Only visible to staff and administrators

## Integration

### Home Page Integration
The home page displays the latest 3 public announcements with priority badges and "NEW" indicators for recent posts.

### Navigation
- Public navigation includes link to announcements
- Authenticated users see announcements in main navigation
- Mobile-responsive navigation menu

## Security

- Public announcements are filtered to only show `target_audience = 'all'`
- Only published and active announcements are displayed
- File uploads are validated for type and size
- XSS protection through proper content escaping

## Customization

### Styling
- Uses Tailwind CSS for consistent styling
- Priority badges have color-coded styling
- Responsive grid layout for different screen sizes

### Content
- Rich text content support
- Line clamping for consistent card heights
- Expandable content for better UX

## Future Enhancements

1. **Email Notifications**: Send announcements via email
2. **RSS Feed**: Subscribe to announcements
3. **Social Media Integration**: Share announcements
4. **Announcement Categories**: Organize by topic
5. **Multilingual Support**: Multiple language announcements
6. **Analytics**: Track announcement views and engagement

## Troubleshooting

### Common Issues

1. **Announcements not showing**:
   - Check if `is_published` is true
   - Verify `target_audience` is set to 'all'
   - Ensure publish/expire dates are correct

2. **Attachments not downloading**:
   - Check file permissions in storage/app/public/announcements/
   - Verify file exists in the correct location
   - Run `php artisan storage:link` if needed

3. **Search not working**:
   - Check database indexes on title and content columns
   - Verify search query is properly escaped

### Maintenance

- Regularly clean up expired announcements
- Monitor storage space for attachments
- Backup announcement data regularly
- Update file upload limits as needed
