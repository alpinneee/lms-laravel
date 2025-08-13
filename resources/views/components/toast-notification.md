# Toast Notification Component

Komponen notifikasi toast untuk menampilkan pesan sukses, error, warning, dan info.

## Cara Penggunaan

### 1. Include komponen pada layout

```blade
@include('components.toast-notification')
```

### 2. Menampilkan notifikasi dari JavaScript

```javascript
// Menampilkan notifikasi sukses
showToast('Operasi berhasil!', 'success');

// Menampilkan notifikasi error
showToast('Terjadi kesalahan!', 'error');

// Menampilkan notifikasi warning
showToast('Perhatian!', 'warning');

// Menampilkan notifikasi info
showToast('Informasi penting', 'info');
```

### 3. Menampilkan notifikasi dari Controller Laravel

```php
// Menampilkan notifikasi sukses
return redirect()->back()->with('success', 'Data berhasil disimpan!');

// Menampilkan notifikasi error
return redirect()->back()->with('error', 'Gagal menyimpan data!');

// Menampilkan notifikasi warning
return redirect()->back()->with('warning', 'Perhatian!');

// Menampilkan notifikasi info
return redirect()->back()->with('info', 'Informasi penting');
```

## Kustomisasi

Komponen ini menggunakan Tailwind CSS untuk styling. Anda dapat mengubah tampilan dengan mengedit file:

- `resources/views/components/toast-notification.blade.php` - Template Blade
- `resources/js/toast-notification.js` - Fungsi JavaScript
- `resources/css/app.css` - Animasi CSS

## Fitur

- 4 jenis notifikasi: success, error, warning, info
- Animasi slide down saat muncul
- Animasi fade out saat menghilang
- Auto-close setelah 5 detik
- Tombol close untuk menutup notifikasi
- Responsif dengan max-width
- Integrasi dengan session flash Laravel

