<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Enums\ReportStatus;
use App\Enums\SampleDecision;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\ServiceGroup;
use App\Models\TestReport;
use App\Models\TestReportItem;
use App\Models\TestRequest;
use App\Services\Export\Excel\OperationalReportExcelExporter;
use App\Services\Export\Excel\SpreadsheetExporter;
use App\Services\Export\Pdf\DompdfExporter;
use App\Services\Export\Pdf\OperationalReportPdfGenerator;
use App\Services\Export\Pdf\PaymentReceiptPdfGenerator;
use App\Services\Export\Pdf\SampleRejectionPdfGenerator;
use App\Services\Export\Pdf\TestReportPdfGenerator;
use App\Services\Export\Pdf\TestRequestReceiptPdfGenerator;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class DataExportTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $employee;

    protected Customer $customer;

    protected TestRequest $testRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = Employee::factory()->create([
            'email' => 'petugas@labkesmas.go.id',
            'name' => 'Petugas Laboratorium',
        ]);

        $this->customer = Customer::factory()->create([
            'name' => 'PT Sumber Sehat Maluku',
            'phone' => '081234567890',
            'phone_hash' => Customer::hashPhone('081234567890'),
        ]);

        $serviceGroup = ServiceGroup::factory()->create([
            'name' => 'Pemeriksaan Air Minum',
            'code' => 'AM',
        ]);

        $this->testRequest = TestRequest::factory()->create([
            'customer_id' => $this->customer->id,
            'service_group_id' => $serviceGroup->id,
            'fpps_number' => 'FPPS-202610-0099',
            'total_amount' => 500000,
            'payment_status' => PaymentStatus::Paid,
        ]);
    }

    public function test_operational_report_excel_exporter_generates_streamed_response(): void
    {
        $response = OperationalReportExcelExporter::export(
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        );

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            (string) $response->headers->get('content-type')
        );
    }

    public function test_operational_report_pdf_generator_generates_pdf_response(): void
    {
        $response = OperationalReportPdfGenerator::download(
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        );

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    public function test_test_report_pdf_generator_creates_valid_pdf_and_logs_access(): void
    {
        $report = TestReport::factory()->create([
            'test_request_id' => $this->testRequest->id,
            'report_number' => 'LHU-2026-00099',
            'status' => ReportStatus::Final,
            'issued_by' => $this->employee->id,
            'issued_at' => now(),
        ]);

        TestReportItem::factory()->create([
            'test_report_id' => $report->id,
            'parameter' => 'pH Air',
            'unit' => 'pH Unit',
            'result' => '7.2',
            'threshold' => '6.5 - 8.5',
            'method' => 'SNI 06-6989.11-2004',
        ]);

        $response = TestReportPdfGenerator::download($report, employee: $this->employee);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringStartsWith('%PDF-', $content);

        $this->assertDatabaseHas('document_access_logs', [
            'test_report_id' => $report->id,
            'employee_id' => $this->employee->id,
            'action' => 'download',
        ]);
    }

    public function test_fpps_receipt_pdf_generator_creates_valid_pdf(): void
    {
        Sample::factory()->create([
            'test_request_id' => $this->testRequest->id,
            'sample_code' => 'SMP-202610-0099',
            'sample_name' => 'Air Bersih Kran Dapur',
        ]);

        $response = TestRequestReceiptPdfGenerator::download($this->testRequest);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    public function test_payment_receipt_pdf_generator_creates_valid_pdf_with_terbilang(): void
    {
        $payment = Payment::factory()->create([
            'test_request_id' => $this->testRequest->id,
            'amount' => 1500000,
            'status' => PaymentStatus::Paid,
            'recorded_by' => $this->employee->id,
        ]);

        $terbilang = PaymentReceiptPdfGenerator::terbilang(1500000);
        $this->assertEquals('Satu Juta Lima Ratus Ribu', $terbilang);

        $response = PaymentReceiptPdfGenerator::download($payment);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    public function test_sample_rejection_bap_pdf_generator_creates_valid_pdf(): void
    {
        $sample = Sample::factory()->create([
            'test_request_id' => $this->testRequest->id,
            'sample_code' => 'SMP-202610-0088',
            'sample_name' => 'Air Limbah Rumah Sakit',
        ]);

        $receipt = SampleReceipt::factory()->create([
            'sample_id' => $sample->id,
            'received_by' => $this->employee->id,
            'decision' => SampleDecision::Rejected,
            'rejection_reason' => 'Botol kemasan sampel pecah saat tiba di loket.',
            'follow_up' => 'Dilakukan resampling ulang oleh pemohon.',
        ]);

        $response = SampleRejectionPdfGenerator::download($receipt);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertStringStartsWith('%PDF-', $content);
    }

    public function test_dompdf_exporter_generates_qr_code_data_uri(): void
    {
        $dataUri = DompdfExporter::generateQrCodeDataUri('https://labkesmasambon.kemkes.go.id/verifikasi/TEST-123');

        $this->assertNotEmpty($dataUri);
        $this->assertStringStartsWith('data:image/png;base64,', $dataUri);
    }

    public function test_public_customer_verification_downloads_final_lhu(): void
    {
        $report = TestReport::factory()->create([
            'test_request_id' => $this->testRequest->id,
            'report_number' => 'LHU-2026-00055',
            'status' => ReportStatus::Final,
            'issued_by' => $this->employee->id,
            'issued_at' => now(),
        ]);

        Livewire::test('pages::verifikasi-lhu', [
            'fpps' => $this->testRequest->fpps_number,
            'phone' => '081234567890',
        ])
            ->call('verify')
            ->assertSet('verified', true)
            ->call('downloadReport')
            ->assertFileDownloaded('LHU_LHU-2026-00055.pdf');

        $this->assertDatabaseHas('document_access_logs', [
            'test_report_id' => $report->id,
            'customer_id' => $this->customer->id,
            'action' => 'download',
        ]);
    }

    public function test_spreadsheet_exporter_adds_logo_drawing_to_worksheet(): void
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $drawing = SpreadsheetExporter::addHeaderLogo($sheet, 'K1', 38);

        $this->assertNotNull($drawing);
        $this->assertCount(1, $sheet->getDrawingCollection());
        $this->assertEquals('K1', $drawing->getCoordinates());
        $this->assertEquals(38, $drawing->getHeight());
        $this->assertFileExists($drawing->getPath());
    }

    public function test_filament_panel_has_brand_logo_configured(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertNotNull($panel->getBrandLogo());
        $this->assertStringContainsString('logo-labkesmas-ambon.png', (string) $panel->getBrandLogo());
        $this->assertNotNull($panel->getDarkModeBrandLogo());
        $this->assertStringContainsString('logo-labkesmas-ambon-dark.png', (string) $panel->getDarkModeBrandLogo());
        $this->assertNotNull($panel->getFavicon());
    }

    public function test_public_portal_renders_brand_logo(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('logo-labkesmas-ambon.png');
        $response->assertSee('logo-labkesmas-ambon-dark.png');
        $response->assertSee('logo-labkesmas-icon.png');
    }
}
