# 1. Tujuan Project

Tujuan project adalah membangun **aplikasi klinik / Rekam Medis Elektronik (RME)** yang dapat membantu proses pelayanan pasien dari awal datang sampai pelayanan selesai.

Secara umum aplikasi harus dapat menangani proses:

1. Pasien melakukan pendaftaran.
2. Pasien mendapatkan nomor antrean.
3. Perawat melakukan pemeriksaan awal.
4. Dokter melakukan pemeriksaan.
5. Dokter memberikan diagnosis, tindakan, dan resep.
6. Apoteker memproses resep dan obat.
7. Kasir memproses pembayaran.
8. Kunjungan pasien dinyatakan selesai.
9. Data tertentu nantinya direncanakan untuk dapat terintegrasi dengan **SATUSEHAT Kementerian Kesehatan**.

Project ini merupakan pengembangan dari assessment dan mini project yang sudah dikerjakan pada minggu pertama.

---

# 2. Hubungan dengan Tugas Minggu Pertama

Pada minggu pertama sudah dilakukan latihan:

- Fundamental programming
- List dan loop
- Function
- Validasi input
- Debugging
- SQL dasar
- CRUD pasien
- Sistem antrean sederhana
- Git
- Kerja sama dalam satu repository

Semua materi tersebut akan digunakan kembali pada project utama.

Contoh:

- CRUD pasien -> menjadi modul pasien.
- Generate nomor antrean -> menjadi modul antrean klinik.
- SQL -> digunakan untuk merancang database.
- Git -> digunakan untuk kerja kolaborasi.
- Validasi -> wajib digunakan pada setiap form.
- Debugging -> digunakan ketika menemukan error.

---

# 3. Gambaran Alur Aplikasi

Alur utama aplikasi:

```text
PASIEN
  |
  v
PENDAFTARAN
  |
  v
ANTREAN
  |
  v
PEMERIKSAAN AWAL / PERAWAT
  |
  v
PEMERIKSAAN DOKTER
  |
  +------> DIAGNOSIS
  |
  +------> TINDAKAN
  |
  +------> RESEP
             |
             v
           APOTEK
             |
             v
           KASIR
             |
             v
       KUNJUNGAN SELESAI
```

Satu pasien dapat datang ke klinik berkali-kali.

Karena itu:

```text
1 PASIEN
    |
    | dapat memiliki
    v
BANYAK KUNJUNGAN
```

Data pasien dan data kunjungan harus dibedakan.

---

# 4. Role Pengguna

Aplikasi direncanakan memiliki **8 role utama**.

## 4.1 Super Admin

Tugas utama:

- Mengelola seluruh sistem.
- Mengelola user.
- Mengelola role dan permission.
- Mengakses semua modul.
- Melakukan konfigurasi sistem.

Super Admin memiliki hak akses tertinggi.

---

## 4.2 Admin Klinik

Tugas utama:

- Mengelola data master klinik.
- Mengelola poli.
- Mengelola tenaga kesehatan.
- Mengelola jadwal.
- Mengelola akun pengguna tertentu.
- Membantu pengelolaan operasional sistem.

---

## 4.3 Pendaftaran

Tugas utama:

- Mendaftarkan pasien baru.
- Mencari pasien lama.
- Membuat kunjungan pasien.
- Memilih poli tujuan.
- Membuat nomor antrean.
- Mengubah status tertentu pada proses pendaftaran.

---

## 4.4 Perawat

Tugas utama:

- Melihat pasien yang menunggu pemeriksaan.
- Mengisi pemeriksaan awal.
- Mengisi tanda vital.

Contoh tanda vital:

- Tekanan darah
- Nadi
- Suhu
- Berat badan
- Tinggi badan
- Respirasi

---

## 4.5 Dokter

Tugas utama:

- Melihat antrean pasien.
- Membuka riwayat pasien.
- Mengisi hasil pemeriksaan.
- Mengisi SOAP / catatan medis.
- Menentukan diagnosis.
- Menentukan tindakan.
- Membuat resep.

---

## 4.6 Apoteker

Tugas utama:

- Melihat resep dari dokter.
- Memproses resep.
- Menyerahkan obat.
- Mengelola stok obat.
- Mencatat pergerakan stok.

---

## 4.7 Kasir

Tugas utama:

- Membuat tagihan.
- Melihat layanan atau tindakan pasien.
- Memproses pembayaran.
- Mengubah status pembayaran.

---

## 4.8 Manajemen

Tugas utama:

- Melihat laporan.
- Melihat statistik kunjungan.
- Melihat laporan transaksi.
- Melihat laporan farmasi.
- Memantau operasional klinik.

Role Manajemen lebih banyak memiliki akses **read-only**.

---

# 5. Konsep Hak Akses

Aplikasi tidak hanya mengecek nama role.

Sistem menggunakan konsep:

```text
USER
 |
 v
ROLE
 |
 v
PERMISSION
```

Contoh permission:

```text
patient.view
patient.create
patient.update

visit.view
visit.create

queue.call

vital.create

medical_record.create

prescription.create

medicine.dispense

invoice.create

payment.create

report.view
```

