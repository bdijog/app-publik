<?php

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
            self::SampleReceiver => 'Petugas Penerimaan Sampel (Pos Khusus)',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Admin => 'Pengelola sistem, konfigurasi master katalog, tarif, dan hak akses pegawai.',
            self::ServiceOfficer => 'Front Office: Mengelola pendaftaran permohonan, data pelanggan, pembayaran, kelaikan sampel fisik (Terima/Tolak), dan penyerahan LHU.',
            self::LabOfficer => 'Mengerjakan pengujian sampel, input hasil, validasi teknis berjenjang, dan draf LHU.',
            self::Leader => 'Memantau statistik, antrean, SLA, rekapitulasi, dan log sistem (read-only).',
            self::SampleReceiver => 'Pos Khusus: Memeriksa kelaikan fisik sampel dan menetapkan keputusan Terima/Tolak sampel.',
        };
    }

    /**
     * @return array<PermissionEnum>
     */
    public function defaultPermissions(): array
    {
        return match ($this) {
            self::Admin => PermissionEnum::cases(),
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
            self::SampleReceiver => [
                PermissionEnum::DashboardView,
                PermissionEnum::SampleManage,
            ],
        };
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
