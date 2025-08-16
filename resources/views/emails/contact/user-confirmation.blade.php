<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank you for contacting us</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; }
        .message { background: white; padding: 15px; border-left: 4px solid #3b82f6; margin: 15px 0; }
        .footer { background: #e5e7eb; padding: 15px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank you for contacting us!</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $inquiry->name }},</p>
            
            <p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p>
            
            <div class="message">
                <h3>Your Message Details:</h3>
                <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
                <p><strong>Message:</strong></p>
                <p>{{ $inquiry->message }}</p>
                <p><strong>Submitted:</strong> {{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            
            <p>We typically respond to inquiries within 24-48 hours during business days. If you have an urgent matter, please don't hesitate to call us directly.</p>
            
            <p>If you have any additional questions or need immediate assistance, please feel free to contact us:</p>
            
            <ul>
                <li><strong>Phone:</strong> {{ \App\Helpers\SettingsHelper::getSchoolPhone() ?: 'Available on our website' }}</li>
                <li><strong>Email:</strong> {{ \App\Helpers\SettingsHelper::getSchoolEmail() ?: 'Available on our website' }}</li>
                <li><strong>Website:</strong> <a href="{{ url('/') }}">{{ url('/') }}</a></li>
            </ul>
            
            <p>Thank you for choosing {{ config('app.name') }}!</p>
            
            <p>Best regards,<br>
            The {{ config('app.name') }} Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated confirmation from {{ config('app.name') }}</p>
            <p>Please do not reply to this email. If you need to contact us, please use the contact information above.</p>
        </div>
    </div>
</body>
</html>