Contoh:

Pendaftaran mungkin memiliki:

```text
patient.view
patient.create
patient.update
visit.create
queue.create
```

Tetapi Pendaftaran tidak boleh memiliki:

```text
medical_record.create
prescription.create
medicine.dispense
```

Pengecekan hak akses harus dilakukan pada sisi server.

---

# 6. Modul yang Akan Dibangun

Project dibagi menjadi beberapa modul.

## Modul 1 - Authentication dan RBAC

Mencakup:

- Login
- Logout
- User
- Role
- Permission
- User Role
- Role Permission

---

## Modul 2 - Master Data

Mencakup:

- Poli
- Tenaga kesehatan
- Jadwal dokter
- Layanan
- Obat
- Data referensi lain

---

## Modul 3 - Pasien

Mencakup:

- Tambah pasien
- Edit pasien
- Cari pasien
- Detail pasien
- Riwayat kunjungan

---

## Modul 4 - Kunjungan

Kunjungan adalah data ketika pasien datang ke klinik.

Contoh:

```text
Pasien:
Budi

Kunjungan 1:
01-10-2026

Kunjungan 2:
15-10-2026
```

Satu pasien dapat memiliki banyak kunjungan.

---

## Modul 5 - Antrean

Mencakup:

- Generate nomor antrean.
- Nomor antrean per poli.
- Status antrean.

Status awal:

```text
Menunggu
Dipanggil
Sedang Dilayani
Selesai
```

Nomor antrean tidak boleh dibuat menggunakan:

```text
MAX(nomor) + 1
```

Gunakan mekanisme counter dan transaksi database agar aman ketika beberapa pengguna membuat antrean bersamaan.

---

## Modul 6 - Pemeriksaan Awal

Dilakukan oleh Perawat.

Mencakup:

- Tekanan darah
- Nadi
- Suhu
- Respirasi
- Berat badan
- Tinggi badan
- Catatan perawat

---

## Modul 7 - Pemeriksaan Dokter

Mencakup:

- Keluhan
- SOAP / catatan medis
- Diagnosis
- Tindakan
- Resep

---

# 7. Referensi Medis

Beberapa data medis tidak boleh hanya menggunakan teks bebas.

Sistem direncanakan menggunakan referensi standar.

## Diagnosis

Menggunakan:

```text
ICD-10
```

Contoh konsep:

```text
Kode        Nama
I10         Essential Hypertension
```

---

## Tindakan

Menggunakan referensi kode tindakan yang sesuai kebutuhan sistem.

---

## Obat

Obat yang nantinya berkaitan dengan SATUSEHAT dapat memiliki referensi:

```text
KFA
```

Perlu dibedakan antara:

```text
REFERENSI OBAT
```

dan

```text
STOK OBAT KLINIK
```

Tidak semua obat dalam referensi harus tersedia di klinik.

---

# 8. Farmasi

Modul farmasi mencakup:

```text
Prescription
Prescription Item
Dispense
Medicine
Stock Movement
```

Alur:

```text
Dokter membuat resep
        |
        v
Apoteker melihat resep
        |
        v
Apoteker menyerahkan obat
        |
        v
Stok berkurang
```

Setiap perubahan stok harus memiliki riwayat.

---

# 9. Kasir

Modul kasir mencakup:

- Layanan
- Invoice
- Invoice Item
- Payment

Harga transaksi harus disimpan pada saat invoice dibuat.

Jangan hanya membaca harga terbaru dari master layanan karena harga layanan dapat berubah di kemudian hari.

---

# 10. Integrasi SATUSEHAT

Project direncanakan memiliki integrasi dengan:

**SATUSEHAT Kementerian Kesehatan**

Tetapi integrasi SATUSEHAT **bukan tugas pertama**.

Fokus awal adalah memastikan:

- database benar,
- alur klinik berjalan,
- role berjalan,
- data pasien benar,
- kunjungan berjalan,
- pemeriksaan berjalan.

Setelah aplikasi utama stabil, barulah integrasi dilakukan.

Beberapa data akan memerlukan ID dari SATUSEHAT, misalnya:

- Patient / IHS Number
- Practitioner
- Organization
- Location

Integrasi akan dibuat sebagai modul/layer terpisah.

Contoh konsep:

```text
APLIKASI KLINIK
      |
      v
INTEGRATION SERVICE
      |
      v
SATUSEHAT
```

Jika SATUSEHAT sedang tidak dapat diakses, aplikasi klinik tetap harus dapat digunakan.

Data yang belum berhasil dikirim akan masuk ke antrean sinkronisasi.

---

# 11. Pembagian Tugas Awal

Pada tahap awal, pekerjaan dibagi sebagai berikut.

## ANAK A

Fokus awal:

### Pasien

- CRUD pasien.
- Pencarian pasien.
- Detail pasien.
- Validasi data pasien.

### Kunjungan

- Membuat kunjungan.
- Menghubungkan pasien dengan kunjungan.
- Riwayat kunjungan pasien.

### Antrean

- Generate nomor antrean.
- Status antrean.
- Riwayat antrean.

