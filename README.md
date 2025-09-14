# Sistem Pakar Perawatan Kulit

Aplikasi sistem pakar untuk menentukan jenis perawatan kulit berdasarkan faktor resiko, jenis kulit, dan gejala yang dialami pengguna.

## Deskripsi

Sistem pakar ini dikembangkan untuk membantu pengguna dalam menentukan jenis perawatan kulit yang sesuai dengan kondisi kulit mereka. Sistem menggunakan metode inferensi forward chaining, yaitu metode penalaran yang dimulai dari fakta-fakta yang diketahui (faktor resiko dan gejala), kemudian bergerak maju melalui premis-premis untuk mencapai kesimpulan (rekomendasi perawatan).

## Fitur

- Diagnosis berdasarkan faktor resiko dan gejala
- Identifikasi jenis kulit
- Deteksi kelainan kulit
- Rekomendasi serum perawatan kulit
- Rekomendasi treatment perawatan kulit
- Penyimpanan riwayat diagnosis
- Cetak hasil diagnosis
- Integrasi model Python dengan fallback PHP
- Perhitungan akurasi diagnosis
- Export hasil ke PDF

## Basis Pengetahuan

Basis pengetahuan sistem ini terdiri dari:
- 8 jenis faktor resiko
- 7 jenis gejala
- 4 jenis kulit
- 7 jenis kelainan kulit
- 10 jenis serum perawatan
- 10 jenis treatment perawatan

## Persyaratan Sistem

### Minimum (PHP Only)
- PHP 7.0 atau lebih tinggi
- MySQL 5.6 atau lebih tinggi
- Web server (Apache, Nginx, dll)

### Optimal (dengan Python)
- Python 3.6+
- Flask dan Flask-CORS
- Semua persyaratan minimum di atas

## Cara Menjalankan

### Langkah 1: Persiapan
1. Pastikan XAMPP sudah terinstal dan berjalan
2. Pastikan folder aplikasi berada di `C:\xampp\htdocs\sistem pakar`

### Langkah 2: Setup Database
1. Buka XAMPP Control Panel
2. Start Apache dan MySQL
3. Akses: `http://localhost/sistem%20pakar/setup.php`
4. Tunggu pesan "Database setup completed successfully!"

### Langkah 3: Instalasi Python (Opsional untuk Performa Optimal)
```bash
cd "C:\xampp\htdocs\sistem pakar"
pip install -r requirements.txt
```

### Langkah 4: Menjalankan API Python (Opsional)
```bash
# Windows
run_api.bat

# Linux/Mac
python3 api.py
```

### Langkah 5: Akses Aplikasi
Buka browser: `http://localhost/sistem%20pakar/`

## Arsitektur Sistem

Sistem menggunakan 3 tingkat model dengan prioritas:

1. **Python API** (Optimal) - Model ML dengan akurasi tinggi
2. **Python Script** (Fallback) - Direct Python execution
3. **PHP Database** (Backup) - Rule-based system

## Testing API Python

Akses `http://localhost/sistem%20pakar/api_test.html` untuk menguji status API Python.

## Penggunaan

1. Buka halaman "Diagnosis" dari menu navigasi
2. Perhatikan indikator model di pojok kanan atas:
   - 🟢 "Python (API)" = Model optimal aktif
   - 🟡 "PHP (Database)" = Fallback mode
3. Pilih faktor resiko yang Anda miliki
4. Pilih gejala yang Anda alami
5. Klik "Analisis Kulit Saya"
6. Lihat hasil dengan tingkat akurasi
7. Export ke PDF atau simpan ke riwayat

## Troubleshooting

### API Python tidak berjalan
- Pastikan Python dan Flask terinstal
- Jalankan `python api.py` di terminal
- Cek `api_test.html` untuk status API

### Database tidak terhubung
- Pastikan MySQL berjalan di XAMPP
- Jalankan `setup.php` sekali lagi
- Periksa konfigurasi di `includes/config.php`

## Struktur Direktori

```
sistem pakar/
├── css/style.css          # Styling utama
├── includes/              # File konfigurasi
│   ├── config.php         # Konfigurasi database
│   ├── functions.php      # Fungsi sistem pakar
│   ├── header.php         # Template header
│   ├── footer.php         # Template footer
│   └── setup_database.php # Setup database
├── js/script.js           # JavaScript utama
├── index.php              # Halaman utama
├── diagnosis.php          # Halaman diagnosis
├── about.php              # Halaman tentang
├── history.php            # Riwayat diagnosis
├── setup.php              # Setup wrapper
├── model.py               # Model Python
├── api.py                 # Flask API server
├── api_test.html          # Testing API
├── requirements.txt       # Dependencies Python
├── run_api.bat           # Script Windows
├── run_api.sh            # Script Linux/Mac
└── README.md             # Dokumentasi
```

## Kontribusi

Kontribusi untuk pengembangan sistem pakar ini sangat diterima. Silakan fork repositori ini, buat branch baru, lakukan perubahan, dan ajukan pull request.

## Lisensi

Sistem pakar ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).