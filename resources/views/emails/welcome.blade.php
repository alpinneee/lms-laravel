<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Train4Best</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            background-color: #373A8D;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 200px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        h1 {
            color: #373A8D;
            margin-top: 0;
        }
        .button {
            display: inline-block;
            background-color: #373A8D;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-white.png') }}" alt="Train4Best Logo">
        </div>
        
        <div class="content">
            <h1>Welcome to Train4Best!</h1>
            
            <p>Hello {{ $name }},</p>
            
            <p>Welcome to Train4Best Training Management System! We're excited to have you join us as a {{ $userType }}.</p>
            
            <p>Our platform provides a comprehensive training experience with access to various courses, certificates, and learning materials designed to enhance your skills and knowledge.</p>
            
            @if($userType == 'admin')
                <p>As an Administrator, you have access to manage users, courses, certificates, and system settings. You can view comprehensive analytics and reports to monitor the performance of the training system.</p>
            @elseif($userType == 'instructor')
                <p>As an Instructor, you can manage your assigned courses, track student progress, issue certificates, and upload course materials for your students.</p>
            @else
                <p>As a Participant, you can browse available courses, register for training sessions, track your learning progress, and download certificates upon completion.</p>
            @endif
            
            <p>To get started, click the button below to access your dashboard:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="button">Go to Dashboard</a>
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team at <a href="mailto:support@train4best.com">support@train4best.com</a>.</p>
            
            <p>Thank you for choosing Train4Best for your training needs!</p>
            
            <p>Best regards,<br>The Train4Best Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Train4Best. All rights reserved.</p>
            <p>This email was sent to {{ $user->email }}</p>
            
            <div class="social-links">
                <a href="https://facebook.com/train4best">Facebook</a> |
                <a href="https://twitter.com/train4best">Twitter</a> |
                <a href="https://linkedin.com/company/train4best">LinkedIn</a>
            </div>
        </div>
    </div>
</body>
</html> 