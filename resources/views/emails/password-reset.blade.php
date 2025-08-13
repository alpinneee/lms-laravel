<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Train4Best</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-height: 60px; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 8px; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/LogoT4B.png') }}" alt="Train4Best" class="logo">
            <h1>Reset Password</h1>
        </div>
        
        <div class="content">
            <p>Halo {{ $user->name }},</p>
            
            <p>Kami menerima permintaan untuk reset password akun Train4Best Anda. Klik tombol di bawah ini untuk membuat password baru:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>
            
            <p>Atau copy dan paste link berikut ke browser Anda:</p>
            <p style="word-break: break-all; background: #eee; padding: 10px; border-radius: 4px;">{{ $resetUrl }}</p>
            
            <p><strong>Link ini akan expired dalam 24 jam.</strong></p>
            
            <p>Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.</p>
            
            <p>Terima kasih,<br>Tim Train4Best</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Train4Best. All rights reserved.</p>
        </div>
    </div>
</body>
</html>