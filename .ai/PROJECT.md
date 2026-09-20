# Project

> Project context. Fill every placeholder from the real product. Do not leave baseline examples as facts.

## Adopted Baseline

| Field | Value |
| --- | --- |
| Baseline version | `1.0.0` |
| Adopted on | `2026-09-01` (ISO 8601 date) |

**Immutable (DEC-012):** kedua nilai di atas adalah metadata adopsi. AI **tidak boleh** mengubahnya otomatis. Ubah hanya jika Oracle memberi instruksi eksplisit untuk **baseline upgrade**.

Do not treat a newer baseline release as an automatic overwrite of this file.

---

## Project Name

Logistik

## Domain

Manajemen logistik, fokus **jasa pengiriman barang**. Mencakup pengiriman B2B (perusahaan ke perusahaan lain atau ke cabangnya) dan B2C (individu, misalnya pindah rumah).

## Purpose

Sistem operasional internal untuk mengelola pengiriman barang: dari permintaan pelanggan sampai barang sampai tujuan. Dipakai hanya oleh tim internal penyedia jasa.

## Target Users

Hanya **tim internal** penyedia jasa pengiriman:

* **Superadmin** (`superadmin`) — role otorisasi tertinggi, terpisah, dengan akses penuh ke seluruh modul, data, dan aksi aplikasi
* **Owner** (`owner`) — role otorisasi tersendiri di bawah `superadmin`; hak akses detail belum dikunci
* **Admin** (`admin`) — role otorisasi tersendiri; hak akses detail belum dikunci
* **Karyawan** (`karyawan`) — role dasar pegawai internal; hak akses dan scope data belum dikunci

`sopir` dan `petugas_lapangan` adalah **klasifikasi operasional tenaga kerja**, bukan role otorisasi. Keduanya dapat berkaitan dengan dasbor **Tugas Saya** melalui browser HP, tetapi pengaruh klasifikasi terhadap akses tugas belum dikunci (DEC-030).

Pelanggan korporat dan pelanggan individu tidak login dan tidak memakai aplikasi ini.

## Modules

Dikonfirmasi untuk **produk** (capability / menu). Belum diimplementasi di kode (starter kit: auth/dashboard/`User` saja).

**Product capabilities are not architectural bounded contexts or code packages** (DEC-029). Do not create `app/Modules/*` per row. Do not delete a capability because the code groups differently.

### Utama (product capabilities)

| Modul | Fungsi |
| --- | --- |
| Manajemen Pengguna & Hak Akses | User + kolom `role` (string) + Laravel Gates/Policies (DEC-005). Bukan Spatie |
| Manajemen Pelanggan (CRM) | Data pelanggan korporat dan individu. Nama “CRM” = direktori pelanggan di MVP, bukan pipeline/scoring |
| Manajemen Armada | Data kendaraan yang dapat di-assign. Bukan servis/BBM/GPS di MVP |
| Manajemen Pengiriman | Order / delivery management |
| Penjadwalan & Penugasan | Scheduling & dispatch |
| Harga & Pembayaran | 1:1; lunas hanya jika aktual >= kesepakatan; tip = kelebihan (DEC-016–018) |
| Validasi & Bukti Kerja | Proof of delivery |
| Dashboard & Laporan | Operational lists / counts; bukan platform analitik |
| Log Aktivitas | Audit trail: tulis **sinkron di Action**, bukan Event/Listener (DEC-024). UI global boleh menyusul; riwayat pada pengiriman cukup untuk e2e |
| Dasbor Tugas Saya | Antrian kerja tim lapangan |
| Penugasan & Ketersediaan | Assignment. Aturan **ketersediaan** (kalender, overlap, dll.) **belum dikunci** — Decision Required sebelum membangun kalender |

### Pendukung

| Modul | Fungsi |
| --- | --- |
| Autentikasi Pengguna & Keamanan | Login, logout, manajemen sesi saja (DEC-004). Bukan registrasi, reset password, verifikasi email, 2FA, atau passkeys |
| Manajemen Penyimpanan Berkas | Unggahan via `Storage` facade; MVP disk `local` / `public` (env). Bukan S3/GCS di MVP (DEC-013) |
| Antarmuka Responsif | Mobile-web; tidak ada aplikasi Android/iOS terpisah di MVP |
| Ekspor Data | `maatwebsite/excel`; generate di backend; **sinkron** (direct download). Bukan queue/job (DEC-020) |

### Implementation grouping (MVP)

Code may group around **User**, **Customer**, **Vehicle**, and **Shipment workflow**. Payment, assignment, POD, scheduling, audit, report, and My Tasks may be implemented as part of that shipment workflow without removing the product capabilities above.

Pada scope MVP saat ini, Customer dan Vehicle diperlakukan sebagai data referensi karena belum mempunyai lifecycle dan invariant yang cukup kompleks untuk boundary sendiri. Itu **bukan** klasifikasi permanen.

---

## Unresolved product and access data (Decision Required)

Terkunci: pelanggan korporat/individu; cabang sebagai contoh tujuan B2B; pengiriman punya lokasi jemput/tujuan dalam narasi bisnis.

**Belum dikunci** (jangan diisi diam-diam di schema):

