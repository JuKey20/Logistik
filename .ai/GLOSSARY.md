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

Definition: Employee penyedia jasa pengiriman yang direpresentasikan aplikasi dan login memakai satu akun User.

Business meaning: Satu-satunya pengguna software. Bukan pelanggan.

System representation: Hubungan employee/User secara konseptual 1:1 untuk MVP; tidak ada personel non-login yang perlu direpresentasikan. Keputusan ini tidak otomatis memerlukan tabel/model Employee. Starter kit baru mempunyai model `User`; enum dan kolom role belum ada (DEC-005, DEC-030, DEC-031).

Related modules: Manajemen Pengguna & Hak Akses; Autentikasi Pengguna & Keamanan

## Superadmin

Definition: Role otorisasi tertinggi. **Backing value: `superadmin`.** Role ini berbeda dari `owner`.

Business meaning: Mempunyai akses penuh ke seluruh modul, data, dan aksi aplikasi. Tidak dikelola melalui ordinary application UI. Last active superadmin tidak boleh dinonaktifkan, dihapus, atau diturunkan rolenya.

System representation: Bootstrap melalui seeder dengan required environment/deployment secrets tanpa predictable fallback. Provisioning/management berikutnya memakai secure operational mechanism; emergency recovery detail belum dikunci. Seeder dan `UserRole::Superadmin` belum dibuat (DEC-004, DEC-005, DEC-030, DEC-031).

Related modules: Manajemen Pengguna & Hak Akses

## Owner

Definition: Role otorisasi internal dengan backing value `owner`, terpisah dari `superadmin`.

Business meaning: Business oversight untuk melihat history, data bisnis/operasional relevan, report, analytics, dan monitoring. Bukan administrator pengguna dan tidak mempunyai unrestricted read access ke security-sensitive/system data. Exact read access ditentukan dalam future module contract.

System representation: `UserRole::Owner` belum dibuat. Enforcement melalui Gates/Policies (DEC-005, DEC-030).

Related modules: Manajemen Pengguna & Hak Akses

## Role (`UserRole`)

Definition: Fixed set of internal roles. Closed enum, bukan tabel CRUD.

Business meaning:

* `superadmin` — role tertinggi; full application access
* `owner` — business oversight; bukan User Management administrator
* `admin` — mengelola akun `karyawan` saja, termasuk deactivate/reactivate dan internal password recovery
* `karyawan` — akun employee; self-service non-sensitive dan akses bisnis hanya pada resource yang diberikan/ditugaskan

System representation: `app/Enums/UserRole.php` (belum dibuat). Kolom `users.role`.

Role adalah konsep **otorisasi/keamanan**. Jangan memakai urutan `superadmin > owner > admin > karyawan` sebagai numeric comparison atau inheritance permission. Ability harus dinyatakan eksplisit dan ditegakkan oleh Policy (DEC-030, DEC-031).

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

## Fungsi operasional karyawan

Definition: Master data bisnis yang menjelaskan fungsi operasional yang dilakukan seorang employee; bukan tingkat otorisasi.

Business meaning: Nilai awal mencakup `sopir` dan `petugas_lapangan`; bisnis dapat menambah fungsi lain tanpa mengubah `UserRole`. Setiap employee mempunyai tepat satu fungsi operasional pada MVP dan tidak dapat memegang beberapa fungsi sekaligus.

System representation: Belum ada di kode. Bukan fixed authorization enum. Exact schema, table/field name, active state, deletion rules, dan UI master data belum diputuskan (DEC-030, DEC-031).

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
