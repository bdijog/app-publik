<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Enums\ReportStatus;
use App\Enums\SampleDecision;
use App\Enums\TestResultStatus;
use App\Filament\Pages\OperationalReports;
use App\Models\CompletionEstimate;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\LaboratoryUnit;
use App\Models\Payment;
use App\Models\ReportTemplate;
use App\Models\ResultRevision;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\SampleType;
use App\Models\ServiceGroup;
use App\Models\TestJob;
use App\Models\TestReport;
use App\Models\TestRequest;
use App\Models\TestRequestParameter;
use App\Models\TestResult;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FilamentBackOfficeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected Employee $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->admin = Employee::factory()->create([
            'is_active' => true,
        ]);

        $adminRole = Role::findByName('admin', 'employee');
        $this->admin->assignRole($adminRole);
    }

    public function test_admin_can_access_catalog_resources(): void
    {
        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/laboratory-units');
        $response->assertSuccessful();

        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/service-groups');
        $response->assertSuccessful();

        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/test-parameters');
        $response->assertSuccessful();

        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/tariffs');
        $response->assertSuccessful();

        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/completion-estimates');
        $response->assertSuccessful();

        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/sample-types');
        $response->assertSuccessful();

        $response = $this->actingAs($this->admin, 'employee')->get('/admin/catalog/service-packages');
        $response->assertSuccessful();
    }

    public function test_test_request_auto_generates_request_and_fpps_numbers(): void
    {
        $customer = Customer::factory()->create();
        $serviceGroup = ServiceGroup::factory()->create();

        $request = TestRequest::create([
            'customer_id' => $customer->id,
            'service_group_id' => $serviceGroup->id,
            'purpose' => 'Pengujian Rutin Kualitas Air Bersih',
            'status' => 'submitted',
            'payment_status' => PaymentStatus::Unpaid,
            'total_amount' => 500000,
        ]);

        $this->assertNotEmpty($request->request_number);
        $this->assertStringStartsWith('REQ-', $request->request_number);
        $this->assertNotEmpty($request->fpps_number);
        $this->assertStringStartsWith('FPPS-', $request->fpps_number);
        $this->assertDatabaseHas('status_histories', [
            'test_request_id' => $request->id,
            'status' => 'submitted',
        ]);
    }

    public function test_payment_recording_updates_request_and_calculates_sla(): void
    {
        $customer = Customer::factory()->create();
        $serviceGroup = ServiceGroup::factory()->create();

        CompletionEstimate::create([
            'service_group_id' => $serviceGroup->id,
            'days' => 5,
            'start_point' => 'payment',
            'effective_from' => now()->subDay(),
        ]);

        $request = TestRequest::create([
            'customer_id' => $customer->id,
            'service_group_id' => $serviceGroup->id,
            'purpose' => 'Pengujian Rutin Air Bersih',
            'status' => 'submitted',
            'payment_status' => PaymentStatus::Unpaid,
            'total_amount' => 350000,
        ]);

        $payment = Payment::create([
            'test_request_id' => $request->id,
            'recorded_by' => $this->admin->id,
            'amount' => 350000,
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        $request->refresh();
        $this->assertEquals(PaymentStatus::Paid, $request->payment_status);
        $this->assertNotNull($request->estimated_completion_date);
    }

    public function test_sample_receiving_verification_records_decision(): void
    {
        $customer = Customer::factory()->create();
        $serviceGroup = ServiceGroup::factory()->create();
        $sampleType = SampleType::factory()->create();

        $request = TestRequest::create([
            'customer_id' => $customer->id,
            'service_group_id' => $serviceGroup->id,
            'purpose' => 'Uji Mikrobiologi Air Minum',
            'status' => 'submitted',
            'payment_status' => PaymentStatus::Paid,
            'total_amount' => 150000,
        ]);

        $sample = Sample::create([
            'test_request_id' => $request->id,
            'sample_type_id' => $sampleType->id,
            'sample_name' => 'Air Baku Sumur Gali',
            'quantity' => 1000,
            'unit' => 'ml',
        ]);

        $this->assertNotEmpty($sample->sample_code);
        $this->assertStringStartsWith('SMP-', $sample->sample_code);

        $receipt = SampleReceipt::create([
            'sample_id' => $sample->id,
            'received_by' => $this->admin->id,
            'received_at' => now(),
            'received_volume' => 1000,
            'condition' => 'Dingin, wadah botol kaca steril utuh',
            'decision' => SampleDecision::Accepted,
        ]);

        $this->assertDatabaseHas('sample_receipts', [
            'id' => $receipt->id,
            'decision' => 'accepted',
        ]);
    }

    public function test_test_result_and_revision_audit_trail(): void
    {
        $unit = LaboratoryUnit::factory()->create();
        $sample = Sample::factory()->create();

        $job = TestJob::create([
            'laboratory_unit_id' => $unit->id,
            'sample_id' => $sample->id,
            'assigned_to' => $this->admin->id,
            'created_by' => $this->admin->id,
            'status' => 'in_progress',
        ]);

        $this->assertNotEmpty($job->job_number);
        $this->assertStringStartsWith('JOB-', $job->job_number);

        $requestParameter = TestRequestParameter::factory()->create();

        $result = TestResult::create([
            'test_job_id' => $job->id,
            'test_request_parameter_id' => $requestParameter->id,
            'analyst_id' => $this->admin->id,
            'value_numeric' => 6.5,
            'unit' => 'pH',
            'method' => 'SNI 06-6989.11-2004',
            'tested_at' => now()->format('Y-m-d'),
            'status' => TestResultStatus::Draft,
            'is_locked' => false,
        ]);

        $revision = ResultRevision::create([
            'test_result_id' => $result->id,
            'revised_by' => $this->admin->id,
            'old_value' => '6.5',
            'new_value' => '7.2',
            'reason' => 'Koreksi faktor pengenceran titrasi',
        ]);

        $this->assertDatabaseHas('result_revisions', [
            'id' => $revision->id,
            'old_value' => '6.5',
            'new_value' => '7.2',
        ]);
    }

    public function test_test_report_issuance_and_versioning(): void
    {
        $customer = Customer::factory()->create();
        $serviceGroup = ServiceGroup::factory()->create();
        $template = ReportTemplate::factory()->create(['is_active' => true]);
        $verifier = Employee::factory()->create();
        $validator = Employee::factory()->create();

        $request = TestRequest::create([
            'customer_id' => $customer->id,
            'service_group_id' => $serviceGroup->id,
            'purpose' => 'Pemeriksaan Baku Mutu Air Minum',
            'status' => 'in_progress',
            'payment_status' => PaymentStatus::Paid,
            'total_amount' => 200000,
        ]);

        $report = TestReport::create([
            'test_request_id' => $request->id,
            'report_template_id' => $template->id,
            'verifier_id' => $verifier->id,
            'validator_id' => $validator->id,
            'status' => ReportStatus::Draft,
            'considerations' => 'Memenuhi standar mutu baku air minum',
        ]);

        $this->assertNotEmpty($report->report_number);
        $this->assertStringStartsWith('LHU-', $report->report_number);
        $this->assertEquals(1, $report->version);

        // Issue Report
        $report->update([
            'status' => ReportStatus::Final,
            'issued_by' => $this->admin->id,
            'issued_at' => now(),
            'validated_on' => now()->format('Y-m-d'),
        ]);

        $this->assertEquals(ReportStatus::Final, $report->status);

        // Create revised version
        $revisedReport = TestReport::create([
            'test_request_id' => $request->id,
            'report_template_id' => $template->id,
            'parent_report_id' => $report->id,
            'verifier_id' => $verifier->id,
            'validator_id' => $validator->id,
            'version' => $report->version + 1,
            'status' => ReportStatus::Draft,
            'considerations' => 'Revisi perbaikan identitas titik koordinat',
        ]);

        $report->update(['status' => ReportStatus::Revised]);

        $this->assertEquals(2, $revisedReport->version);
        $this->assertEquals($report->id, $revisedReport->parent_report_id);
        $this->assertEquals(ReportStatus::Revised, $report->status);
    }

    public function test_operational_reports_page_is_accessible(): void
    {
        $response = $this->actingAs($this->admin, 'employee')->get('/admin/operational-reports');
        $response->assertSuccessful();

        Livewire::actingAs($this->admin, 'employee')
            ->test(OperationalReports::class)
            ->assertSuccessful()
            ->call('setPreset', '7_days')
            ->assertSet('activePreset', '7_days')
            ->assertSee('Rekapitulasi Operasional');
    }

    public function test_admin_can_access_employees_resource(): void
    {
        $response = $this->actingAs($this->admin, 'employee')->get('/admin/employees');
        $response->assertSuccessful();
    }
}
