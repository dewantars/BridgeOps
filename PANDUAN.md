# Panduan Penggunaan BridgeOps AI (MVP)

BridgeOps AI adalah platform komunikasi proyek berbasis AI yang menghubungkan aktivitas teknis tim engineering (seperti commit GitHub, pull request, issues, dan log error) dengan kebutuhan transparansi klien. Aplikasi ini menerjemahkan data teknis yang rumit menjadi ringkasan bisnis yang mudah dipahami oleh stakeholder non-teknis.

---

## 1. Kebutuhan Sistem & Prasyarat

Sebelum menjalankan aplikasi, pastikan Anda telah menginstal tools berikut di komputer Anda:
*   **PHP >= 8.2**
*   **Composer** (Dependency manager PHP)
*   **Node.js & NPM** (Untuk membangun UI assets dengan Vite & Tailwind)
*   **Docker Desktop** (Untuk menjalankan PostgreSQL database dan pgAdmin)
*   **ngrok** (Opsional, untuk menguji GitHub Webhook di local environment)

---

## 2. Langkah Instalasi & Setup Pertama Kali

Ikuti langkah-langkah di bawah ini untuk menyiapkan aplikasi di komputer lokal Anda:

### Langkah 2.1: Konfigurasi Environment File
Salin file `.env.example` menjadi `.env` di direktori utama proyek:
```bash
cp .env.example .env
```
Buka file `.env` dan pastikan konfigurasi database diatur sesuai dengan Docker:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bridgeops
DB_USERNAME=bridgeops_user
DB_PASSWORD=bridgeops_pass

# Timezone & App URL
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Jakarta

# Konfigurasi Queue (Sangat Penting untuk Pemrosesan AI secara Async)
QUEUE_CONNECTION=database

# Konfigurasi Gemini API (Bisa dikosongkan untuk memakai Mock Mode)
GEMINI_API_KEY=

# Secret Token untuk GitHub Webhook Verification
GITHUB_WEBHOOK_SECRET=your_webhook_secret_token
```

### Langkah 2.2: Menjalankan Database PostgreSQL via Docker
Aplikasi ini menyertakan `docker-compose.yml` untuk menjalankan PostgreSQL dan pgAdmin secara instan.
Jalankan perintah berikut:
```bash
docker compose up -d
```
> [!NOTE]
> *   **PostgreSQL** akan berjalan di port `5432` dengan database `bridgeops`.
> *   **pgAdmin 4** (alat manajemen database berbasis web) dapat diakses melalui browser di `http://localhost:5050` dengan email `admin@bridgeops.local` dan password `admin`.

### Langkah 2.3: Instalasi Dependensi PHP & Generate Key
Jalankan Composer untuk menginstal semua package PHP:
```bash
composer install
php artisan key:generate
```

### Langkah 2.4: Migrasi Database & Seeding Data Demo
Jalankan migrasi database beserta data dummy (seeder):
```bash
php artisan migrate --seed
```
Perintah ini akan membuat semua tabel database yang diperlukan dan secara otomatis mendaftarkan **3 akun demo** dengan berbagai role.

### Langkah 2.5: Instalasi & Build Aset Frontend
Instal dependensi JavaScript dan jalankan Vite dev server:
```bash
npm install
npm run dev
```

### Langkah 2.6: Menjalankan Web Server & Queue Worker
Karena pemrosesan AI (Gemini API) dikerjakan di latar belakang (background) agar tidak membebani performa browser pengguna, Anda perlu menjalankan **dua terminal terpisah**:

*   **Terminal 1 (Web Server)**:
    ```bash
    php artisan serve
    ```
    Aplikasi akan berjalan dan dapat diakses di **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

*   **Terminal 2 (Queue Worker)**:
    ```bash
    php artisan queue:work
    ```
    Queue worker ini wajib tetap berjalan untuk memproses data dari GitHub Webhook dan input error log manual untuk dikirimkan ke Gemini API.

---

## 3. Akun Demo Bawaan (Role Access Control)

Aplikasi ini menggunakan Laravel Breeze untuk otentikasi dan dilengkapi dengan Role-Based Access Control (RBAC). Berikut adalah 3 akun demo yang bisa digunakan untuk menguji aplikasi:

| Role | Email | Password | Hak Akses (Permissions) |
| :--- | :--- | :--- | :--- |
| **Administrator (Admin)** | `admin@bridgeops.local` | `password` | Akses penuh ke seluruh fitur sistem, melihat/mengedit semua proyek, semua timeline aktivitas, input error log, dan generate laporan. |
| **Project Manager (PM)** | `pm@bridgeops.local` | `password` | Mengelola proyek yang ditugaskan, melihat timeline aktivitas, membuat error log manual, dan men-generate laporan proyek untuk dikirim ke klien. |
| **Client / Stakeholder** | `client@bridgeops.local` | `password` | *Read-only access*. Hanya dapat melihat dashboard ringkasan proyek, timeline aktivitas bisnis, dan mengunduh/melihat laporan AI yang sudah diterbitkan. Tidak dapat menambah atau mengedit data. |

