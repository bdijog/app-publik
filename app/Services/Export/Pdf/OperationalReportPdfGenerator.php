<?php

namespace App\Services\Export\Pdf;

use App\Enums\SampleDecision;
use App\Models\Payment;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\ServiceGroup;
use App\Models\TestReport;
use App\Models\TestRequest;
use Barryvdh\DomPDF\PDF as DomPdfWrapper;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class OperationalReportPdfGenerator
{
    /**
     * Mempersiapkan objek DomPDF untuk Laporan Ringkasan Eksekutif Operasional.
     */
    public static function preparePdf(?string $startDate = null, ?string $endDate = null): DomPdfWrapper
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->startOfMonth()->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfMonth()->endOfDay();

        // 1. Metrik Agregat Utama
        $totalRequests = TestRequest::whereBetween('created_at', [$start, $end])->count();
        $totalRevenue = (float) Payment::whereBetween('created_at', [$start, $end])->sum('amount');
        $totalSamples = Sample::whereBetween('created_at', [$start, $end])->count();
        $totalReportsIssued = TestReport::where('status', 'final')->whereBetween('issued_at', [$start, $end])->count();
        $paidRequests = TestRequest::whereBetween('created_at', [$start, $end])->where('payment_status', 'paid')->count();
        $paidRate = $totalRequests > 0 ? round(($paidRequests / $totalRequests) * 100, 1) : 100;

        // 2. Distribusi 17 Kelompok Layanan Brosur
        $services = ServiceGroup::withCount([
            'testRequests' => fn ($q) => $q->whereBetween('created_at', [$start, $end]),
        ])->get()->map(function ($grp) use ($totalRequests, $start, $end) {
            $revenue = (float) TestRequest::where('service_group_id', $grp->id)
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');
            $grp->revenue = $revenue;
            $grp->percent = $totalRequests > 0 ? round(($grp->test_requests_count / $totalRequests) * 100, 1) : 0;

            return $grp;
        })->sortByDesc('test_requests_count');

        // 3. Kepatuhan SLA (Turnaround Time)
        $requests = TestRequest::whereBetween('created_at', [$start, $end])
            ->whereNotNull('estimated_completion_date')
            ->get();
        $completedOnTime = 0;
        $overdue = 0;
        foreach ($requests as $req) {
            $estDate = Carbon::parse($req->estimated_completion_date)->endOfDay();
            if (in_array($req->status, ['completed', 'report_issued'])) {
                if ($req->updated_at->lte($estDate)) {
                    $completedOnTime++;
                } else {
                    $overdue++;
                }
            } elseif ($req->status !== 'cancelled' && now()->gt($estDate)) {
                $overdue++;
            }
        }
        $complianceRate = ($completedOnTime + $overdue) > 0 ? round(($completedOnTime / ($completedOnTime + $overdue)) * 100, 1) : 100;

        // 4. Mutu Penerimaan Sampel
        $receipts = SampleReceipt::whereBetween('received_at', [$start, $end])->get();
        $acceptedCount = $receipts->where('decision', SampleDecision::Accepted)->count();
        $rejectedCount = $receipts->where('decision', SampleDecision::Rejected)->count();
        $totalReceipts = $acceptedCount + $rejectedCount;
        $rejectionRate = $totalReceipts > 0 ? round(($rejectedCount / $totalReceipts) * 100, 1) : 0;

        $data = [
            'start' => $start,
            'end' => $end,
            'totalRequests' => $totalRequests,
            'totalRevenue' => $totalRevenue,
            'totalSamples' => $totalSamples,
            'totalReportsIssued' => $totalReportsIssued,
            'paidRequests' => $paidRequests,
            'paidRate' => $paidRate,
            'services' => $services,
            'complianceRate' => $complianceRate,
            'completedOnTime' => $completedOnTime,
            'overdue' => $overdue,
            'totalReceipts' => $totalReceipts,
            'acceptedCount' => $acceptedCount,
            'rejectedCount' => $rejectedCount,
            'rejectionRate' => $rejectionRate,
            'title' => 'Laporan Rekapitulasi Operasional Laboratorium',
        ];

        return DompdfExporter::renderView('exports.pdf.reports.operational-summary', $data, 'a4', 'landscape');
    }

    public static function stream(?string $startDate = null, ?string $endDate = null): Response
    {
        $pdf = static::preparePdf($startDate, $endDate);
        $filename = 'Laporan_Operasional_Labkesmas_'.date('Ymd').'.pdf';

        return DompdfExporter::streamPdf($pdf, $filename);
    }

    public static function download(?string $startDate = null, ?string $endDate = null): mixed
    {
        $pdf = static::preparePdf($startDate, $endDate);
        $filename = 'Laporan_Operasional_Labkesmas_'.date('Ymd').'.pdf';

        return response()->streamDownload(
            function () use ($pdf): void {
                echo $pdf->output();
            },
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
