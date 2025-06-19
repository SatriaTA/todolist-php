# 📝 Aplikasi To-Do List Sederhana

Aplikasi to-do list berbasis web yang dibuat dengan PHP dan Bootstrap untuk mengelola daftar tugas secara sederhana dan efisien dengan menerapkan best practice coding.

## 📋 Deskripsi

Aplikasi To-Do List ini adalah sistem manajemen tugas sederhana yang memungkinkan pengguna untuk membuat, mengedit, menandai selesai, dan menghapus tugas. Aplikasi ini dibangun menggunakan teknologi web modern dengan fokus pada user experience yang baik dan kode yang terstruktur.

### 🎯 Tujuan Pengembangan
- Menyediakan platform manajemen tugas yang mudah digunakan
- Menerapkan best practice dalam pengembangan web
- Menggunakan teknologi yang modern dan responsif
- Memberikan contoh implementasi CRUD operations yang baik

## ✨ Fitur Utama

### 🔧 Fitur Dasar
- ✅ **Tambah Tugas Baru**: Form input untuk menambahkan tugas baru dengan validasi
- ✅ **Edit Tugas**: Modal popup untuk mengubah judul tugas
- ✅ **Checkbox Status**: Checkbox untuk menandai tugas selesai/belum dengan auto-submit
- ✅ **Tombol Hapus**: Tombol delete dengan konfirmasi untuk menghapus tugas
- ✅ **Daftar Tugas**: Tampilan tabel yang rapi dengan informasi lengkap

### 📊 Fitur Lanjutan
- ✅ **Statistik Real-time**: Informasi jumlah total, selesai, dan belum selesai
- ✅ **Responsive Design**: Tampilan yang responsif menggunakan Bootstrap 5
- ✅ **Best Practice Coding**: Kode terstruktur dengan komentar yang jelas
- ✅ **Session Management**: Data tersimpan dengan aman menggunakan PHP Session
- ✅ **AJAX Integration**: Update data tanpa refresh halaman

### 🛡️ Fitur Keamanan
- ✅ **Validasi Input**: Mencegah data kosong dan input yang tidak valid
- ✅ **XSS Protection**: Escape HTML untuk mencegah serangan XSS
- ✅ **Form Resubmission Prevention**: Mencegah duplikasi data saat refresh
- ✅ **Confirmation Dialogs**: Konfirmasi sebelum operasi destruktif

## 🏗️ Struktur Program

### Pendekatan Pemrograman Terstruktur

Aplikasi ini menggunakan pendekatan pemrograman terstruktur dengan:

#### 1. **Fungsi-fungsi Terpisah**:
- `getNextId()` - Mendapatkan ID unik berikutnya
- `addTask()` - Menambah tugas baru dengan validasi
- `editTask()` - Mengubah judul tugas
- `toggleTaskStatus()` - Mengubah status tugas (checkbox)
- `deleteTask()` - Menghapus tugas berdasarkan ID
- `getStatusBadge()` - Menampilkan badge status dengan styling
- `getTaskStatistics()` - Menghitung statistik tugas

#### 2. **Struktur Data Array of Objects**:
```php
$tasks = [
    ["id" => 1, "title" => "Belajar PHP", "status" => "belum"],
    ["id" => 2, "title" => "Kerjakan Tugas UX", "status" => "selesai"]
];
```

#### 3. **Perulangan foreach**: Untuk menampilkan data tugas

### Best Practice Coding

#### **Indentasi dan Formatting**:
- Konsisten menggunakan 4 spasi untuk indentasi
- Pengelompokan kode dengan komentar section
- Pemisahan logika yang jelas

#### **Penamaan Variabel**:
- `$tasks` - Array tugas utama
- `$taskId` - ID tugas spesifik
- `$statistics` - Data statistik
- `$completed` - Jumlah tugas selesai
- `$pending` - Jumlah tugas belum selesai

#### **Komentar yang Jelas**:
- Header komentar untuk setiap fungsi
- Dokumentasi parameter dan return value
- Komentar section untuk pengelompokan kode
- Komentar inline untuk logika kompleks

## 🎨 Layout dan UI

Aplikasi memiliki layout yang terstruktur:

1. **Header**: Judul aplikasi dan informasi total tugas
2. **Form Input**: Form untuk menambahkan tugas baru
3. **Daftar Tugas**: Tabel dengan checkbox dan tombol aksi
4. **Statistik**: Informasi ringkasan tugas
5. **Footer**: Informasi aplikasi

### Komponen Bootstrap yang Digunakan:
- **Cards**: Untuk mengelompokkan konten
- **Tables**: Untuk menampilkan daftar tugas
- **Forms**: Untuk input dan aksi
- **Buttons**: Untuk tombol aksi
- **Badges**: Untuk status dan ID
- **Grid System**: Untuk layout responsif
- **Modal**: Untuk form edit tugas

