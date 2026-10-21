<?php

namespace Tests\Feature;

use App\Enums\ReportStatus;
use App\Models\Customer;
use App\Models\SampleType;
use App\Models\ServiceGroup;
use App\Models\TestParameter;
use App\Models\TestReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicPortalAndCustomerFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_page_is_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeText('Balai Labkesmas Ambon');
        $response->assertSeeText('Layanan Pengujian Laboratorium');
    }

    public function test_katalog_dan_tarif_page_is_accessible(): void
    {
        $response = $this->get(route('katalog'));

        $response->assertStatus(200);
        $response->assertSeeText('Katalog Layanan');
        $response->assertSeeText('Semua Layanan');
    }

    public function test_pendaftaran_fpps_page_is_accessible(): void
    {
        $response = $this->get(route('pendaftaran'));

        $response->assertStatus(200);
        $response->assertSeeText('Pengajuan Permohonan Pengujian Sampel');
        $response->assertSeeText('Profil Pemohon');
    }

    public function test_lacak_sampel_page_is_accessible(): void
    {
        $response = $this->get(route('lacak'));

        $response->assertStatus(200);
        $response->assertSeeText('Lacak Status Pemeriksaan Sampel');
    }

    public function test_verifikasi_lhu_page_is_accessible(): void
    {
        $response = $this->get(route('verifikasi-lhu'));

        $response->assertStatus(200);
        $response->assertSeeText('Gerbang Pengambilan Laporan Hasil Uji (LHU) Resmi');
    }

    protected function getOrCreateTestReport(): TestReport
    {
        $report = TestReport::with('testRequest.customer')->where('status', ReportStatus::Final)->first();
        if ($report && $report->testRequest && $report->testRequest->customer) {
            return $report;
        }

        return TestReport::factory()->final()->create();
    }

    public function test_scan_lhu_page_is_accessible(): void
    {
        $report = $this->getOrCreateTestReport();

        $response = $this->get(route('scan-lhu', ['number' => $report->report_number]));

        $response->assertStatus(200);
        $response->assertSeeText('DOKUMEN RESMI TERVERIFIKASI');
    }

    public function test_customer_login_page_is_accessible(): void
    {
        $response = $this->get(route('customer.login'));

        $response->assertStatus(200);
        $response->assertSeeText('Portal Pelanggan');
    }

    public function test_customer_register_page_is_accessible(): void
    {
        $response = $this->get(route('customer.register'));

        $response->assertStatus(200);
        $response->assertSeeText('Daftar Akun Pelanggan');
    }

    public function test_unauthenticated_customer_redirected_from_dashboard(): void
    {
        $response = $this->get(route('customer.dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_customer_can_access_dashboard(): void
    {
        $customer = Customer::first() ?? Customer::create([
            'name' => 'Testing Customer',
            'customer_type' => 'individual',
            'email' => 'test_cust_unique@example.com',
            'phone' => '081234567899',
            'address' => 'Jl. Pattimura No. 1, Ambon',
            'password' => 'password',
        ]);

        $response = $this->actingAs($customer, 'customer')->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText('Riwayat Pengujian Sampel Mandiri');
    }

    public function test_customer_can_print_and_download_their_own_lhu(): void
    {
        $report = $this->getOrCreateTestReport();
        $customer = $report->testRequest->customer;

        $responsePrint = $this->actingAs($customer, 'customer')->get(route('customer.reports.print', $report->id));
        $responsePrint->assertStatus(200);
        $responsePrint->assertHeader('content-type', 'application/pdf');

        $responseDownload = $this->actingAs($customer, 'customer')->get(route('customer.reports.download', $report->id));
        $responseDownload->assertStatus(200);
        $responseDownload->assertHeader('content-type', 'application/pdf');
    }

    public function test_customer_cannot_access_other_customers_lhu(): void
    {
        $report = $this->getOrCreateTestReport();
        $otherCustomer = Customer::factory()->create();

        $response = $this->actingAs($otherCustomer, 'customer')->get(route('customer.reports.print', $report->id));
        $response->assertStatus(403);
    }

    public function test_livewire_customer_dashboard_download_report_method(): void
    {
        $report = $this->getOrCreateTestReport();
        $customer = $report->testRequest->customer;

        Livewire::actingAs($customer, 'customer')
            ->test('pages::customer.dashboard')
            ->call('downloadReport', $report->id)
            ->assertFileDownloaded();
    }

    public function test_livewire_beranda_fpps_search(): void
    {
        Livewire::test('pages::beranda')
            ->set('fppsQuery', 'FPPS-20261015-0001')
            ->call('searchFpps')
            ->assertRedirect(route('lacak', ['fpps' => 'FPPS-20261015-0001']));
    }

    public function test_livewire_katalog_parameter_toggle_and_reset(): void
    {
        Livewire::test('pages::katalog-tarif')
            ->call('toggleParameter', 1)
            ->assertSet('selectedParameters', [1])
            ->call('resetSelection')
            ->assertSet('selectedParameters', []);
    }

    public function test_livewire_katalog_category_filter_by_code(): void
    {
        Livewire::test('pages::katalog-tarif')
            ->call('selectCategory', 'AIR_KOLAM_RENANG')
            ->assertSet('category', 'AIR_KOLAM_RENANG')
            ->assertStatus(200);
    }

    public function test_pendaftaran_with_string_category_param(): void
    {
        $response = $this->get(route('pendaftaran', ['category' => 'AIR_KOLAM_RENANG']));

        $response->assertStatus(200);
    }

    public function test_livewire_verifikasi_lhu_flow(): void
    {
        $report = $this->getOrCreateTestReport();
        $testRequest = $report->testRequest;
        $customer = $testRequest->customer;

        Livewire::test('pages::verifikasi-lhu')
            ->set('fpps', $testRequest->fpps_number)
            ->set('phone', $customer->phone)
            ->call('verify')
            ->assertSet('verified', true)
            ->assertSeeText('Otentikasi Berhasil');
    }

    public function test_livewire_customer_registration_flow(): void
    {
        $uniqueEmail = 'cust_'.uniqid().'@example.com';

        Livewire::test('pages::customer.register')
            ->set('name', 'Pendaftar Baru')
            ->set('customer_type', 'individual')
            ->set('email', $uniqueEmail)
            ->set('phone', '081299998888')
            ->set('address', 'Jl. Mardika, Ambon')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('customer.dashboard'));

        $this->assertDatabaseHas('customers', [
            'email' => $uniqueEmail,
        ]);
    }

    public function test_livewire_customer_login_flow(): void
    {
        $customer = Customer::where('email', 'budi.santoso@example.com')->first();
        if (! $customer) {
            $customer = Customer::factory()->create([
                'email' => 'budi.santoso@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        Livewire::test('pages::customer.login')
            ->set('email', $customer->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect(route('customer.dashboard'));
    }

    public function test_livewire_pendaftaran_fpps_wizard_submission(): void
    {
        $group = ServiceGroup::first() ?? ServiceGroup::factory()->create();
        $param = TestParameter::where('service_group_id', $group->id)->first()
            ?? TestParameter::factory()->create(['service_group_id' => $group->id, 'is_active' => true]);

        $uniqueEmail = 'wizard_'.uniqid().'@example.com';

        $sampleType = SampleType::first() ?? SampleType::factory()->create();

        Livewire::test('pages::pendaftaran-fpps')
            ->set('applicant_type', 'individual')
            ->set('customer_name', 'Pemohon Wizard')
            ->set('customer_phone', '081233334444')
            ->set('customer_email', $uniqueEmail)
            ->set('customer_address', 'Jl. AY Patty, Ambon')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->set('service_group_id', $group->id)
            ->set('selected_parameters', [$param->id])
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->set('sample_type_id', $sampleType->id)
            ->set('sample_name', 'Air Sumur Uji')
            ->set('sampling_location', 'Desa Poka')
            ->set('sampling_date', now()->format('Y-m-d'))
            ->call('nextStep')
            ->assertSet('currentStep', 4)
            ->set('agree_terms', true)
            ->call('submitRegistration')
            ->assertSet('currentStep', 5);

        $this->assertDatabaseHas('test_requests', [
            'sampling_location' => 'Desa Poka',
        ]);
    }
}
