<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inquiry Received</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333333;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .header {
            background-color: #7367f0;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h2 {
            margin: 0;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 0.8rem;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Michigan Explorer</h2>
        </div>
        <div class="content">
            <p>Hi {{ $contactMessage->full_name }},</p>
            <p>Thank you for contacting Michigan Explorer.</p>
            <p>We have successfully received your inquiry regarding <strong>"{{ $contactMessage->subject }}"</strong>.</p>
            <p>Our support team will review your message and reply within 24 hours.</p>
            <p>Thank you for choosing Michigan Explorer.</p>
            <br>
            <p>Michigan Explorer Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Michigan Explorer. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
