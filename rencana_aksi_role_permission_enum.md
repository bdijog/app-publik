# RENCANA AKSI PEMBUATAN ROLE & PERMISSION DENGAN SPATIE LARAVEL PERMISSION DAN LARAVEL ENUMS
## Sistem Informasi Laboratorium Balai Laboratorium Kesehatan Masyarakat Ambon
### (Edisi Revisi: Penggabungan Petugas Pelayanan & Penerimaan Sampel serta Konsolidasi CRUD)

| Parameter | Keterangan |
|---|---|
| **Dokumen Sumber** | [PRD_dan_Desain_Basis_Data.md](file:///home/kurnia/HTDOCS/app-publik/PRD_dan_Desain_Basis_Data.md) (Bab 2, 3, 4, dan 6) |
| **Framework & Versi** | Laravel 13.x, PHP 8.4 (Constructor Promotion, Backed Enums), PostgreSQL 18 |
| **Paket RBAC** | `spatie/laravel-permission` v8.x |
| **Guard Target** | `employee` (Model: `App\Models\Employee`) |
| **Penyederhanaan CRUD** | Seluruh operasi CRUD (Create, Read, Update, Delete) disatukan menjadi 1 permission `manage` per modul |
| **Penggabungan Peran** | Petugas Pelayanan dan Petugas Penerimaan Sampel diintegrasikan dalam peran operasional loket layanan |
| **Status Dokumen** | Rencana Aksi Siap Eksekusi (Ready for Implementation) |

---

## 1. Landasan & Prinsip Desain

1. **Penggabungan Peran Pelayanan & Penerimaan Sampel (PRD Bab 2)**:
   - PRD Bab 2 secara eksplisit menyatakan: *"Petugas Penerimaan Sampel: Peran ini dapat digabung dengan Petugas Pelayanan bila sesuai pembagian kerja Balai."*
   - Pada operasional Balai Labkesmas Ambon, alur loket pelayanan bersifat terintegrasi: saat pemohon mendaftar (atau konfirmasi WhatsApp), petugas yang sama langsung memeriksa fisik sampel, mengecek syarat wadah/volume, mengonfirmasi pembayaran, dan menerbitkan tanda terima/FPPS.
   - Oleh karena itu, peran **`service_officer`** ditetapkan sebagai **Petugas Pelayanan & Penerimaan Sampel (Front Office Terpadu)** yang langsung memiliki kewenangan pendaftaran (`request.manage`), pembayaran (`payment.manage`), pemeriksaan sampel (`sample.manage`), dan penyerahan LHU (`report.manage`).
   - Untuk fleksibilitas organisasi, sistem tetap menyediakan peran khusus **`sample_receiver`** jika suatu saat ada staf yang *hanya* ditugaskan di pos penerimaan sampel fisik murni tanpa mengelola pendaftaran/keuangan.
2. **Konsolidasi Fitur CRUD**:
   - Seluruh operasi Create, Read, Update, Delete disatukan menjadi satu izin `manage` per entitas/fitur (seperti `catalog.manage`, `request.manage`, `payment.manage`, `sample.manage`, `job.manage`, `result.manage`, `report.manage`, `user.manage`).
3. **Pemisahan Otorisasi Khusus (ISO 17025)**:
   - Validasi hasil (`result.validate`) dan revisi hasil terkunci berjejak audit (`result.revise`) tetap dipisahkan dari input hasil analis biasa (`result.manage`) demi integritas mutu pengujian.

---

## 2. Taksonomi Peran (Roles) Sistem

| No | Peran (Role Enum) | Nama Kode (`value`) | Karakteristik & Cakupan Tanggung Jawab |
|---|---|---|---|
| 1 | **Administrator** | `admin` | Pengelola aplikasi & TI. Akses penuh (Super Admin via `Gate::before`), konfigurasi sistem, kelola pegawai, hak akses, master katalog, tarif, SLA, template LHU, dan seluruh log audit. |
| 2 | **Petugas Pelayanan & Penerimaan Sampel** *(Peran Gabungan)* | `service_officer` | **Front Office / Loket Terpadu**: Menerima pendaftaran permohonan uji, verifikasi dokumen pelanggan, konfirmasi pembayaran, **memeriksa fisik sampel dan menetapkan keputusan Terima/Tolak**, serta mencatat penyerahan LHU final. |
| 3 | **Petugas Laboratorium** | `lab_officer` | Analis pengujian dan Kepala Instalasi/Validator (Manager Lab). Mengelola antrean SPK (job), input & edit hasil uji, validasi teknis bertahap (Manager Lab & Rekomendasi), koreksi hasil berjejak audit, dan draf LHU. |
| 4 | **Pimpinan** | `leader` | Kepala Balai dan manajemen struktural. Memantau dashboard statistik, antrean uji, capaian SLA, rekapitulasi data layanan, dan ekspor laporan ke PDF/Excel (*Read-only pada data transaksi*). |
| 5 | *Petugas Penerimaan Sampel (Dedicated)* | `sample_receiver` | *Peran opsional/khusus*: Staf yang hanya bertugas di pos pemeriksaan fisik sampel tanpa mengelola pendaftaran, kasir, atau penyerahan LHU. |

---

## 3. Daftar Ringkas 13 Permission Terpadu

| No | Permission Enum Case | Nilai String | Klaster Modul | Cakupan Hak Akses (CRUD Lengkap) |
|---|---|---|---|---|
| 1 | `DASHBOARD_VIEW` | `dashboard.view` | Dashboard | Melihat antarmuka ringkasan dashboard panel & indikator layanan. |
| 2 | `USER_MANAGE` | `user.manage` | Pengguna & Akses | **CRUD Lengkap** Pegawai (`employees`), Penempatan Unit Lab, & Pengaturan Peran. |
| 3 | `CATALOG_MANAGE` | `catalog.manage` | Master Katalog | **CRUD Lengkap** Unit Lab, Kelompok Layanan, Paket, Parameter, Tarif, SLA, & Jenis Sampel. |
| 4 | `REQUEST_MANAGE` | `request.manage` | Permohonan | **CRUD Lengkap** Permohonan Pengujian (`test_requests`: pendaftaran, edit, pembatalan) & Data Pemohon (`customers`). |
| 5 | `PAYMENT_MANAGE` | `payment.manage` | Pembayaran | **CRUD Lengkap** Pembayaran biaya uji (`payments`: pencatatan & konfirmasi lunas). |
| 6 | `SAMPLE_MANAGE` | `sample.manage` | Penerimaan Sampel | **CRUD Lengkap** Sampel fisik & Penerimaan (`samples`, `sample_receipts`: cek kelaikan, Terima/Tolak). |
| 7 | `JOB_MANAGE` | `job.manage` | Distribusi SPK | **CRUD Lengkap** Penugasan perintah kerja uji lab (`test_jobs`: penugasan analis & instalasi). |
| 8 | `RESULT_MANAGE` | `result.manage` | Pengujian Lab | **CRUD Lengkap** Hasil pengujian (`test_results`: input nilai angka/teks, satuan, metode, dan edit draf). |
| 9 | `RESULT_VALIDATE` | `result.validate` | Validasi Hasil | **Otorisasi Khusus**: Validasi teknis berjenjang (Manager Lab & Rekomendasi). |
| 10 | `RESULT_REVISE` | `result.revise` | Koreksi / Audit | **Otorisasi Khusus**: Koreksi/revisi hasil uji terkunci dengan alasan tertulis wajib. |
| 11 | `REPORT_MANAGE` | `report.manage` | LHU & Penyerahan | **CRUD Lengkap** Laporan Hasil Uji (`test_reports`: draf, terbitkan final PDF, cetak, & serah terima). |
| 12 | `REPORT_EXPORT` | `report.export` | Laporan Eksekutif | Mengekspor rekapitulasi data layanan & waktu penyelesaian ke format PDF/Excel. |
| 13 | `AUDIT_VIEW` | `audit.view` | Jejak Digital | Membuka log aktivitas sistem (`activity_logs`) dan riwayat akses dokumen (`document_access_logs`). |

---

## 4. Matriks Hak Akses Peran (Role-Permission Matrix)

```
✅ = Memiliki Izin Eksplisit
⚡ = Memiliki Akses Penuh Otomatis via Gate::before (Super Admin)
❌ = Tidak Memiliki Izin
```

| Permission (CRUD Disatukan) | Nilai String | Administrator (`admin`) | Petugas Pelayanan & Sampel (`service_officer`) | Petugas Laboratorium (`lab_officer`) | Pimpinan (`leader`) | *Sample Receiver (Dedicated)* (`sample_receiver`) |
|---|---|:---:|:---:|:---:|:---:|:---:|
| **Dashboard** | `dashboard.view` | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Pengguna (CRUD)** | `user.manage` | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Katalog (CRUD)** | `catalog.manage` | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Permohonan (CRUD)** | `request.manage` | ❌ | ✅ | ❌ | *(Read via Policy)* | ❌ |
| **Pembayaran (CRUD)** | `payment.manage` | ❌ | ✅ | ❌ | ❌ | ❌ |
| **Penerimaan Sampel (CRUD)** | `sample.manage` | ❌ | **✅ (Tergabung)** | ❌ | *(Read via Policy)* | ✅ |
| **SPK / Job (CRUD)** | `job.manage` | ❌ | ❌ | ✅ | *(Read via Policy)* | ❌ |
| **Hasil Uji (CRUD)** | `result.manage` | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Validasi Hasil (ISO 17025)** | `result.validate` | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Revisi Hasil Terkunci** | `result.revise` | ❌ | ❌ | ✅ | ❌ | ❌ |
| **LHU & Penyerahan (CRUD)** | `report.manage` | ❌ | ✅ | ✅ | *(Read via Policy)* | ❌ |
| **Ekspor Rekap Laporan** | `report.export` | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Log Aktivitas & Audit** | `audit.view` | ✅ | ❌ | ❌ | ✅ | ❌ |

---

## 5. Implementasi Enum Laravel (PHP 8.4)

### 5.1 `App\Enums\RoleEnum`
File: `app/Enums/RoleEnum.php`
```php
namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case ServiceOfficer = 'service_officer';
    case LabOfficer = 'lab_officer';
    case Leader = 'leader';
    case SampleReceiver = 'sample_receiver';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator Sistem',
            self::ServiceOfficer => 'Petugas Pelayanan & Penerimaan Sampel',
            self::LabOfficer => 'Petugas Laboratorium (Analis & Validator)',
            self::Leader => 'Pimpinan / Kepala Balai',
            self::SampleReceiver => 'Petugas Penerimaan Sampel (Khusus)',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Admin => 'Pengelola sistem, konfigurasi master katalog, tarif, dan hak akses pegawai.',
            self::ServiceOfficer => 'Front Office: Mengelola pendaftaran permohonan, data pelanggan, pembayaran, kelaikan sampel fisik (Terima/Tolak), dan penyerahan LHU.',
            self::LabOfficer => 'Mengerjakan pengujian sampel, input hasil, validasi berjenjang, dan draf LHU.',
            self::Leader => 'Memantau statistik, antrean, SLA, rekapitulasi, dan log sistem (read-only).',
            self::SampleReceiver => 'Pos Khusus: Memeriksa kelaikan fisik sampel dan mencatat keputusan Terima/Tolak sampel.',
        };
    }

    /**
     * @return array<PermissionEnum>
     */
    public function defaultPermissions(): array
    {
        return match ($this) {
            self::Admin => [
                PermissionEnum::DashboardView,
                PermissionEnum::UserManage,
                PermissionEnum::CatalogManage,
                PermissionEnum::AuditView,
            ],
            // Peran Gabungan Pelayanan & Penerimaan Sampel:
            self::ServiceOfficer => [
                PermissionEnum::DashboardView,
                PermissionEnum::RequestManage,
                PermissionEnum::PaymentManage,
                PermissionEnum::SampleManage,
                PermissionEnum::ReportManage,
            ],
            self::LabOfficer => [
                PermissionEnum::DashboardView,
                PermissionEnum::JobManage,
                PermissionEnum::ResultManage,
                PermissionEnum::ResultValidate,
                PermissionEnum::ResultRevise,
                PermissionEnum::ReportManage,
            ],
            self::Leader => [
                PermissionEnum::DashboardView,
                PermissionEnum::ReportExport,
                PermissionEnum::AuditView,
            ],
            // Peran Khusus Pos Sampel:
            self::SampleReceiver => [
                PermissionEnum::DashboardView,
                PermissionEnum::SampleManage,
            ],
        };
    }
}
```

### 5.2 `App\Enums\PermissionEnum`
File: `app/Enums/PermissionEnum.php`
```php
namespace App\Enums;

enum PermissionEnum: string
{
    case DashboardView = 'dashboard.view';
    case UserManage = 'user.manage';
    case CatalogManage = 'catalog.manage';
    case RequestManage = 'request.manage';
    case PaymentManage = 'payment.manage';
    case SampleManage = 'sample.manage';
    case JobManage = 'job.manage';
    case ResultManage = 'result.manage';
    case ResultValidate = 'result.validate';
    case ResultRevise = 'result.revise';
    case ReportManage = 'report.manage';
    case ReportExport = 'report.export';
    case AuditView = 'audit.view';

    public function label(): string
    {
        return match ($this) {
            self::DashboardView => 'Melihat Dashboard Ringkasan & KPI',
            self::UserManage => 'Kelola Pengguna (CRUD Pegawai & Hak Akses)',
            self::CatalogManage => 'Kelola Master Katalog (CRUD Layanan, Paket, Parameter, Tarif, SLA, Sampel)',
            self::RequestManage => 'Kelola Permohonan (CRUD Pendaftaran, Edit, Batal, & Data Pemohon)',
            self::PaymentManage => 'Kelola Pembayaran (CRUD Pencatatan & Konfirmasi Pembayaran)',
            self::SampleManage => 'Kelola Sampel (CRUD Penerimaan Fisik & Putusan Terima/Tolak)',
            self::JobManage => 'Kelola Distribusi Pekerjaan (CRUD SPK & Penugasan Analis)',
            self::ResultManage => 'Kelola Hasil Pengujian (CRUD Input & Edit Draf Hasil Uji)',
            self::ResultValidate => 'Validasi Hasil Uji (Manager Lab & Rekomendasi)',
            self::ResultRevise => 'Koreksi / Revisi Hasil Terkunci (Audit Trail)',
            self::ReportManage => 'Kelola LHU (CRUD Draf, Terbitkan Final PDF, Cetak, & Serah Terima)',
            self::ReportExport => 'Ekspor Rekapitulasi Data Operasional (PDF / Excel)',
            self::AuditView => 'Melihat Log Aktivitas & Jejak Akses Dokumen',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::DashboardView => 'Dashboard & Analitik',
            self::UserManage => 'Pengguna & Hak Akses',
            self::CatalogManage => 'Katalog & Konfigurasi Layanan',
            self::RequestManage => 'Pendaftaran & Permohonan Uji',
            self::PaymentManage => 'Pembayaran & Keuangan',
            self::SampleManage => 'Penerimaan Sampel Fisik',
            self::JobManage => 'Distribusi Pekerjaan Uji',
            self::ResultManage,
            self::ResultValidate,
            self::ResultRevise => 'Pengujian & Validasi Hasil',
            self::ReportManage,
            self::ReportExport => 'Laporan Hasil Uji (LHU) & Rekapitulasi',
            self::AuditView => 'Audit & Jejak Digital',
        };
    }
}
```

---

## 6. Tahapan Eksekusi Teknis

1. **Fase 1: Pembuatan File Enum Laravel**
   - Buat `app/Enums/RoleEnum.php` dan `app/Enums/PermissionEnum.php` dengan peran terpadu `service_officer`.
2. **Fase 2: Konfigurasi Core Spatie & Gate Bypass**
   - Daftarkan `Gate::before` untuk Super Admin di [AppServiceProvider.php](file:///home/kurnia/HTDOCS/app-publik/app/Providers/AppServiceProvider.php).
   - Daftarkan Spatie Middleware Aliases di [bootstrap/app.php](file:///home/kurnia/HTDOCS/app-publik/bootstrap/app.php).
3. **Fase 3: Seeder Berbasis Enum**
   - Perbarui [RolePermissionSeeder.php](file:///home/kurnia/HTDOCS/app-publik/database/seeders/RolePermissionSeeder.php) menggunakan `PermissionEnum::cases()` dan `$role->defaultPermissions()`.
   - Update [EmployeeSeeder.php](file:///home/kurnia/HTDOCS/app-publik/database/seeders/EmployeeSeeder.php) agar pegawai loket/pelayanan otomatis memegang hak penerimaan sampel.
4. **Fase 4: Refactoring Policy Otorisasi**
   - Sesuaikan `TestRequestPolicy`, `CustomerPolicy`, `PaymentPolicy`, `SamplePolicy`, `TestJobPolicy`, `TestResultPolicy`, `TestReportPolicy`, dll., dengan izin `*.manage` yang bersih.
5. **Fase 5: UI Filament v5 Form & Table Roles**
   - Perbarui [RoleForm.php](file:///home/kurnia/HTDOCS/app-publik/app/Filament/Resources/Roles/Schemas/RoleForm.php) dan [PermissionsTable.php](file:///home/kurnia/HTDOCS/app-publik/app/Filament/Resources/Permissions/Tables/PermissionsTable.php).
6. **Fase 6: Testing & Standarisasi Kode**
   - Buat `tests/Feature/RolePermissionEnumTest.php` dan format menggunakan `vendor/bin/pint --dirty --format agent`.
