# 📋 PANDUAN PENGISIAN LAPORAN FINAL PROJECT SI118

## Status Pembuatan Laporan

✅ **SUDAH SELESAI (OTOMATIS)**
- Cover page dengan judul dan identitas universitas
- Bab I: Pendahuluan (Latar Belakang + Value Proposisi)
- Bab II: Pembahasan
  - 2.1 Software dan Perangkat yang Digunakan
  - 2.2 Rancangan Database dan Relasi
  - 2.3 Fitur-Fitur dan Menu Halaman Web
  - 2.4 Alamat Web dan Kredensial Akses
  - 2.5 Link Dokumentasi dan File Backup
- Bab III: Kesimpulan
- Daftar Pustaka
- Lampiran dengan Template

---

## ⚠️ BAGIAN YANG PERLU ANDA ISI SECARA MANUAL

### 1. **Data Anggota Kelompok** (Halaman 4)
Silakan isi tabel dengan:
- **No.** | **Nama Lengkap** | **NIM** | **Peran/Kontribusi**
  - Contoh: 1 | Budi Santoso | 1234567890 | Backend Developer

**Minimal 3 anggota, maksimal 4 anggota**

---

### 2. **Alamat Web dan Kredensial Akses** (Halaman 11-12)

Isi field berikut:
- **Status Hosting:**
  - Pilih apakah aplikasi masih di local development atau sudah dihosting
  - Format: `http://localhost:8000` atau `https://your-domain.com`

- **Kredensial Admin:**
  - Username: `admin` (sudah terisi)
  - Password: `admin` (sudah terisi)
  - ⚠️ Pastikan credentials ini sudah ditest dan berfungsi

- **Akses Admin Dashboard:**
  - Contoh: `http://localhost:8000/admin/login`

---

### 3. **Link Dokumentasi dan File Backup** (Halaman 13)

**Sangat Penting!** Anda harus membuat folder backup di Google Drive:

**Langkah-langkah:**
1. Buka Google Drive dan buat folder baru
2. Beri nama: `SI118_[NAMA_KELOMPOK]_TierList`
   - Contoh: `SI118_Kelompok1_TierList`

3. Upload file-file berikut ke folder tersebut:
   - ✅ Source code lengkap (seluruh folder proyek Laravel)
   - ✅ File database dump (export.sql)
   - ✅ Dokumentasi teknis (TECHNICAL_DOCUMENTATION.md)
   - ✅ Screenshot fitur-fitur (minimal 5-10 screenshot)
   - ✅ File .env.example (konfigurasi)

4. **Sharing Settings:**
   - Klik "Share"
   - Pilih "Anyone with the link"
   - Set permission ke "Viewer" (read-only)
   - Copy link

5. **Paste link** ke dokumen laporan di field yang sudah disediakan

**Format link:** `https://drive.google.com/drive/folders/1abc...`

---

### 4. **Lampiran: Profil Anggota dan Job Description** (Halaman 16+)

Untuk **SETIAP ANGGOTA**, isi template berikut:

```
ANGGOTA [NOMOR]

Nama: [NAMA LENGKAP]
NIM: [NIM]
Foto: [SILAKAN COPY PASTE FOTO ATAU SERTAKAN LINK]

Job Description:
Tanggung Jawab dan Kontribusi dalam Proyek:

[URAIKAN SECARA DETAIL - Min. 200 kata per anggota]

Aspek yang harus dicakup:
- Modul/fitur apa yang dikerjakan
  Contoh: "Saya menangani Backend Management Games (CRUD games)"

- File-file yang dibuat/dimodifikasi
  Contoh: "GameController.php, games/index.blade.php, games/create.blade.php"

- Challenge/masalah dan cara mengatasinya
  Contoh: "Masalah validasi slug unik, solusi menggunakan unique rule di Form Request"

- Jam kerja perkiraan
  Contoh: "±25 jam kerja"

- Skill/teknologi yang digunakan
  Contoh: "Laravel, Blade, Bootstrap, MySQL, RESTful API"
```

**Penting:** Setiap anggota harus punya kontribusi yang jelas dan terukur!

---

## 📸 Tips Membuat Screenshot

Untuk dokumentasi yang lebih baik, ambil screenshot dari:

### Frontend (Publik):
1. Halaman utama (daftar game)
2. Halaman tier list dengan beberapa kategori
3. Responsive design (mobile view)

### Admin Panel:
1. Halaman login admin
2. Dashboard admin
3. List games
4. Form tambah/edit game
5. List karakter
6. Tier list management
7. Database seeder (jika ada data sample)

**Catatan:** Minimal 5 screenshot, maksimal 10 screenshot

---

## 📝 Struktur File Backup di Google Drive

```
SI118_Kelompok1_TierList/
├── laravel-project/
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── .env.example
│   ├── composer.json
│   ├── package.json
│   └── ...
├── export.sql (database backup)
├── TECHNICAL_DOCUMENTATION.md
├── SCREENSHOTS/
│   ├── 01_homepage.png
│   ├── 02_tierlist.png
│   ├── 03_admin_login.png
│   ├── 04_admin_dashboard.png
│   └── ...
└── README.txt (instruksi setup)
```

---

## ✅ Checklist Sebelum Submit

- [ ] Data anggota kelompok sudah diisi lengkap
- [ ] Alamat web dan kredensial admin sudah ditest
- [ ] Google Drive folder sudah dibuat dan di-share
- [ ] Semua file sudah diupload ke Google Drive
- [ ] Link Google Drive sudah dicopy ke dokumen
- [ ] Lampiran profil dan job description sudah lengkap
- [ ] Dokumen sudah direview dan tidak ada typo
- [ ] File DOCX sudah dibuka di Word dan formatnya benar
- [ ] File PDF sudah dibuat dari DOCX (jika perlu)

---

## 🔗 File Dokumentasi yang Sudah Ada

Referensi untuk melengkapi laporan:

1. **TECHNICAL_DOCUMENTATION.md** - Dokumentasi teknis lengkap aplikasi
2. **export.sql** - Database structure dan schema
3. **composer.json & package.json** - Dependencies dan tools
4. **Blade templates** di `resources/views/` - Frontend code
5. **Controllers** di `app/Http/Controllers/` - Backend logic

---

## 📞 Bantuan & Pertanyaan

Jika ada bagian yang tidak jelas, silakan:
1. Buka `TECHNICAL_DOCUMENTATION.md` untuk referensi teknis
2. Baca kode source di folder `app/` dan `resources/`
3. Cek database structure di `export.sql`

---

**Deadline Submission:** Sesuai dengan jadwal UAS SI118

**Format File:** PDF (buat dari DOCX menggunakan "Save As" di Microsoft Word)

Good luck! 🎉
