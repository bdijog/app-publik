<?php

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

    /**
     * @return array<string, array<self>>
     */
    public static function grouped(): array
    {
        $groups = [];
        foreach (self::cases() as $permission) {
            $groups[$permission->group()][] = $permission;
        }

        return $groups;
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
