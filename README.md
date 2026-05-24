# BridgeOps AI

**BridgeOps AI** adalah platform komunikasi proyek berbasis AI yang menghubungkan aktivitas teknis tim engineering (seperti commit GitHub, pull request, issues, dan log error) dengan kebutuhan transparansi klien/stakeholder.

Platform ini memecahkan masalah umum di mana Project Manager (PM) harus secara manual mengumpulkan data teknis dan menerjemahkannya menjadi laporan perkembangan proyek. BridgeOps AI mengotomatisasi proses tersebut menggunakan AI (Google Gemini API), sehingga stakeholder dapat memahami progres, risiko, blocker, dan dampak bisnis tanpa harus memahami detail teknis kode.

---

## Fitur Utama MVP
1. **GitHub Webhook Integration**: Menangkap push commit, pull request, dan issue secara real-time.
2. **AI-Powered Summary**: Mengubah pesan commit teknis yang rumit menjadi ringkasan bernilai bisnis menggunakan Gemini API.
3. **Manual Error Logging**: Memasukkan log error sistem secara manual dan menerjemahkannya ke penjelasan dampak bisnis bagi klien.
4. **Periodic Executive Reports**: Menghasilkan laporan berkala (harian, mingguan, bulanan, rilis) yang merangkum pencapaian, blocker, risiko, dan saran bisnis.
5. **Role-Based Access Control (RBAC)**: Pembagian akses login untuk Admin, Project Manager (PM), dan Client.

---

## Panduan Penggunaan & Instalasi

Untuk panduan lengkap cara menjalankan aplikasi, setup Docker database, konfigurasi environment, webhook GitHub lokal, serta akun demo yang tersedia, silakan baca:

👉 **[PANDUAN_PENGGUNAAN.md](PANDUAN.md)**

---

## Lisensi
Aplikasi ini dikembangkan untuk keperluan internal BridgeOps AI.