---

## 4. Alur Kerja Fitur Utama & Penggunaan

### 4.1. Manajemen Project (Project Management)
1. Login sebagai **Admin** atau **PM**.
2. Masuk ke menu **Projects** di sidebar.
3. Klik tombol **Create New Project**.
4. Isi data proyek:
   *   **Project Name**: Nama proyek Anda (misal: "E-Commerce Re-platform").
   *   **Description**: Deskripsi singkat proyek.
   *   **GitHub Repository**: Format penulisan harus `username/nama-repo` atau `organisasi/nama-repo` (contoh: `octocat/Hello-World`). Ini penting agar webhook GitHub bisa memetakan aktivitas teknis ke proyek yang tepat.
5. Klik **Create Project**.

### 4.2. Timeline Aktivitas Teknis (Engineering Events)
Setiap kali ada aktivitas teknis masuk (melalui GitHub Webhook atau Simulasi), data akan disimpan ke database.
1. Sistem akan mendispatch `GenerateAiSummaryJob` ke antrean (`queue`).
2. Job tersebut akan mengirimkan payload teknis ke Gemini API.
3. Gemini API menerjemahkan detail teknis (commit message, diff, status issue/PR) menjadi bahasa non-teknis, misalnya:
   *   *Commit Teknis*: `"refactor: implement redis caching on product catalog retrieval"`
   *   *AI Summary*: `"Meningkatkan kecepatan akses katalog produk dengan memanfaatkan sistem penyimpanan memori sementara (cache), sehingga mengurangi beban server database saat traffic tinggi."`
4. Pengguna dengan role **Admin**, **PM**, atau **Client** dapat mengakses halaman **Activities** untuk melihat timeline ringkasan bisnis ini.

### 4.3. Input Log Error Manual (Manual Error Logging)
Terkadang, tim mendapati bug dari QA, laporan klien, atau sistem monitoring internal (seperti Sentry) yang tidak terhubung dengan GitHub. PM atau Admin dapat memasukkannya secara manual:
1. Masuk ke halaman **Manual Errors** -> klik **Report Error**.
2. Isi formulir:
   *   **Project**: Pilih proyek terkait.
   *   **Title**: Judul error (misal: "Gagal memproses pembayaran Midtrans").
   *   **Severity**: `Low`, `Medium`, `High`, atau `Critical`.
   *   **Error Message / Stack Trace**: Detail teknis error (misal: `Error 500: Signature key mismatch on payment notification callback`).
   *   **User/Business Impact**: Dampak bisnisnya (misal: "Klien gagal melakukan checkout barang, transaksi menjadi tertunda").
3. Klik **Save & Summarize**.
4. Sistem akan otomatis meminta Gemini API merangkum error tersebut menjadi penjelasan bisnis yang sopan, beserta analisis langkah perbaikan yang sedang atau harus diambil.

### 4.4. Pembuatan Laporan AI Berkala (Executive Reports)
Fitur ini digunakan oleh PM untuk mengekspor status proyek secara periodik kepada klien.
1. Masuk ke halaman **Reports** -> klik **Generate Report**.
2. Isi formulir pembuatan laporan:
   *   **Project**: Pilih proyek yang ingin dilaporkan.
   *   **Report Type**: Pilih `Daily`, `Weekly`, `Monthly`, atau `Release`.
   *   **Start Date** & **End Date**: Rentang waktu aktivitas proyek yang ingin dimasukkan dalam laporan.
3. Klik **Generate Report**.
4. **Proses AI**: Sistem akan mengumpulkan semua commit, PR, issues, dan manual error log dalam rentang tanggal tersebut. Data tersebut dikirim ke Gemini API untuk menghasilkan sebuah laporan eksekutif dengan struktur:
   *   **Executive Summary**: Ringkasan umum progres proyek dalam bahasa bisnis.
   *   **Key Deliverables**: Fitur-fitur atau perbaikan apa saja yang berhasil diselesaikan.
   *   **Blockers & Risks**: Hambatan teknis atau risiko keterlambatan proyek beserta dampaknya.
   *   **Recommendations**: Langkah strategis berikutnya.
5. Laporan yang sudah selesai akan muncul di daftar laporan. Klien dapat masuk ke dashboard mereka dan langsung membaca laporan ini secara terstruktur.

---

## 5. Integrasi GitHub Webhook secara Lokal (Local Webhook Testing)

Untuk menghubungkan repository GitHub riil dengan server lokal BridgeOps AI, ikuti langkah-langkah berikut:

### Langkah 5.1: Jalankan ngrok
Jalankan ngrok untuk membuat terowongan publik (public tunnel) menuju server lokal Laravel Anda:
```bash
ngrok http 8000
```
Salin URL HTTPS publik yang dihasilkan oleh ngrok, misalnya: `https://a1b2-34-56-78-90.ngrok-free.app`.

