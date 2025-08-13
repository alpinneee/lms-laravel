<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - Train4Best</title>
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
        .payment-details {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .payment-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-details table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .payment-details table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .payment-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-verified {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-white.png') }}" alt="Train4Best Logo">
        </div>
        
        <div class="content">
            <h1>Payment {{ ucfirst($status) }}</h1>
            
            <p>Hello {{ $name }},</p>
            
            @if($status == 'verified')
                <p>Your payment for <strong>{{ $course->course_name }}</strong> has been verified and confirmed. Thank you for your payment!</p>
            @elseif($status == 'pending')
                <p>We have received your payment submission for <strong>{{ $course->course_name }}</strong>. Our team will verify your payment shortly.</p>
            @else
                <p>Unfortunately, your payment for <strong>{{ $course->course_name }}</strong> has been rejected. Please check the payment details and submit a new payment.</p>
            @endif
            
            <div class="payment-details">
                <h3>Payment Details</h3>
                <table>
                    <tr>
                        <td>Course:</td>
                        <td>{{ $course->course_name }}</td>
                    </tr>
                    <tr>
                        <td>Schedule:</td>
                        <td>{{ $class->start_date->format('d M Y') }} - {{ $class->end_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td>${{ $amount }}</td>
                    </tr>
                    <tr>
                        <td>Payment Date:</td>
                        <td>{{ $date }}</td>
                    </tr>
                    <tr>
                        <td>Reference Number:</td>
                        <td>{{ $reference }}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>
                            <span class="payment-status status-{{ $status }}">{{ ucfirst($status) }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            
            @if($status == 'verified')
                <p>You are now fully enrolled in the course. You can access your course materials and information from your dashboard.</p>
                <div style="text-align: center;">
                    <a href="{{ url('/participant/courses') }}" class="button">View My Courses</a>
                </div>
            @elseif($status == 'pending')
                <p>We will notify you once your payment has been verified. This process typically takes 1-2 business days.</p>
                <div style="text-align: center;">
                    <a href="{{ url('/participant/payment') }}" class="button">View Payment Status</a>
                </div>
            @else
                <p>Reason for rejection: {{ $payment->notes ?? 'Payment details could not be verified' }}</p>
                <div style="text-align: center;">
                    <a href="{{ url('/participant/payment/upload') }}" class="button">Submit New Payment</a>
                </div>
            @endif
            
            <p>If you have any questions or need assistance, please contact our support team at <a href="mailto:support@train4best.com">support@train4best.com</a>.</p>
            
            <p>Thank you for choosing Train4Best for your training needs!</p>
            
            <p>Best regards,<br>The Train4Best Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Train4Best. All rights reserved.</p>
            <p>This email was sent regarding your course registration.</p>
        </div>
    </div>
</body>
</html> 