---

## ANAK B

Fokus awal:

### Authentication

- Login.
- Logout.

### RBAC

- Role.
- Permission.
- User Role.
- Role Permission.

### Master Data

- CRUD Poli.
- CRUD Tenaga Kesehatan.
- Jadwal Dokter.

---

# 12. Hal yang Harus Dirancang oleh Masing-Masing

Sebelum coding modul, masing-masing siswa harus menentukan:

1. Tabel apa yang dibutuhkan.
2. Field apa yang dibutuhkan.
3. Primary key.
4. Foreign key.
5. Relasi antar tabel.
6. Validasi input.
7. Hak akses pengguna.
8. Skenario testing.

Tidak perlu membuat seluruh database aplikasi sekaligus.

Cukup fokus pada modul yang sedang dikerjakan.

---

# 13. Aturan Git

Project menggunakan:

**1 repository bersama**

Aturan:

```text
main
 |
 +--- feature/nama-fitur-A
 |
 +--- feature/nama-fitur-B
```

Tidak boleh melakukan coding langsung pada branch:

```text
main
```

Alur kerja:

```text
Ambil tugas Trello
      |
      v
Buat branch
      |
      v
Coding
      |
      v
Testing
      |
      v
Commit
      |
      v
Push
      |
      v
Pull Request
      |
      v
Code Review
      |
      v
Merge
```

---

# 14. Aturan Pull Request

Anak A melakukan review terhadap pekerjaan Anak B.

Anak B melakukan review terhadap pekerjaan Anak A.

Yang diperiksa minimal:

- kode dapat berjalan,
- validasi ada,
- penamaan jelas,
- tidak ada kode yang tidak digunakan,
- database sesuai,
- fitur sesuai requirement,
- tidak merusak fitur lain.

Tidak diperbolehkan langsung merge tanpa review.

---

# 15. Trello

Workflow Trello:

```text
BACKLOG
   |
   v
SIAP
   |
   v
DIKERJAKAN
   |
   v
REVIEW
   |
   v
SELESAI
```

Tambahan kolom:

```text
PERTANYAAN MENTOR
```

Jika menemukan masalah atau keputusan yang membutuhkan persetujuan mentor, masukkan ke kolom tersebut.

---

# 16. Definisi Tugas Selesai

Tugas tidak dianggap selesai hanya karena halaman dapat dibuka.

Satu tugas dianggap selesai jika:

- fitur dapat digunakan,
- input sudah divalidasi,
- database sudah benar,
- permission sudah diterapkan,
- sudah dilakukan testing,
- tidak ada error utama,
- sudah di-push,
- sudah dibuat Pull Request,
- sudah direview.

---

# 17. Hal yang Tidak Boleh Dilakukan

Tidak diperbolehkan:

- coding langsung di `main`,
- menyimpan password dalam plain text,
- commit file `.env`,
- menggunakan query database tanpa memahami efeknya,
- menghapus migration sembarangan,
- mengubah struktur database besar tanpa diskusi,
- menggunakan `MAX()+1` untuk nomor antrean,
- menyimpan diagnosis hanya sebagai teks bebas jika sudah menggunakan referensi kode,
- memasukkan data pasien asli untuk testing SATUSEHAT sandbox.

Gunakan data fiktif selama development.

---

# 18. Target Awal

Target pertama bukan menyelesaikan seluruh aplikasi.

Target pertama adalah membuat fondasi:

### Anak A

```text
Patient
   |
   v
Visit
   |
   v
Queue
```

### Anak B

```text
Login
   |
   v
RBAC
   |
   v
Poli
   |
   v
Practitioner
   |
   v
Schedule
```

Setelah dua bagian tersebut selesai dan berhasil digabungkan, project dilanjutkan ke:

```text
Pemeriksaan Perawat
        |
        v
Pemeriksaan Dokter
        |
        v
Farmasi
        |
        v
Kasir
        |
        v
Laporan
        |
        v
Integrasi SATUSEHAT
```

---

# 19. Tujuan Pembelajaran PKL

Selain menghasilkan aplikasi, project ini bertujuan agar siswa dapat belajar:

- memahami kebutuhan sistem,
- merancang database,
- membuat aplikasi berbasis role,
- menggunakan Git,
- bekerja dengan branch,
- menggunakan Pull Request,
- melakukan code review,
- debugging,
- testing,
- berkomunikasi dalam tim,
- membaca dokumentasi teknis,
- bertanggung jawab terhadap modul masing-masing.

Yang dinilai bukan hanya:

> "Program bisa jalan."

Tetapi juga:

> "Bagaimana program tersebut dirancang, dibuat, diuji, didokumentasikan, dan dikerjakan bersama."

---

# 20. Prinsip Utama Project

```text
KERJAKAN SATU MODUL
        |
        v
PASTIKAN BENAR
        |
        v
TEST
        |
        v
REVIEW
        |
        v
BARU LANJUT KE MODUL BERIKUTNYA
```

Jangan mencoba membuat seluruh fitur sekaligus.

Project akan dibangun secara bertahap.
