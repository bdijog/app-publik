<?php

namespace Tests\Feature;

use App\Enums\CustomerType;
use App\Enums\DocumentAccessAction;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\ReportStatus;
use App\Enums\SampleDecision;
use App\Enums\SampleStatus;
use App\Enums\TestJobStatus;
use App\Enums\TestRequestStatus;
use App\Enums\TestResultStatus;
use App\Enums\ValidationDecision;
use App\Enums\ValidationStage;
use App\Models\Customer;
use App\Models\DocumentAccessLog;
use App\Models\Employee;
use App\Models\LaboratoryUnit;
use App\Models\ReportHandover;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\ServiceGroup;
use App\Models\TestJob;
use App\Models\TestReport;
use App\Models\TestRequest;
use App\Models\TestResult;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LaboratoryDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_populates_master_and_demo_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // 1. Roles & Permissions
        $this->assertGreaterThanOrEqual(5, Role::where('guard_name', 'employee')->count());
        $this->assertDatabaseHas('roles', ['name' => 'admin', 'guard_name' => 'employee']);
        $this->assertDatabaseHas('roles', ['name' => 'service_officer', 'guard_name' => 'employee']);

        // 2. Laboratory Units & Service Groups
        $this->assertEquals(6, LaboratoryUnit::count());
        $this->assertEquals(17, ServiceGroup::count());

        // 3. Employees with Roles (Minimal 20 baris)
        $this->assertGreaterThanOrEqual(20, Employee::count());
        $admin = Employee::where('email', 'admin@labkesmas-ambon.go.id')->first();
        $this->assertNotNull($admin);
        $this->assertTrue($admin->hasRole('admin'));

        // 4. Demo Data
        $this->assertDatabaseHas('customers', ['email' => 'budi.santoso@example.com']);
        $this->assertDatabaseHas('test_requests', ['fpps_number' => 'FPPS-20261015-0001']);
        $this->assertDatabaseHas('test_reports', ['report_number' => 'LHU-2026-00088']);
    }

    public function test_customer_encrypts_phone_and_generates_phone_hash(): void
    {
        $rawPhone = '0812-3456-7890';
        $normalizedPhone = '081234567890';
        $expectedHash = hash('sha256', $normalizedPhone);

        $customer = Customer::create([
            'name' => 'John Doe',
            'customer_type' => CustomerType::Individual,
            'email' => 'john@example.com',
            'phone' => $rawPhone,
            'password' => 'secret123',
        ]);

        // Reload fresh from DB
        $customer->refresh();

        // 1. Accessor decrypts back to original phone
        $this->assertEquals($rawPhone, $customer->phone);

        // 2. phone_hash is automatically generated using normalized digits
        $this->assertEquals($expectedHash, $customer->phone_hash);

        // 3. Raw database row does NOT store plain text phone
        $rawRow = DB::table('customers')->where('id', $customer->id)->first();
        $this->assertNotEquals($rawPhone, $rawRow->phone);
        $this->assertEquals($expectedHash, $rawRow->phone_hash);
    }

    public function test_test_request_relationships_and_lifecycle(): void
    {
        $unit = LaboratoryUnit::factory()->create(['code' => 'LAB-TEST']);
        $employee = Employee::factory()->create(['laboratory_unit_id' => $unit->id]);
        $serviceGroup = ServiceGroup::factory()->create(['laboratory_unit_id' => $unit->id]);
        $customer = Customer::factory()->create();

        // Create TestRequest
        $testRequest = TestRequest::factory()->create([
            'customer_id' => $customer->id,
            'service_group_id' => $serviceGroup->id,
            'created_by' => $employee->id,
            'channel' => RegistrationChannel::Counter,
            'payment_status' => PaymentStatus::Paid,
            'status' => TestRequestStatus::InProgress,
        ]);

        // Create Sample
        $sample = Sample::factory()->create([
            'test_request_id' => $testRequest->id,
            'status' => SampleStatus::Accepted,
        ]);

        // Create Receipt
        $receipt = SampleReceipt::factory()->create([
            'sample_id' => $sample->id,
            'received_by' => $employee->id,
            'decision' => SampleDecision::Accepted,
        ]);

        // Create TestJob
        $job = TestJob::factory()->create([
            'sample_id' => $sample->id,
            'laboratory_unit_id' => $unit->id,
            'assigned_to' => $employee->id,
            'status' => TestJobStatus::Completed,
        ]);

        // Create TestResult & Validation
        $result = TestResult::factory()->create([
            'test_job_id' => $job->id,
            'analyst_id' => $employee->id,
            'status' => TestResultStatus::Validated,
            'is_locked' => true,
        ]);

        $validation = $result->validations()->create([
            'validator_id' => $employee->id,
            'stage' => ValidationStage::ManagerLab,
            'decision' => ValidationDecision::Approved,
            'decided_at' => now(),
        ]);

        // Create TestReport & Item
        $report = TestReport::factory()->create([
            'test_request_id' => $testRequest->id,
            'verifier_id' => $employee->id,
            'validator_id' => $employee->id,
            'issued_by' => $employee->id,
            'status' => ReportStatus::Final,
        ]);

        $item = $report->items()->create([
            'test_result_id' => $result->id,
            'sample_name' => $sample->sample_name,
            'sample_number' => $sample->sample_code,
            'parameter' => 'pH',
            'result' => '7.4',
        ]);

        // Create Handover
        $handover = ReportHandover::factory()->create([
            'test_report_id' => $report->id,
            'handed_by' => $employee->id,
        ]);

        // Create Access Log
        $accessLog = DocumentAccessLog::factory()->create([
            'test_report_id' => $report->id,
            'customer_id' => $customer->id,
            'action' => DocumentAccessAction::Download,
        ]);

        // Assert relationships
        $this->assertEquals($customer->id, $testRequest->customer->id);
        $this->assertEquals($employee->id, $testRequest->creator->id);
        $this->assertCount(1, $testRequest->samples);
        $this->assertEquals($receipt->id, $sample->latestReceipt->id);
        $this->assertEquals($job->id, $sample->testJobs->first()->id);
        $this->assertEquals($result->id, $job->testResults->first()->id);
        $this->assertEquals($validation->id, $result->validations->first()->id);
        $this->assertEquals($report->id, $testRequest->testReports->first()->id);
        $this->assertEquals($item->id, $report->items->first()->id);
        $this->assertEquals($handover->id, $report->handovers->first()->id);
        $this->assertEquals($accessLog->id, $report->accessLogs->first()->id);
    }
}
