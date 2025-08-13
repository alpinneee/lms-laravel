<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .certificate {
            width: 100%;
            height: 100%;
            padding: 30px;
            box-sizing: border-box;
            position: relative;
            background: url('{{ public_path('images/certificate-bg.jpg') }}') no-repeat center center;
            background-size: cover;
        }
        .border {
            border: 15px solid #373A8D;
            height: calc(100% - 30px);
            width: calc(100% - 30px);
            padding: 40px;
            box-sizing: border-box;
            position: relative;
        }
        .content {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .logo {
            position: absolute;
            top: 40px;
            left: 40px;
            width: 120px;
        }
        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #373A8D;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .certificate-subtitle {
            font-size: 24px;
            margin-bottom: 40px;
            color: #555;
        }
        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #373A8D;
            padding-bottom: 10px;
        }
        .certificate-text {
            font-size: 18px;
            margin-bottom: 40px;
            max-width: 600px;
            line-height: 1.6;
        }
        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #373A8D;
            margin-bottom: 40px;
        }
        .date-instructor {
            display: flex;
            justify-content: space-between;
            width: 90%;
            margin-top: 60px;
        }
        .date, .instructor {
            text-align: center;
            width: 40%;
        }
        .signature-line {
            width: 200px;
            border-bottom: 1px solid #333;
            margin: 0 auto 10px;
        }
        .date-text, .instructor-name {
            font-size: 16px;
            font-weight: bold;
        }
        .small-text {
            font-size: 14px;
            color: #777;
        }
        .certificate-footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
        .certificate-number {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        .qr-code {
            position: absolute;
            bottom: 40px;
            right: 40px;
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border">
            <img src="{{ public_path('images/logo.png') }}" alt="Train4Best Logo" class="logo">
            
            <div class="content">
                <h1 class="certificate-title">Certificate of Completion</h1>
                <p class="certificate-subtitle">This is to certify that</p>
                
                <h2 class="recipient-name">{{ $certificate->name }}</h2>
                
                <p class="certificate-text">
                    has successfully completed the training course and demonstrated proficiency in all required areas of
                </p>
                
                <h3 class="course-name">{{ $course->course_name }}</h3>
                
                <div class="date-instructor">
                    <div class="date">
                        <div class="signature-line"></div>
                        <p class="date-text">{{ $certificate->issue_date->format('d F Y') }}</p>
                        <p class="small-text">Issue Date</p>
                    </div>
                    
                    <div class="instructor">
                        <div class="signature-line"></div>
                        <p class="instructor-name">{{ $instructure->full_name }}</p>
                        <p class="small-text">Instructor</p>
                    </div>
                </div>
                
                <div class="certificate-footer">
                    <p>Verify this certificate at: <strong>https://train4best.com/verify</strong></p>
                    <p class="certificate-number">Certificate Number: {{ $certificate->certificate_number }}</p>
                    <p class="certificate-number">Valid until: {{ $certificate->expiry_date->format('d F Y') }}</p>
                </div>
                
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->generate(route('certificates.verify', ['certificate_number' => $certificate->certificate_number]))) }}" 
                     alt="Verification QR Code" class="qr-code">
            </div>
        </div>
    </div>
</body>
</html> 