* hubungan Customer vs pengirim vs penerima
* apakah banyak alamat milik pelanggan atau tercatat pada pengiriman
* entity Address terpisah
* aturan ketersediaan sopir/kendaraan (overlap, kalender)
* apakah satu karyawan hanya memiliki satu klasifikasi operasional atau dapat memiliki beberapa sekaligus
* apakah setiap tenaga operasional wajib memiliki akun login
* permission dan scope data eksplisit untuk `owner`, `admin`, dan `karyawan`
* pengaruh klasifikasi operasional terhadap shipment, assignment, dan **Tugas Saya**
* aturan create/manage account antar-role, deactivate/delete, dan perlindungan `superadmin`/`owner`
* alur bootstrap credential dan pemulihan password internal

Jangan membuat abstraction Address. Jangan menyamakan Customer dengan sender dan destination tanpa keputusan produk.

## Business Context

Pemilik produk menjalankan jasa pengiriman. Pelanggan memakai jasa ini ketika tidak punya cukup kendaraan sendiri.

Contoh yang sudah dikonfirmasi:

* Perusahaan A perlu memindahkan barang ke Perusahaan B, atau ke cabang Perusahaan A.
* Orang biasa perlu memindahkan barang, misalnya pindah rumah.

Aplikasi ini adalah sistem **internal** untuk bisnis jasa pengiriman tersebut. Pelanggan tidak punya akun atau portal.

---

## Out of Scope

Sudah dikonfirmasi:

* Portal atau akun untuk pelanggan (korporat/individu)
* Aplikasi native Android/iOS di fase MVP (tim lapangan memakai web responsif)
* Registrasi publik, reset password self-serve, verifikasi email, 2FA, dan passkeys (DEC-004)
* Notifikasi otomatis (email / WhatsApp API / push). Komunikasi luar sistem: WhatsApp **manual**. Internal: pull di UI (DEC-010)
* Dwibahasa / language switcher (DEC-025)
* Repository layer, Domain Events/Listeners sebagai default workflow, Queue Worker (DEC-021–024). Event/queue **deferred** sampai side effect async nyata (DEC-029) — bukan larangan seumur hidup produk

Fase interogasi baseline **ditutup** 2026-09-02 (DEC-028). Kontrak role kemudian direvisi oleh requirement owner/client pada 2026-09-20 (DEC-030); pertanyaan akses yang tercantum di atas tetap terbuka. Panduan implementasi: [`knowledge/mvp-lock.md`](./knowledge/mvp-lock.md).

---

## Success Metrics

v1 berhasil jika tim internal dapat menyelesaikan **satu pengiriman end-to-end di sistem**: order → jadwal/penugasan → bukti kerja → tagihan.

Target angka (on-time %, volume, dll.) belum ditetapkan.

## Non-Functional Requirements

Sudah dikonfirmasi:

* UI web sepenuhnya responsif; dasbor tim lapangan ringan di browser mobile
* Unggahan bukti kerja (foto/dokumen) dengan optimasi ukuran agar tidak membebani storage
* **Kompresi gambar di frontend** dengan `browser-image-compression` sebelum unggah foto bukti pengiriman (belum terpasang di lockfile)
* Autentikasi wajib: login, logout, manajemen sesi. Bukan reset password / 2FA / passkeys / registrasi (DEC-004)
* Penyimpanan berkas MVP: disk `local` (dan `public` bila berkas boleh publik). Implementasi wajib `Storage` + config/env; **dilarang** hardcode nama disk (DEC-013)
* Bukti pengiriman **hanya** disk private. View/download MVP default: route auth + Policy + stream. Temporary signed URL diizinkan DEC-014 tetapi bukan jalur default. **Bukan** URL publik (DEC-014)
* Kolom uang: Rupiah integer; skema `BIGINT` atau `DECIMAL(p,0)`; **bukan** float (DEC-019)
* Ekspor `.xlsx` / `.csv`: `maatwebsite/excel`, **direct download sinkron**; bukan queue, bukan download center (DEC-020)
* MVP **tanpa** Queue Worker / `ShouldQueue`. Tabel `jobs` tetap ada (DEC-021)
* Copy & pesan sistem: **Bahasa Indonesia saja**; `APP_LOCALE` / fallback `id` (DEC-025)
* Zona waktu **Asia/Jakarta** sebagai SST (DB, jadwal, audit, ekspor, UI) — bukan UTC (DEC-026)
* Nama tampilan aplikasi: **Logistik** (`APP_NAME`, DEC-027)

S3/GCS bukan runtime MVP; migrasi nanti lewat env.

---

## Related Documents

* [README.md](./README.md)
* [TECH_STACK.md](./TECH_STACK.md)
* [architecture/README.md](./architecture/README.md)
* [RULES.md](./RULES.md)
* [GLOSSARY.md](./GLOSSARY.md)
* [DECISION_LOG.md](./DECISION_LOG.md)
* [knowledge/mvp-lock.md](./knowledge/mvp-lock.md)
* [modules/README.md](./modules/README.md)
* [architecture/README.md](./architecture/README.md)

---

# Guiding Principle

Business needs drive architecture, implementation, and documentation. If a business fact is not written here or in `modules/`, it is not a fact — ask.
