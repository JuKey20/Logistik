# Glossary

> Shared language for this project. Fill business terms during adaptation. Do not keep terms from another product.

---

# Rules

* Use one term per concept. Do not invent synonyms that change meaning.
* Add a term here before spreading it through code and UI.
* If a term changes, update this file first.

---

# Engineering terms

These names are used by the baseline. Keep them stable.

| Term | Meaning |
| --- | --- |
| Action | A use-case class that orchestrates one business operation |
| Repository | Persistence abstraction — **not used** in this project (DEC-023) |
| DTO | Optional typed data between layers |
| Policy | Server-side authorization decision for an action on a resource |
| Product capability | A business/menu area in `PROJECT.md`. Not a code package (DEC-029) |
| Fixed Domain Value | Closed set owned by the application (enum), not end-user CRUD |
| Dynamic Business Data | Records users create, update, or delete at runtime |
| Envelope | JSON `success` / `message` / `data` / `meta` / `errors` — **not adopted** for MVP Inertia pages |
| Feature | Frontend module folder for one UI domain |
| Documentation Harvest | Updating docs while implementation context is still complete |
| Production-like | Staging or any environment whose data must be treated as real |

---

# Business terms

## Jasa pengiriman

Definition: Layanan memindahkan barang dari satu lokasi ke lokasi lain dengan kendaraan milik penyedia jasa.

Business meaning: Inti produk. Dipakai ketika pelanggan tidak punya cukup kendaraan sendiri.

System representation: Belum ada di kode (starter kit; hanya `User` dan halaman auth/dashboard).

Related modules: Manajemen Pengiriman; Penjadwalan & Penugasan; Harga & Pembayaran; Validasi & Bukti Kerja

## Pelanggan korporat

Definition: Perusahaan yang menyewa jasa pengiriman untuk memindahkan barang ke perusahaan lain atau ke cabangnya.

Business meaning: Segmen B2B. Pada scope MVP saat ini, data pelanggan adalah direktori referensi, bukan CRM penuh, dan bukan klasifikasi permanen (DEC-029).

System representation: Belum ada di kode.

**Unresolved:** Customer vs pengirim vs penerima vs banyak lokasi — Decision Required. Jangan samakan Customer dengan sender/destination. Jangan buat entity Address tanpa keputusan produk.

Related modules: Manajemen Pelanggan (CRM)

## Pelanggan individu

Definition: Orang biasa yang menyewa jasa pengiriman, misalnya untuk pindah rumah.

Business meaning: Segmen B2C. Scope MVP sama dengan pelanggan korporat: direktori referensi, bukan klasifikasi permanen.

System representation: Belum ada di kode.

Related modules: Manajemen Pelanggan (CRM)

## Tim internal

Definition: Staf penyedia jasa pengiriman yang login dan memakai aplikasi.

Business meaning: Satu-satunya pengguna software. Bukan pelanggan.

System representation: Starter kit memakai model `User` (`app/Models/User.php`). Peran: `users.role` di-cast ke `UserRole` (`owner`, `admin`, `sopir`, `petugas_lapangan`) + Gates/Policies (DEC-005, DEC-009). Kelas enum belum ada di source.

Related modules: Manajemen Pengguna & Hak Akses; Autentikasi Pengguna & Keamanan

## Superadmin / Owner

Definition: Role akun pertama. **Backing value: `owner`.** "Superadmin" adalah makna bisnis, bukan nilai yang disimpan.

Business meaning: Bootstrap akses. Oracle / pemilik sistem. Membuat pengguna internal berikutnya lewat modul Manajemen Pengguna.

System representation: Seeder (belum ada). `UserRole::Owner`. Gates/Policies (DEC-005, DEC-009).

Related modules: Manajemen Pengguna & Hak Akses

## Role (`UserRole`)

Definition: Fixed set of internal roles. Closed enum, bukan tabel CRUD.

Business meaning:

* `owner` — superadmin / seeder
* `admin` — staf kantor
* `sopir` — pengemudi
* `petugas_lapangan` — petugas di lapangan

System representation: `app/Enums/UserRole.php` (belum dibuat). Kolom `users.role`.

Related modules: Manajemen Pengguna & Hak Akses

## Status pengiriman (`ShipmentStatus`)

Definition: Siklus hidup order pengiriman (MVP).

Business meaning: Transisi **linier** (DEC-015):

`menunggu_persetujuan` → `menunggu_penjadwalan` → `terjadwal` → `dalam_perjalanan` → `proses_pemindahan` → `dalam_pengiriman` → `menunggu_validasi` → `selesai`

Cabang: dari status **non-terminal** → `dibatalkan`.

**Terminal (immutable):** `selesai`, `dibatalkan` — tidak bisa dikembalikan ke status sebelumnya.

System representation: `app/Enums/ShipmentStatus.php` (belum dibuat). Mesin transisi di backend, bukan di klien.

