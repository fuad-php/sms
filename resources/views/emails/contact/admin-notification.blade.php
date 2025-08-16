<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #374151; }
        .value { margin-top: 5px; }
        .footer { background: #e5e7eb; padding: 15px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Form Submission</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <p>A new contact form submission has been received:</p>
            
            <div class="field">
                <div class="label">Name:</div>
                <div class="value">{{ $inquiry->name }}</div>
            </div>
            
            <div class="field">
                <div class="label">Email:</div>
                <div class="value">{{ $inquiry->email }}</div>
            </div>
            
            @if($inquiry->phone)
            <div class="field">
                <div class="label">Phone:</div>
                <div class="value">{{ $inquiry->phone }}</div>
            </div>
            @endif
            
            @if($inquiry->department)
            <div class="field">
                <div class="label">Department:</div>
                <div class="value">{{ $inquiry->department }}</div>
            </div>
            @endif
            
            <div class="field">
                <div class="label">Subject:</div>
                <div class="value">{{ $inquiry->subject }}</div>
            </div>
            
            <div class="field">
                <div class="label">Message:</div>
                <div class="value">{{ $inquiry->message }}</div>
            </div>
            
            <div class="field">
                <div class="label">Submitted:</div>
                <div class="value">{{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}</div>
            </div>
            
            <div class="field">
                <div class="label">IP Address:</div>
                <div class="value">{{ $inquiry->ip_address }}</div>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}</p>
            <p>Please respond to the inquiry at your earliest convenience.</p>
        </div>
    </div>
</body>
</html>
