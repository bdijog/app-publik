<?php

namespace App\Enums;

enum TestRequestStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case AwaitingPayment = 'awaiting_payment';
    case AwaitingSample = 'awaiting_sample';
    case InProgress = 'in_progress';
    case Validated = 'validated';
    case ReportIssued = 'report_issued';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf Permohonan',
            self::Submitted => 'Menunggu Verifikasi',
            self::AwaitingPayment => 'Menunggu Pembayaran',
            self::AwaitingSample => 'Menunggu Penerimaan Sampel',
            self::InProgress => 'Sedang Pengujian',
            self::Validated => 'Hasil Tervalidasi',
            self::ReportIssued => 'LHU Terbit',
            self::Completed => 'Selesai & Diserahkan',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
