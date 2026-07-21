<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Email with document attachments">
    <title>{{ $subjects }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border-top: 5px solid #4a6ee0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .email-header h1 {
            color: #4a6ee0;
            margin: 0;
            font-weight: 600;
            font-size: 24px;
        }
        .email-body {
            padding: 20px 0;
        }
        .default-message {
            font-size: 16px;
            color: #555;
            font-style: italic;
        }
        .document-description {
            margin: 15px 0;
            padding: 10px;
            background-color: #f0f4ff;
            border-left: 4px solid #4a6ee0;
            border-radius: 4px;
        }
        .description-text {
            margin: 0;
            color: #444;
        }
        .attachments-section {
            background-color: #f7f9fc;
            border-radius: 6px;
            padding: 15px;
            margin-top: 25px;
        }
        .attachment-description {
            margin-top: 0;
            color: #666;
            font-size: 15px;
        }
        .attachments-section h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #4a6ee0;
            font-size: 18px;
            font-weight: 600;
        }
        .attachments-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .attachment-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .attachment-item:last-child {
            border-bottom: none;
        }
        .attachment-icon {
            margin-right: 10px;
            color: #7c8495;
        }
        .attachment-name {
            font-size: 15px;
            color: #555;
        }
        .email-footer {
            padding-top: 20px;
            text-align: center;
            color: #888;
            font-size: 14px;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }
        .footer-notes {
            font-size: 13px;
            font-style: italic;
        }
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
                padding: 15px !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $subjects }}</h1>
        </div>
        
        <div class="email-body">
            @if(empty($messageBody))
                <p class="default-message">This message contains document attachments shared with you. Please see the attached files below for more information.</p>
            @else
                {!! $messageBody !!}
            @endif
            
            <div class="document-description">
                <p class="description-text">{{ 'These documents require your attention. Please review the attachments carefully.' }}</p>
            </div>
            
            @if(count($originalNames) > 0)
                <div class="attachments-section">
                    <h4>Attachments ({{ count($originalNames) }})</h4>
                    <p class="attachment-description">The following files have been shared with you:</p>
                    <ul class="attachments-list">
                        @foreach($originalNames as $name)
                            <li class="attachment-item">
                                <span class="attachment-icon">📎</span>
                                <span class="attachment-name">{{ $name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        
        <div class="email-footer">
            <p>Thank you for your attention to this message.</p>
            <p class="footer-notes">{{ 'If you have any questions regarding these documents, please reply to this email.' }}</p>
        </div>
    </div>
</body>
</html>