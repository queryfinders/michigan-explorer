<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Inquiry</title>
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
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th, .details-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eeeeee;
        }
        .details-table th {
            color: #666666;
            width: 30%;
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
            <p>Hello Admin,</p>
            <p>You have received a new contact inquiry from the website. Here are the details:</p>
            
            <table class="details-table">
                <tr>
                    <th>Name:</th>
                    <td>{{ $contactMessage->full_name }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $contactMessage->email }}</td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td>{{ $contactMessage->phone ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Subject:</th>
                    <td>{{ $contactMessage->subject }}</td>
                </tr>
                <tr>
                    <th>Message:</th>
                    <td>{{ $contactMessage->message }}</td>
                </tr>
                <tr>
                    <th>Submitted Date:</th>
                    <td>{{ $contactMessage->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                <tr>
                    <th>IP Address:</th>
                    <td>{{ $contactMessage->ip_address }}</td>
                </tr>
            </table>
            
            <div class="btn-container">
                <a href="{{ $viewUrl }}" class="btn">View in Admin Panel</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Michigan Explorer. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
