<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #4F46E5;
            font-size: 24px;
        }
        .detail {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .label {
            font-weight: bold;
            color: #4F46E5;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .company-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .highlight {
            background-color: #f8f4ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="company-logo">
            <h2 style="color: #4F46E5;">{{ env('APP_NAME') }}</h2>
        </div>
        
        <div class="highlight">
            <h1>New Contact Form Submission</h1>
            <p>You have received a new inquiry from your website contact form. Please respond within 2 hours.</p>
        </div>
        
        <div class="detail">
            @if(isset($formData['contact_name']))
            <span class="label">Name:</span> {{ $formData['contact_name'] }}
            @else
            <span class="label">Name:</span> {{ $formData['first_name'] }} {{ $formData['last_name'] }}
            @endif
        </div>
        
        <div class="detail">
            <span class="label">Email:</span> {{ $formData['email']??'' }}
        </div>
        
        <div class="detail">
            <span class="label">Phone:</span> {{ $formData['phone']??'' }}
        </div>
        
        <div class="detail">
            <span class="label">Company:</span> {{ $formData['company'] ?? 'Not provided' }}
        </div>
        <div class="detail">
            <span class="label">Inquiry Type:</span> {{ $formData['inquiry_type'] ?? 'Not provided' }}
        </div>
        <div class="detail">
            <span class="label">Business Requirements:</span>
            @if(isset($formData['requirements']))
                <p>{{ $formData['requirements']??'' }}</p>
            @else
                <p>{{ $formData['message']??'' }}</p>
            @endif
        </div>
        
        <div class="footer">
            <p>This email was sent automatically from your website contact form at {{ now()->format('Y-m-d H:i:s') }}.</p>
            <p>© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>