## 🔧 Fitur Interaktif

### **Checkbox Status**:
- Checkbox yang langsung mengubah status saat dicentang/dilepas
- Auto-submit form menggunakan JavaScript
- Visual feedback dengan styling berbeda untuk tugas selesai

### **Tombol Edit**:
- Modal popup untuk mengedit judul tugas
- Form input yang sudah terisi dengan judul saat ini
- Validasi input (tidak boleh kosong)
- Update instan tanpa refresh halaman

### **Tombol Hapus**:
- Tombol delete dengan ikon trash
- Konfirmasi JavaScript sebelum penghapusan
- Styling outline untuk konsistensi UI

### **Validasi Input**:
- Validasi tugas kosong sebelum ditambahkan
- Escape HTML untuk mencegah XSS
- Konfirmasi sebelum operasi destruktif

## 🛡️ Keamanan

- **Validasi Input**: Mencegah tugas kosong
- **Escape HTML**: `htmlspecialchars()` untuk mencegah XSS
- **Konfirmasi**: JavaScript confirm untuk penghapusan
- **Validasi ID**: Pengecekan ID sebelum operasi
- **Session Management**: Data tersimpan dengan aman

## 🚀 Cara Menjalankan

### Persyaratan Sistem
- **XAMPP** (Apache + PHP + MySQL)
- **PHP 7.4** atau lebih tinggi
- **Web Browser** modern (Chrome, Firefox, Safari, Edge)

### Langkah-langkah Instalasi

1. **Download dan Install XAMPP**
   - Kunjungi [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Download XAMPP sesuai sistem operasi Anda
   - Install XAMPP dengan pengaturan default

2. **Start Apache Server**
   - Buka XAMPP Control Panel
   - Klik tombol "Start" di sebelah Apache
   - Pastikan status menunjukkan "Running"

3. **Letakkan File Aplikasi**
   - Buka folder `C:\xampp\htdocs\`
   - Buat folder baru bernama `todolist`
   - Copy file `index.php` ke dalam folder tersebut

4. **Akses Aplikasi**
   - Buka web browser
   - Ketik URL: `http://localhost/todolist/`
   - Atau: `http://127.0.0.1/todolist/`

### Struktur Folder di XAMPP
```
C:\xampp\htdocs\todolist\
├── index.php      # File utama aplikasi dengan semua fitur
└── README.md      # Dokumentasi lengkap aplikasi
```

## 📁 Struktur File

```
todolist/
├── index.php      # File utama aplikasi dengan semua fitur
└── README.md      # Dokumentasi lengkap aplikasi
```

## 🔄 Workflow Penggunaan

1. **Tambah Tugas**: Isi form dan klik "Tambah Tugas"
2. **Edit Tugas**: Klik tombol edit (ikon pensil) dan ubah judul
3. **Toggle Status**: Centang/uncheck checkbox untuk mengubah status
4. **Hapus Tugas**: Klik tombol trash dan konfirmasi
5. **Lihat Statistik**: Monitor progress di bagian statistik

## 🎯 Pengembangan Selanjutnya

Beberapa fitur yang bisa ditambahkan:
- **Database Integration**: Penyimpanan data ke MySQL
- **User Authentication**: Login dan manajemen user
- **Categories**: Pengelompokan tugas berdasarkan kategori
- **Deadlines**: Tanggal jatuh tempo dan reminder
- **Search & Filter**: Pencarian dan filter tugas
- **Export Data**: Export ke CSV/PDF
- **Drag & Drop**: Reordering tugas dengan drag-drop
- **Dark Mode**: Toggle tema gelap/terang
- **Multi-language**: Dukungan bahasa Indonesia dan Inggris

## 📝 Catatan Teknis

- **PHP Version**: Kompatibel dengan PHP 7.4+
- **Bootstrap**: Version 5.3.0
- **Font Awesome**: Version 6.4.0
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)
- **Session Management**: PHP Session untuk penyimpanan data
- **AJAX**: Fetch API untuk komunikasi asynchronous

## 🤝 Kontributor

### Developer
- **[Satria Tantra Adlillah](https://github.com/SatriaTA)** - Full Stack Developer


## 📄 Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detail.

## 📞 Kontak

Jika Anda memiliki pertanyaan atau saran, silakan hubungi:

- **Email**: [satriatantra02@gmail.com]
- **GitHub**: [https://github.com/SatriaTA](https://github.com/SatriaTA)

---

**Aplikasi To-Do List** - Dibuat dengan ❤️ menggunakan PHP dan Bootstrap 