Related modules: Manajemen Pengiriman; Penjadwalan & Penugasan; Validasi & Bukti Kerja

## Status pembayaran (`PaymentStatus`)

Definition: `belum_dibayar` | `sudah_dibayar`. Label UI untuk `sudah_dibayar` adalah **Lunas** (bukan nilai yang disimpan).

Business meaning: Satu arah `belum_dibayar` → `sudah_dibayar` (DEC-016). `sudah_dibayar` terminal. **Tidak** mengikuti pembatalan pengiriman.

Ke `sudah_dibayar` wajib: metode, nominal aktual **>= harga kesepakatan**, user/admin yang mengubah. Kekurangan bayar **ditolak**. Tip = kelebihan bayar (tidak negatif) (DEC-018).

System representation: `app/Enums/PaymentStatus.php` (belum dibuat). Validasi di backend.

Related modules: Harga & Pembayaran

## Harga kesepakatan perusahaan

Definition: Harga yang disepakati perusahaan untuk pengiriman tersebut (bukan uang yang diserahkan pelanggan di lapangan).

Business meaning: Dasar hitung bonus/tip tim.

System representation: Belum ada di kode. Bagian data pengiriman/billing. Tipe: integer Rupiah / `BIGINT` (DEC-019).

Related modules: Harga & Pembayaran; Manajemen Pengiriman

## Nominal aktual

Definition: Uang yang diserahkan pelanggan, dicatat saat status menjadi `sudah_dibayar`.

Business meaning: Bisa berbeda dari harga kesepakatan.

System representation: Wajib di record pembayaran (DEC-016, DEC-017). Tipe: integer Rupiah / `BIGINT` (DEC-019).

Related modules: Harga & Pembayaran

## Bonus / tip tim

Definition: Bagian untuk tim dari **selisih** nominal aktual vs harga kesepakatan perusahaan.

Business meaning: Bukan cicilan. Satu nilai per pembayaran (1:1 dengan pengiriman).

System representation: Field tersimpan **atau** kalkulasi `nominal_aktual − harga_kesepakatan` ketika aktual >= kesepakatan. **Tidak boleh negatif.** Jika aktual < kesepakatan, transisi lunas ditolak (DEC-018). Tipe: integer Rupiah / `BIGINT` (DEC-019).

Related modules: Harga & Pembayaran; Penugasan & Ketersediaan

## Log aktivitas (Audit Trail)

Definition: Jejak perubahan penting (status pengiriman, harga, penugasan tim, dan sejenisnya).

Business meaning: Modul Log Aktivitas. Bukan notifikasi. Bukan event bus.

System representation: Ditulis **eksplisit dan sinkron di Action** yang melakukan perubahan (DEC-024). Bukan Domain Event, Listener, atau Observer.

Related modules: Log Aktivitas

## Metode pembayaran (`PaymentMethod`)

Definition: `tunai` | `transfer`.

System representation: `app/Enums/PaymentMethod.php` (belum dibuat).

Related modules: Harga & Pembayaran

## Tim lapangan

Definition: Staf internal di lapangan (bukan pelanggan) yang mengerjakan tugas pengiriman.

Business meaning: Memakai dasbor **Tugas Saya** lewat browser HP. Bukan pengguna aplikasi native. Role enum: `sopir`, `petugas_lapangan`.

System representation: Belum ada di kode.

Related modules: Dasbor Tugas Saya; Penugasan & Ketersediaan; Validasi & Bukti Kerja; Antarmuka Responsif

## Bukti kerja (Proof of Delivery)

Definition: Foto atau dokumen tanda terima yang membuktikan pengiriman selesai.

Business meaning: Validasi penyelesaian tugas lapangan.

System representation: Belum ada di kode. Foto dikompres di klien dengan `browser-image-compression` (DEC-002) sebelum unggah. Disk **private** saja; akses lewat route auth + Policy atau temporary signed URL (DEC-013, DEC-014). Bukan URL publik.

Related modules: Validasi & Bukti Kerja; Manajemen Penyimpanan Berkas

## WhatsApp (manual)

Definition: Komunikasi ke pelanggan atau pihak luar di luar aplikasi.

Business meaning: Bukan kanal sistem. Tidak ada pengiriman otomatis di MVP (DEC-010). Operasional internal: pull di UI.

System representation: Tidak ada.

Related modules: — (di luar sistem)

## Cabang

Definition: Lokasi lain milik perusahaan pelanggan yang dapat menjadi tujuan pengiriman.

Business meaning: Tujuan B2B selain perusahaan lain. Apakah cabang tersimpan sebagai relasi Customer, field pada pengiriman, atau entity terpisah **belum dikunci**.

System representation: Belum ada di kode.

Related modules: Manajemen Pelanggan (CRM); Manajemen Pengiriman

---

# Related Documents

* [PROJECT.md](./PROJECT.md)
* [architecture/README.md](./architecture/README.md)
* [DECISION_LOG.md](./DECISION_LOG.md)