### Langkah 5.2: Daftarkan Webhook di GitHub
1. Masuk ke halaman repository GitHub Anda.
2. Pergi ke **Settings** -> **Webhooks** -> klik **Add webhook**.
3. Konfigurasikan field berikut:
   *   **Payload URL**: Tempelkan URL ngrok Anda diikuti dengan `/api/webhooks/github`.
       *   *Contoh*: `https://a1b2-34-56-78-90.ngrok-free.app/api/webhooks/github`
   *   **Content type**: Pilih `application/json`.
   *   **Secret**: Masukkan token rahasia (contoh: `mysecretwebhooktoken`). Pastikan nilai ini **sama dengan** nilai `GITHUB_WEBHOOK_SECRET` di file `.env` Anda.
   *   **Which events would you like to trigger this webhook?**: Pilih `Let me select individual events` dan centang:
       *   `Pushes` (untuk merekam commit)
       *   `Pull requests` (untuk merekam status PR)
       *   `Issues` (untuk merekam status issue tracker)
   *   Pastikan checkbox **Active** dicentang.
4. Klik **Add webhook**.

### Langkah 5.3: Pengujian Webhook
Lakukan perubahan code pada repository GitHub Anda (commit & push, buat issue baru, atau buka pull request). GitHub akan mengirim payload JSON ke server lokal Anda.
1. Webhook endpoint `/api/webhooks/github` akan menerima payload tersebut.
2. Webhook controller memverifikasi signature menggunakan algoritma HMAC sha256 dengan secret token Anda.
3. Jika valid, data disimpan ke tabel `engineering_events` dan job antrean dijalankan untuk menghasilkan ringkasan bisnis dengan Gemini API.
4. Anda dapat memantau log masuk di terminal `php artisan queue:work`.

---

## 6. Integrasi Gemini API Key vs Mock Mode

BridgeOps AI telah mengintegrasikan API resmi Google Gemini (menggunakan model `gemini-2.0-flash` yang cepat dan efisien).

### A. Menggunakan Real Gemini API Key (Rekomendasi)
1. Dapatkan API Key secara gratis atau berbayar melalui [Google AI Studio](https://aistudio.google.com/).
2. Buka file `.env` di root project Anda.
3. Isi API Key tersebut pada baris:
   ```ini
   GEMINI_API_KEY=AIzaSyA123_contoh_key_gemini_anda
   ```
4. Restart antrean queue Anda jika sedang berjalan (`Ctrl+C` lalu jalankan kembali `php artisan queue:work`) agar perubahan konfigurasi `.env` dibaca oleh Laravel.

### B. Menggunakan Mock Mode (Fallback)
Jika Anda belum memiliki API Key atau ingin menguji fungsionalitas sistem secara offline/tanpa kuota API:
1. Kosongkan nilai `GEMINI_API_KEY` di file `.env`:
   ```ini
   GEMINI_API_KEY=
   ```
2. Aplikasi BridgeOps AI secara cerdas akan mendeteksi bahwa API Key kosong dan beralih ke **Mock Fallback**.
3. Sistem akan menghasilkan ringkasan teks simulasi yang realistis dengan tanda prefiks `[MOCK]` di depannya. Fitur dashboard, log error, dan generate laporan akan tetap berfungsi 100% secara visual.

---

## 7. Pemecahan Masalah (Troubleshooting)

### Q: Mengapa AI Summary tidak muncul di dashboard setelah saya membuat manual error atau men-trigger webhook GitHub?
**A**: Pastikan **Queue Worker** sedang berjalan. Karena proses AI bersifat asinkronus, jalankan `php artisan queue:work` di terminal terpisah. Jika queue worker belum dijalankan, job akan tertahan di database (tabel `jobs`).

### Q: Saya mendapatkan error "403 Unauthorized" atau "This action is unauthorized" saat mencoba mengedit project.
**A**: Pastikan Anda login dengan akun yang memiliki hak akses. Hanya role `admin` dan `pm` yang diperbolehkan membuat atau memodifikasi project. Akun `client` hanya memiliki akses melihat data (read-only).

### Q: Bagaimana cara merestart antrean database jika terjadi kendala/error berkelanjutan?
**A**: Anda bisa membersihkan antrean job yang gagal atau tertahan dengan perintah:
```bash
php artisan queue:clear
php artisan queue:flush
```

### Q: Port Docker bertabrakan (Port already in use).
**A**: Jika port PostgreSQL 5432 sudah digunakan oleh instalasi PostgreSQL lokal di komputer Anda, buka file `docker-compose.yml` lalu ubah pemetaan port eksternal (kiri), misalnya dari `"5432:5432"` menjadi `"5433:5432"`, dan ubah juga nilai `DB_PORT=5433` di file `.env` Anda.
