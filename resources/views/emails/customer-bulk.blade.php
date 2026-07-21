<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        :root {
            --primary-color: #0F8389;
            --secondary-color: #06565A;
            --accent-color: #e74c3c;
            --dark-color: #34495e;
            --light-color: #ecf0f1;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        a {
            color: var(--primary-color);
            text-decoration: none;
        }
        a:hover {
            color: var(--secondary-color);
        }
        .contact-info {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('DOCMEY_LOGO.png') }}" alt="Docmey Logo" style="max-width: 200px; margin-bottom: 15px;">
    </div>
    <div class="content">
        {!! $content !!}
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
        <div class="contact-info">
            <p>
                <a href="tel:+919429895795">{{ env('MOBILE') }}</a> | 
                <a href="mailto:{{ env('ADMIN_EMAIL') }}">{{ env('ADMIN_EMAIL') }}</a> | 
                <a href="mailto:{{ env('SUPPORT_EMAIL') }}">{{ env('SUPPORT_EMAIL') }}</a>
            </p>
        </div>
        <p>
            You are receiving this email because you are a customer of Docmey.
            If you prefer not to receive these emails, you can
            <a href="{{ route('user.unsubscribe', ['email' => $customer->email, 'token' => $customer->unsubscribe_token]) }}">unsubscribe</a>.
        </p>
        <p>
            {{ env('APP_NAME') }}, {{ env('ADDRESS') }}
        </p>
    </div>
</body>
</html>