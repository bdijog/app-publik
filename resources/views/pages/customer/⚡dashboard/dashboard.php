<?php

use App\Enums\PaymentStatus;
use App\Enums\ReportStatus;
use App\Enums\TestRequestStatus;
use App\Models\Customer;
use App\Models\TestReport;
use App\Models\TestRequest;
use App\Services\Export\Pdf\TestReportPdfGenerator;
use App\Services\Export\Pdf\TestRequestReceiptPdfGenerator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public string $activeTab = 'requests';

    public ?int $selectedRequestId = null;

    public function getCustomerProperty(): ?Customer
    {
        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        return $customer;
    }

    public function getSummaryStatsProperty(): array
    {
        $customerId = $this->customer?->id;

        if (! $customerId) {
            return [
                'total_requests' => 0,
                'active_tests' => 0,
                'pending_payments' => 0,
                'ready_reports' => 0,
            ];
        }

        return [
            'total_requests' => TestRequest::where('customer_id', $customerId)->count(),
            'active_tests' => TestRequest::where('customer_id', $customerId)
                ->whereNotIn('status', [
                    TestRequestStatus::Completed->value,
                    TestRequestStatus::Cancelled->value,
                    TestRequestStatus::ReportIssued->value,
                ])
                ->count(),
            'pending_payments' => TestRequest::where('customer_id', $customerId)
                ->where('payment_status', PaymentStatus::Unpaid->value)
                ->count(),
            'ready_reports' => TestReport::whereHas('testRequest', fn ($q) => $q->where('customer_id', $customerId))
                ->where('status', ReportStatus::Final->value)
                ->count(),
        ];
    }

    public function getLatestActiveRequestProperty(): ?TestRequest
    {
        $customerId = $this->customer?->id;

        if (! $customerId) {
            return null;
        }

        return TestRequest::where('customer_id', $customerId)
            ->with(['serviceGroup', 'samples.sampleType', 'testReports', 'statusHistories'])
            ->latest('registered_at')
            ->first();
    }

    public function getRequestsProperty(): LengthAwarePaginator
    {
        $customerId = $this->customer?->id;

        return TestRequest::where('customer_id', $customerId)
            ->with(['serviceGroup', 'samples', 'testReports', 'payments'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('fpps_number', 'ilike', "%{$this->search}%")
                        ->orWhere('request_number', 'ilike', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                if ($this->statusFilter === 'unpaid') {
                    $q->where('payment_status', PaymentStatus::Unpaid->value);
                } elseif ($this->statusFilter === 'active') {
                    $q->whereNotIn('status', [TestRequestStatus::Completed->value, TestRequestStatus::Cancelled->value]);
                } elseif ($this->statusFilter === 'completed') {
                    $q->where('status', TestRequestStatus::Completed->value);
                }
            })
            ->latest('registered_at')
            ->paginate(5);
    }

    public function getReportsProperty(): LengthAwarePaginator
    {
        $customerId = $this->customer?->id;

        return TestReport::whereHas('testRequest', fn ($q) => $q->where('customer_id', $customerId))
            ->with(['testRequest.serviceGroup'])
            ->latest('issued_at')
            ->paginate(5);
    }

    public function downloadReport(int $reportId): mixed
    {
        $customerId = $this->customer?->id;

        $report = TestReport::whereHas('testRequest', fn ($q) => $q->where('customer_id', $customerId))
            ->where('id', $reportId)
            ->first();

        if (! $report) {
            session()->flash('error', 'Dokumen LHU tidak ditemukan atau Anda tidak memiliki hak akses.');

            return null;
        }

        $isFinal = ($report->status === ReportStatus::Final || (string) $report->status?->value === 'final' || (string) $report->status === 'final');
        if (! $isFinal) {
            session()->flash('error', 'Dokumen LHU belum berstatus Final atau masih dalam proses verifikasi.');

            return null;
        }

        return TestReportPdfGenerator::download(
            $report,
            customer: $this->customer
        );
    }

    public function downloadReceipt(int $requestId): mixed
    {
        $customerId = $this->customer?->id;

        $request = TestRequest::where('customer_id', $customerId)
            ->where('id', $requestId)
            ->first();

        if (! $request) {
            session()->flash('error', 'Permohonan FPPS tidak ditemukan atau Anda tidak memiliki hak akses.');

            return null;
        }

        return TestRequestReceiptPdfGenerator::download($request);
    }

    public function render(): mixed
    {
        return view('pages::customer.⚡dashboard.dashboard');
    }
};
