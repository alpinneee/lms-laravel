<!DOCTYPE html>
<html>
<head>
    <title>Test Email - Train4Best</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .email-content { border: 1px solid #ddd; padding: 20px; margin: 10px 0; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Test Email Reset Password</h1>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="/test-email">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" value="admin@train4best.com" required>
        <button type="submit">Kirim Test Email</button>
    </form>
    
    <h2>Email Content Preview:</h2>
    <div class="email-content">
        @include('emails.password-reset', [
            'user' => (object)['name' => 'Test User', 'email' => 'test@example.com'],
            'resetUrl' => url('/reset-password/test-token?email=test@example.com')
        ])
    </div>
    
    <h2>Log File Content:</h2>
    <pre>{{ $logContent ?? 'No log content available' }}</pre>
</body>
</html>