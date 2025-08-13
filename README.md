# Train4Best - Laravel Training Management System

![Train4Best Logo](public/images/logo.png)

## 🚀 Overview

Train4Best adalah Training Management System yang dikembangkan menggunakan Laravel 12. Sistem ini memungkinkan pengelolaan pelatihan dengan tiga tipe pengguna: Admin, Instructor, dan Participant.

## ✨ Features

- **User Management**: Admin, Instructor, dan Participant
- **Course Management**: Pembuatan kursus, jadwal, dan materi
- **Registration System**: Pendaftaran peserta dan verifikasi
- **Payment System**: Upload bukti pembayaran dan verifikasi
- **Certificate System**: Pembuatan dan verifikasi sertifikat
- **Dashboard Analytics**: Statistik dan laporan untuk setiap tipe pengguna
- **Responsive Design**: Mobile-friendly dengan Tailwind CSS

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL
- **Frontend**: Blade Templates + Tailwind CSS 4.0
- **Authentication**: Custom Auth (tanpa Breeze/Jetstream)
- **PDF Generation**: DomPDF
- **Email**: Laravel Mail

## 📋 Requirements

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM

## 🔧 Installation

### Langkah 1: Clone Repository

```bash
git clone https://github.com/alpinneee/train4best-laravel.git
cd train4best-laravel
```

### Langkah 2: Install Dependencies

```bash
composer install
npm install
```

### Langkah 3: Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### Langkah 4: Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database Anda:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=train4best
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 5: Migrasi dan Seeding Database

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan membuat struktur database dan mengisi data awal termasuk user demo.

### Langkah 6: Buat Symbolic Link untuk Storage

```bash
php artisan storage:link
```

Ini diperlukan untuk akses file upload seperti gambar dan dokumen.

### Langkah 7: Build Assets

```bash
npm run build
```

### Langkah 8: Jalankan Server Development

#### Menggunakan Artisan Server

```bash
php artisan serve
```

Aplikasi akan berjalan di [http://localhost:8000](http://localhost:8000)

#### Menggunakan Laragon

Jika menggunakan Laragon:

1. Letakkan project di folder `C:\laragon\www\train4best-laravel`
2. Buka Laragon dan klik "Start All"
3. Akses aplikasi di [http://train4best-laravel.test](http://train4best-laravel.test)

#### Menggunakan XAMPP

Jika menggunakan XAMPP:

1. Letakkan project di folder `C:\xampp\htdocs\train4best-laravel`
2. Pastikan Apache dan MySQL sudah running
3. Akses aplikasi di [http://localhost/train4best-laravel/public](http://localhost/train4best-laravel/public)

### Langkah 9: Troubleshooting

Jika mengalami masalah, coba perintah berikut:

```bash
# Bersihkan cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Jika ada masalah dengan autoload
composer dump-autoload
```

## 👥 Akun Demo

| Role       | Email                      | Password |
|------------|----------------------------|----------|
| Admin      | admin@train4best.com       | password |
| Instructor | instructor@train4best.com  | password |
| Participant| participant@train4best.com | password |

## 📁 Directory Structure

```
train4best-laravel/
├── app/                    # Application code
│   ├── Http/               # Controllers, Middleware, Requests
│   ├── Models/             # Eloquent models
│   └── Mail/               # Email templates
├── database/               # Migrations and seeders
├── public/                 # Publicly accessible files
├── resources/              # Views, CSS, JS
│   ├── views/              # Blade templates
│   │   ├── admin/          # Admin views
│   │   ├── instructor/     # Instructor views
│   │   ├── participant/    # Participant views
│   │   ├── components/     # Reusable components
│   │   └── layouts/        # Layout templates
│   ├── css/                # CSS files
│   └── js/                 # JavaScript files
└── routes/                 # Route definitions
```

## 🔄 Workflow

### Admin Workflow

1. Login → Admin Dashboard
2. Manage Users, Courses, dan Certificates
3. View Reports dan Analytics
4. Verify Payments dan Registrations

### Instructor Workflow

1. Login → Instructor Dashboard
2. Manage Assigned Courses
3. Track Student Progress
4. Issue Certificates

### Participant Workflow

1. Login → Participant Dashboard
2. Browse & Register for Courses
3. Upload Payment Proof
4. Download Certificates

## 🔒 Authentication

Sistem menggunakan custom authentication dengan role-based access control:

- `auth` middleware untuk semua authenticated users
- `role:admin` middleware untuk Admin
- `role:instructor` middleware untuk Instructor
- `role:participant` middleware untuk Participant

## 📊 Database Schema

Sistem memiliki 18 tabel utama:

1. `users` - User accounts
2. `user_types` - User roles
3. `course_types` - Course categories
4. `courses` - Course information
5. `classes` - Course schedules
6. `instructures` - Instructor profiles
7. `participants` - Participant profiles
8. `instructure_classes` - Instructor assignments
9. `course_registrations` - Course enrollments
10. `payments` - Payment records
11. `bank_accounts` - Payment destination accounts
12. `certificates` - Issued certificates
13. `certifications` - Certificate records
14. `value_reports` - Assessment records
15. `course_materials` - Course resources
16. `attendances` - Attendance records
17. `system_settings` - Application settings
18. `password_reset_tokens` - Password reset records

## 📱 Responsive Design

Aplikasi didesain dengan pendekatan mobile-first menggunakan Tailwind CSS:

- **Mobile**: Optimized for screens < 640px
- **Tablet**: Optimized for screens 640px - 1024px
- **Desktop**: Full-featured interface for screens > 1024px

## 📧 Email Notifications

Sistem mengirimkan email untuk:

- Welcome Email (saat registrasi)
- Registration Confirmation
- Payment Verification
- Certificate Issuance
- Password Reset

## 📄 Certificate Generation

Sertifikat dihasilkan sebagai PDF dengan:

- Unique certificate number
- QR code for verification
- Expiry date tracking
- Digital signature support

## 📝 License

Copyright © 2025 Train4Best. All rights reserved.