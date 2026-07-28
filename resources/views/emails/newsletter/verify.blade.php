<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirm Your Subscription</title>
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
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background-color: #7367f0;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
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
            <p>Hello,</p>
            <p>Thank you for signing up for the Michigan Explorer newsletter! To complete your subscription and start receiving our exclusive travel guides, secrets, and premium escape updates, please verify your email address by clicking the button below:</p>
            
            <div class="btn-container">
                <a href="{{ $verificationUrl }}" class="btn">Verify Subscription</a>
            </div>
            
            <p>Or copy and paste this link directly into your browser:</p>
            <p style="word-break: break-all;"><a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a></p>
            
            <hr style="border: 0; border-top: 1px solid #eeeeee; margin: 30px 0;">
            <p style="font-size: 0.85rem; color: #666666;"><em>Note: This verification link will expire in 24 hours. If you did not sign up for this newsletter, please ignore this email.</em></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Michigan Explorer. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
