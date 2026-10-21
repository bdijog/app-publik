<?php

use App\Models\Payment;
use App\Models\SampleReceipt;
use App\Models\TestJob;
use App\Models\TestReport;
use App\Models\TestRequest;
use App\Services\Export\Excel\OperationalReportExcelExporter;
use App\Services\Export\Pdf\OperationalReportPdfGenerator;
use App\Services\Export\Pdf\PaymentReceiptPdfGenerator;
use App\Services\Export\Pdf\SampleRejectionPdfGenerator;
use App\Services\Export\Pdf\TestReportPdfGenerator;
use App\Services\Export\Pdf\TestRequestReceiptPdfGenerator;
use App\Services\Export\Pdf\WorkOrderPdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Balai Labkesmas Ambon Public Portal & Customer Dashboard
|--------------------------------------------------------------------------
*/

// Public Pages (Livewire 4 Multi-File Components)
Route::livewire('/', 'pages::beranda')->name('home');
Route::livewire('/katalog-dan-tarif', 'pages::katalog-tarif')->name('katalog');
Route::livewire('/pendaftaran-fpps', 'pages::pendaftaran-fpps')->name('pendaftaran');
Route::livewire('/lacak-sampel', 'pages::lacak-sampel')->name('lacak');
Route::get('/lacak-status', fn (Request $request) => redirect()->route('lacak', $request->query()));
Route::livewire('/verifikasi-lhu', 'pages::verifikasi-lhu')->name('verifikasi-lhu');
Route::livewire('/lhu/verify/{number?}', 'pages::scan-lhu')->name('scan-lhu');
Route::get('/verifikasi-lhu/{number}', fn (string $number) => redirect()->route('scan-lhu', ['number' => $number]));

// Customer Guest Routes
Route::middleware('guest:customer')->group(function () {
    Route::livewire('/customer/register', 'pages::customer.register')->name('customer.register');
    Route::livewire('/customer/login', 'pages::customer.login')->name('customer.login');
    Route::get('/register', fn () => redirect()->route('customer.register'))->name('register');
    Route::get('/login', fn () => redirect()->route('customer.login'))->name('login');
});

// Customer Authenticated Routes
Route::middleware('auth:customer')->group(function () {
    Route::livewire('/customer/dashboard', 'pages::customer.dashboard')->name('customer.dashboard');

    Route::get('/customer/reports/{report}/download', function (TestReport $report) {
        if ($report->testRequest?->customer_id !== auth('customer')->id()) {
            abort(403, 'Akses dokumen tidak diizinkan.');
        }

        return TestReportPdfGenerator::download(
            $report,
            customer: auth('customer')->user()
        );
    })->name('customer.reports.download');

    Route::get('/customer/reports/{report}/print', function (TestReport $report) {
        if ($report->testRequest?->customer_id !== auth('customer')->id()) {
            abort(403, 'Akses dokumen tidak diizinkan.');
        }

        return TestReportPdfGenerator::stream(
            $report,
            customer: auth('customer')->user()
        );
    })->name('customer.reports.print');

    Route::get('/customer/requests/{testRequest}/receipt', function (TestRequest $testRequest) {
        if ($testRequest->customer_id !== auth('customer')->id()) {
            abort(403, 'Akses dokumen tidak diizinkan.');
        }

        return TestRequestReceiptPdfGenerator::download($testRequest);
    })->name('customer.requests.receipt');

    Route::post('/customer/logout', function (Request $request) {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    })->name('customer.logout');
});

// Employee Export & Document Print Routes
Route::middleware('auth:employee')->prefix('admin/exports')->group(function () {
    Route::get('/operational-reports/excel', function (Request $request) {
        return OperationalReportExcelExporter::export(
            $request->query('start_date'),
            $request->query('end_date')
        );
    })->name('operational.export.excel');

    Route::get('/operational-reports/pdf', function (Request $request) {
        return OperationalReportPdfGenerator::download(
            $request->query('start_date'),
            $request->query('end_date')
        );
    })->name('operational.export.pdf');

    Route::get('/lhu/{report}/download', function (TestReport $report) {
        return TestReportPdfGenerator::download(
            $report,
            employee: auth('employee')->user()
        );
    })->name('reports.lhu.download');

    Route::get('/lhu/{report}/print', function (TestReport $report) {
        return TestReportPdfGenerator::stream(
            $report,
            employee: auth('employee')->user()
        );
    })->name('reports.lhu.print');

    Route::get('/requests/{testRequest}/receipt', function (TestRequest $testRequest) {
        return TestRequestReceiptPdfGenerator::stream($testRequest);
    })->name('requests.receipt.print');

    Route::get('/payments/{payment}/receipt', function (Payment $payment) {
        return PaymentReceiptPdfGenerator::stream($payment);
    })->name('payments.receipt.print');

    Route::get('/sample-receipts/{receipt}/bap', function (SampleReceipt $receipt) {
        return SampleRejectionPdfGenerator::stream($receipt);
    })->name('samples.receipt.bap');

    Route::get('/jobs/{testJob}/spk', function (TestJob $testJob) {
        return WorkOrderPdfGenerator::stream($testJob);
    })->name('jobs.spk.print');
});
