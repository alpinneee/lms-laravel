# Setup Email untuk Reset Password

## Konfigurasi Gmail SMTP

1. **Buka Google Account Settings**
   - Pergi ke https://myaccount.google.com/
   - Pilih "Security" di sidebar kiri

2. **Aktifkan 2-Step Verification**
   - Jika belum aktif, aktifkan 2-Step Verification terlebih dahulu

3. **Generate App Password**
   - Di bagian "2-Step Verification", klik "App passwords"
   - Pilih "Mail" dan "Other (custom name)"
   - Masukkan nama: "Train4Best Laravel"
   - Copy password yang dihasilkan (16 karakter)

4. **Update file .env**
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-16-digit-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@train4best.com"
   MAIL_FROM_NAME="Train4Best"
   ```

5. **Clear Config Cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Alternatif: Menggunakan Mailtrap (untuk testing)

1. **Daftar di Mailtrap.io**
   - Buat akun gratis di https://mailtrap.io/

2. **Update .env dengan kredensial Mailtrap**
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@train4best.com"
   MAIL_FROM_NAME="Train4Best"
   ```

## Test Email

Setelah konfigurasi, test dengan:
1. Buka halaman forgot password: `/forgot-password`
2. Masukkan email yang terdaftar
3. Cek inbox email atau Mailtrap untuk melihat email reset password