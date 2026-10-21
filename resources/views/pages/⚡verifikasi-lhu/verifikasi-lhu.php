<?php

use App\Enums\DocumentAccessAction;
use App\Enums\ReportStatus;
use App\Models\Customer;
use App\Models\DocumentAccessLog;
use App\Models\TestReport;
use App\Models\TestRequest;
use App\Services\Export\Pdf\TestReportPdfGenerator;
use App\Services\Export\Pdf\TestRequestReceiptPdfGenerator;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    #[Url]
    public string $fpps = '';

    public string $phone = '';

    public bool $verified = false;

    public string $errorMessage = '';

    public ?int $testRequestId = null;

    public ?int $testReportId = null;

    public function mount(): void
    {
        if (auth('customer')->check() && empty($this->phone)) {
            $this->phone = (string) (auth('customer')->user()->phone ?? '');
        }

        if (! empty($this->fpps) && ! empty($this->phone)) {
            $this->verify();
        }
    }

    public function verify(): void
    {
        $this->errorMessage = '';
        $this->verified = false;
        $this->testRequestId = null;
        $this->testReportId = null;

        $this->validate([
            'fpps' => 'required|string',
            'phone' => 'required|string|min:8',
        ], [
            'fpps.required' => 'Nomor FPPS wajib diisi.',
            'phone.required' => 'Nomor Handphone/WhatsApp wajib diisi.',
            'phone.min' => 'Nomor Handphone minimal 8 digit.',
        ]);

        $request = TestRequest::with(['customer', 'testReports', 'serviceGroup', 'samples.sampleType', 'parameters.testParameter'])
            ->where('fpps_number', trim($this->fpps))
            ->orWhere('request_number', trim($this->fpps))
            ->first();

        if (! $request) {
            $this->errorMessage = 'Nomor FPPS tidak ditemukan dalam sistem kami. Pastikan nomor yang dimasukkan sudah benar.';

            return;
        }

        $customer = $request->customer;
        if (! $customer) {
            $this->errorMessage = 'Data pemohon untuk FPPS ini tidak valid.';

            return;
        }

        // Check phone hash or normalized number matching
        $inputHash = Customer::hashPhone($this->phone);
        $inputNormalized = preg_replace('/\D/', '', $this->phone);
        $customerNormalized = preg_replace('/\D/', '', (string) $customer->phone);

        $matches = ($customer->phone_hash && $customer->phone_hash === $inputHash)
            || ($customerNormalized && $customerNormalized === $inputNormalized);

        if (! $matches) {
            $this->errorMessage = 'Verifikasi Gagal: Nomor Telepon/WhatsApp yang Anda masukkan tidak cocok dengan data pendaftar FPPS ini.';

            return;
        }

        $this->verified = true;
        $this->testRequestId = $request->id;

        $report = $request->testReports()->latest()->first();
        if ($report) {
            $this->testReportId = $report->id;

            DocumentAccessLog::create([
                'test_report_id' => $report->id,
                'customer_id' => $customer->id,
                'action' => DocumentAccessAction::View,
                'ip_address' => request()->ip(),
                'accessed_at' => now(),
            ]);
        }
    }

    public function downloadReport(): mixed
    {
        if ($this->testReportId && $this->verified) {
            $report = TestReport::find($this->testReportId);
            if ($report && ($report->status === ReportStatus::Final || $report->status === 'final')) {
                return TestReportPdfGenerator::download(
                    $report,
                    customer: $report->testRequest?->customer
                );
            }
        }

        return null;
    }

    public function downloadReceipt(): mixed
    {
        if ($this->testRequestId && $this->verified) {
            $request = TestRequest::find($this->testRequestId);
            if ($request) {
                return TestRequestReceiptPdfGenerator::download($request);
            }
        }

        return null;
    }

    public function render(): mixed
    {
        $testRequest = $this->testRequestId ? TestRequest::with(['customer', 'serviceGroup', 'samples.sampleType', 'testReports'])->find($this->testRequestId) : null;
        $testReport = $this->testReportId ? TestReport::find($this->testReportId) : null;

        return view('pages::⚡verifikasi-lhu.verifikasi-lhu', [
            'testRequest' => $testRequest,
            'testReport' => $testReport,
        ]);
    }
};
