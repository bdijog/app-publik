<?php

namespace Tests\Feature;

use App\Enums\DocumentAccessAction;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\ReportStatus;
use App\Enums\RoleEnum;
use App\Enums\TestRequestStatus;
use App\Filament\Widgets\Admin\DocumentAccessAuditTableWidget;
use App\Filament\Widgets\Admin\SecurityAlertsWatchlistWidget;
use App\Filament\Widgets\Admin\SystemHealthStatsWidget;
use App\Filament\Widgets\Lab\LabWorkstationStatsWidget;
use App\Filament\Widgets\Lab\ResultValidationQueueTableWidget;
use App\Filament\Widgets\Lab\ReturnedResultsAlertTableWidget;
use App\Filament\Widgets\Lab\TestJobWorkloadChartWidget;
use App\Filament\Widgets\Leader\LeaderStatsOverviewWidget;
use App\Filament\Widgets\Leader\ServiceDistributionChartWidget;
use App\Filament\Widgets\Leader\SlaComplianceTrendChartWidget;
use App\Filament\Widgets\Leader\SlaOverdueWatchlistTableWidget;
use App\Filament\Widgets\Sample\IncomingSampleQueueTableWidget;
use App\Filament\Widgets\Sample\SampleReceptionStatsWidget;
use App\Filament\Widgets\Sample\SampleRejectionReasonChartWidget;
use App\Filament\Widgets\Service\PendingPaymentsTableWidget;
use App\Filament\Widgets\Service\ReadyForHandoverTableWidget;
use App\Filament\Widgets\Service\ServiceOfficerStatsWidget;
use App\Filament\Widgets\Service\UnverifiedRequestsTableWidget;
use App\Models\Customer;
use App\Models\DocumentAccessLog;
use App\Models\Employee;
use App\Models\ServiceGroup;
use App\Models\TestReport;
use App\Models\TestRequest;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardRoleWidgetsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_leader_widgets_authorization(): void
    {
        /** @var Employee $leader */
        $leader = Employee::factory()->create(['is_active' => true]);
        $leader->assignRole(RoleEnum::Leader->value);

        /** @var Employee $other */
        $other = Employee::factory()->create(['is_active' => true]);
        $other->assignRole(RoleEnum::SampleReceiver->value);

        $this->actingAs($leader, 'employee');
        $this->assertTrue(LeaderStatsOverviewWidget::canView());
        $this->assertTrue(ServiceDistributionChartWidget::canView());
        $this->assertTrue(SlaComplianceTrendChartWidget::canView());
        $this->assertTrue(SlaOverdueWatchlistTableWidget::canView());

        $this->actingAs($other, 'employee');
        $this->assertFalse(LeaderStatsOverviewWidget::canView());
        $this->assertFalse(ServiceDistributionChartWidget::canView());
        $this->assertFalse(SlaComplianceTrendChartWidget::canView());
        $this->assertFalse(SlaOverdueWatchlistTableWidget::canView());
    }

    public function test_service_officer_widgets_authorization(): void
    {
        /** @var Employee $officer */
        $officer = Employee::factory()->create(['is_active' => true]);
        $officer->assignRole(RoleEnum::ServiceOfficer->value);

        /** @var Employee $leader */
        $leader = Employee::factory()->create(['is_active' => true]);
        $leader->assignRole(RoleEnum::Leader->value);

        $this->actingAs($officer, 'employee');
        $this->assertTrue(ServiceOfficerStatsWidget::canView());
        $this->assertTrue(UnverifiedRequestsTableWidget::canView());
        $this->assertTrue(PendingPaymentsTableWidget::canView());
        $this->assertTrue(ReadyForHandoverTableWidget::canView());

        $this->actingAs($leader, 'employee');
        $this->assertFalse(ServiceOfficerStatsWidget::canView());
        $this->assertFalse(UnverifiedRequestsTableWidget::canView());
        $this->assertFalse(PendingPaymentsTableWidget::canView());
        $this->assertFalse(ReadyForHandoverTableWidget::canView());
    }

    public function test_sample_receiver_widgets_authorization(): void
    {
        /** @var Employee $receiver */
        $receiver = Employee::factory()->create(['is_active' => true]);
        $receiver->assignRole(RoleEnum::SampleReceiver->value);

        /** @var Employee $lab */
        $lab = Employee::factory()->create(['is_active' => true]);
        $lab->assignRole(RoleEnum::LabOfficer->value);

        $this->actingAs($receiver, 'employee');
        $this->assertTrue(SampleReceptionStatsWidget::canView());
        $this->assertTrue(IncomingSampleQueueTableWidget::canView());
        $this->assertTrue(SampleRejectionReasonChartWidget::canView());

        $this->actingAs($lab, 'employee');
        $this->assertFalse(SampleReceptionStatsWidget::canView());
        $this->assertFalse(IncomingSampleQueueTableWidget::canView());
        $this->assertFalse(SampleRejectionReasonChartWidget::canView());
    }

    public function test_lab_officer_widgets_authorization(): void
    {
        /** @var Employee $lab */
        $lab = Employee::factory()->create(['is_active' => true]);
        $lab->assignRole(RoleEnum::LabOfficer->value);

        /** @var Employee $leader */
        $leader = Employee::factory()->create(['is_active' => true]);
        $leader->assignRole(RoleEnum::Leader->value);

        $this->actingAs($lab, 'employee');
        $this->assertTrue(LabWorkstationStatsWidget::canView());
        $this->assertTrue(TestJobWorkloadChartWidget::canView());
        $this->assertTrue(ResultValidationQueueTableWidget::canView());

        $this->actingAs($leader, 'employee');
        $this->assertFalse(LabWorkstationStatsWidget::canView());
        $this->assertFalse(TestJobWorkloadChartWidget::canView());
        $this->assertFalse(ResultValidationQueueTableWidget::canView());
    }

    public function test_admin_has_access_to_all_widgets(): void
    {
        /** @var Employee $admin */
        $admin = Employee::factory()->create(['is_active' => true]);
        $admin->assignRole(RoleEnum::Admin->value);

        $this->actingAs($admin, 'employee');
        $this->assertTrue(LeaderStatsOverviewWidget::canView());
        $this->assertTrue(ServiceOfficerStatsWidget::canView());
        $this->assertTrue(SampleReceptionStatsWidget::canView());
        $this->assertTrue(LabWorkstationStatsWidget::canView());
        $this->assertTrue(SystemHealthStatsWidget::canView());
        $this->assertTrue(DocumentAccessAuditTableWidget::canView());
    }

    public function test_widgets_render_without_errors(): void
    {
        /** @var Employee $admin */
        $admin = Employee::factory()->create(['is_active' => true]);
        $admin->assignRole(RoleEnum::Admin->value);

        $customer = Customer::factory()->create();
        $group = ServiceGroup::first() ?? ServiceGroup::factory()->create();

        $request = TestRequest::factory()->create([
            'customer_id' => $customer->id,
            'service_group_id' => $group->id,
            'channel' => RegistrationChannel::Online,
            'status' => TestRequestStatus::Submitted->value,
            'payment_status' => PaymentStatus::Unpaid->value,
        ]);

        $report = TestReport::factory()->create([
            'test_request_id' => $request->id,
            'status' => ReportStatus::Final->value,
        ]);

        DocumentAccessLog::create([
            'test_report_id' => $report->id,
            'customer_id' => $customer->id,
            'action' => DocumentAccessAction::Download,
            'ip_address' => '127.0.0.1',
            'accessed_at' => now(),
        ]);

        $this->actingAs($admin, 'employee');

        $widgetClasses = [
            LeaderStatsOverviewWidget::class,
            ServiceDistributionChartWidget::class,
            SlaComplianceTrendChartWidget::class,
            SlaOverdueWatchlistTableWidget::class,
            ServiceOfficerStatsWidget::class,
            UnverifiedRequestsTableWidget::class,
            PendingPaymentsTableWidget::class,
            ReadyForHandoverTableWidget::class,
            SampleReceptionStatsWidget::class,
            IncomingSampleQueueTableWidget::class,
            SampleRejectionReasonChartWidget::class,
            LabWorkstationStatsWidget::class,
            TestJobWorkloadChartWidget::class,
            ResultValidationQueueTableWidget::class,
            ReturnedResultsAlertTableWidget::class,
            SystemHealthStatsWidget::class,
            SecurityAlertsWatchlistWidget::class,
            DocumentAccessAuditTableWidget::class,
        ];

        foreach ($widgetClasses as $widgetClass) {
            Livewire::actingAs($admin, 'employee')
                ->test($widgetClass)
                ->assertSuccessful();
        }
    }

    public function test_customer_dashboard_livewire_component_and_report_download(): void
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create([
            'name' => 'RSUD Test Ambon',
            'phone' => '081234567890',
        ]);

        $group = ServiceGroup::first() ?? ServiceGroup::factory()->create();

        $request = TestRequest::factory()->create([
            'customer_id' => $customer->id,
            'service_group_id' => $group->id,
            'total_amount' => 500000,
            'payment_status' => PaymentStatus::Unpaid->value,
            'status' => TestRequestStatus::Submitted->value,
        ]);

        $report = TestReport::factory()->create([
            'test_request_id' => $request->id,
            'status' => ReportStatus::Final->value,
            'report_number' => 'LHU-2026-TEST01',
        ]);

        // Autentikasi customer
        $this->actingAs($customer, 'customer');

        // Uji Livewire component customer dashboard
        Livewire::actingAs($customer, 'customer')
            ->test('pages::customer.dashboard')
            ->assertSee('RSUD Test Ambon')
            ->assertSee($request->fpps_number)
            ->call('downloadReport', $report->id);

        // Verifikasi dokumen access log tercatat otomatis
        $this->assertDatabaseHas('document_access_logs', [
            'test_report_id' => $report->id,
            'customer_id' => $customer->id,
            'action' => 'download',
        ]);
    }
}
