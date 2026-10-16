<?php

namespace Database\Seeders;

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
use App\Models\Payment;
use App\Models\ReportHandover;
use App\Models\ReportTemplate;
use App\Models\ResultRevision;
use App\Models\ResultValidation;
use App\Models\Sample;
use App\Models\SampleReceipt;
use App\Models\SampleType;
use App\Models\ServiceGroup;
use App\Models\ServicePackage;
use App\Models\StatusHistory;
use App\Models\TestJob;
use App\Models\TestParameter;
use App\Models\TestReport;
use App\Models\TestReportItem;
use App\Models\TestRequest;
use App\Models\TestRequestParameter;
use App\Models\TestResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Membuat 5 skenario transaksi realistis mencakup seluruh siklus hidup layanan:
     * 1. Selesai & LHU Final (Air Minum - Online).
     * 2. Sedang Diuji di Laboratorium (Makanan - Pendaftaran WhatsApp).
     * 3. Sampel Ditolak karena wadah pecah (Swab Alat - Loket Pelayanan).
     * 4. Menunggu Pembayaran (Penelitian Mahasiswa S1).
     * 5. Selesai dengan Koreksi/Revisi Hasil Uji & Jejak Audit (Air Limbah Faskes).
     */
    public function run(): void
    {
        $pelayanan = Employee::where('email', 'pelayanan@labkesmas-ambon.go.id')->first();
        $penerima = Employee::where('email', 'penerima@labkesmas-ambon.go.id')->first();
        $analis = Employee::where('email', 'analis.kimia@labkesmas-ambon.go.id')->first();
        $validator = Employee::where('email', 'validator.lab@labkesmas-ambon.go.id')->first();
        $template = ReportTemplate::first();

        // -------------------------------------------------------------
        // MASTER PELANGGAN CONTOH
        // -------------------------------------------------------------
        $customerOnline = Customer::updateOrCreate(
            ['email' => 'budi.santoso@example.com'],
            [
                'name' => 'Budi Santoso',
                'organization_name' => 'PT Maluku Aqua Prima',
                'customer_type' => CustomerType::Organization,
                'phone' => '081234567890',
                'phone_hash' => Customer::hashPhone('081234567890'),
                'address' => 'Jl. Pattimura No. 45, Ambon',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $customerWa = Customer::updateOrCreate(
            ['phone_hash' => Customer::hashPhone('085298765432')],
            [
                'name' => 'dr. Sarah Wattimena',
                'organization_name' => 'RSUD Dr. M. Haulussy Ambon',
                'customer_type' => CustomerType::HealthFacility,
                'email' => 'sarah.haulussy@example.com',
                'phone' => '085298765432',
                'address' => 'Jl. Dr. Kayadoe, Kudamati, Ambon',
                'password' => null, // Terdaftar via WhatsApp tanpa password web
                'email_verified_at' => null,
            ]
        );

        $customerLoket = Customer::updateOrCreate(
            ['phone_hash' => Customer::hashPhone('082199887766')],
            [
                'name' => 'Sanitarian Puskesmas Rijali',
                'organization_name' => 'Puskesmas Rijali Kota Ambon',
                'customer_type' => CustomerType::HealthFacility,
                'email' => null,
                'phone' => '082199887766',
                'address' => 'Jl. Kakialy, Sirimau, Ambon',
                'password' => null,
                'email_verified_at' => null,
            ]
        );

        $customerMahasiswa = Customer::updateOrCreate(
            ['email' => 'maria.unpatti@example.com'],
            [
                'name' => 'Maria Martha Latuconsina',
                'organization_name' => 'Universitas Pattimura (Jurusan Kimia MIPA)',
                'customer_type' => CustomerType::Individual,
                'phone' => '081344556677',
                'phone_hash' => Customer::hashPhone('081344556677'),
                'address' => 'Kampus Poka, Ambon',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $customerDlh = Customer::updateOrCreate(
            ['email' => 'wasdal.dlh@ambon.go.id'],
            [
                'name' => 'Bidang Pengendalian Pencemaran DLH',
                'organization_name' => 'Dinas Lingkungan Hidup Kota Ambon',
                'customer_type' => CustomerType::Organization,
                'phone' => '081147008899',
                'phone_hash' => Customer::hashPhone('081147008899'),
                'address' => 'Jl. Sultan Hairun No. 1, Ambon',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // -------------------------------------------------------------
        // KASUS 1: Selesai Penuh, LHU Terbit & Diserahkan (Air Minum)
        // -------------------------------------------------------------
        $airMinumGroup = ServiceGroup::where('code', 'AIR_MINUM')->first();
        $airMinumSampleType = SampleType::where('service_group_id', $airMinumGroup?->id)->first();
        $airMinumParams = $airMinumGroup?->testParameters()->limit(4)->get();

        if ($airMinumGroup && $airMinumParams && $airMinumParams->isNotEmpty()) {
            $req1 = TestRequest::updateOrCreate(
                ['fpps_number' => 'FPPS-20261015-0001'],
                [
                    'request_number' => 'REQ-20261015-0001',
                    'customer_id' => $customerOnline->id,
                    'service_group_id' => $airMinumGroup->id,
                    'created_by' => null,
                    'purpose' => 'Sertifikasi Kelaikan Higiene Sanitasi Air Minum Depot',
                    'channel' => RegistrationChannel::Online,
                    'sampling_location' => 'Depot Air Minum Lateri',
                    'sampling_date' => now()->subDays(5)->format('Y-m-d'),
                    'notes' => 'Permohonan prioritas audit Dinkes',
                    'total_amount' => 503000.00,
                    'payment_status' => PaymentStatus::Paid,
                    'status' => TestRequestStatus::Completed,
                    'estimated_completion_date' => now()->addDays(5)->format('Y-m-d'),
                    'registered_at' => now()->subDays(5),
                ]
            );

            $reqParameters = [];
            foreach ($airMinumParams as $param) {
                $reqParameters[] = TestRequestParameter::updateOrCreate(
                    [
                        'test_request_id' => $req1->id,
                        'test_parameter_id' => $param->id,
                    ],
                    [
                        'price' => 125000.00,
                    ]
                );
            }

            Payment::updateOrCreate(
                ['test_request_id' => $req1->id],
                [
                    'recorded_by' => $pelayanan?->id ?? 1,
                    'amount' => 503000.00,
                    'status' => PaymentStatus::Paid,
                    'paid_at' => now()->subDays(5),
                ]
            );

            $sample1 = Sample::updateOrCreate(
                ['sample_code' => 'SMP-20261015-001'],
                [
                    'test_request_id' => $req1->id,
                    'sample_type_id' => $airMinumSampleType?->id ?? 1,
                    'sample_name' => 'Air Baku Depot Lateri',
                    'quantity' => 1500.0,
                    'unit' => 'ml',
                    'collected_at' => now()->subDays(5),
                    'status' => SampleStatus::Accepted,
                ]
            );

            SampleReceipt::updateOrCreate(
                ['sample_id' => $sample1->id],
                [
                    'received_by' => $penerima?->id ?? 1,
                    'received_at' => now()->subDays(4),
                    'received_volume' => 1500.0,
                    'condition' => 'Segel utuh, suhu coolbox 4°C, wadah kaca steril',
                    'decision' => SampleDecision::Accepted,
                    'follow_up' => 'Diteruskan ke Instalasi Kimia Lingkungan',
                ]
            );

            $job1 = TestJob::updateOrCreate(
                ['job_number' => 'JOB-20261015-001'],
                [
                    'sample_id' => $sample1->id,
                    'laboratory_unit_id' => $airMinumGroup->laboratory_unit_id,
                    'assigned_to' => $analis?->id,
                    'status' => TestJobStatus::Completed,
                    'started_at' => now()->subDays(4),
                    'completed_at' => now()->subDays(2),
                ]
            );

            $testResults = [];
            foreach ($reqParameters as $index => $reqP) {
                $valNumeric = ($index === 0) ? 7.2 : ($index * 1.5);
                $res = TestResult::updateOrCreate(
                    [
                        'test_job_id' => $job1->id,
                        'test_request_parameter_id' => $reqP->id,
                    ],
                    [
                        'analyst_id' => $analis?->id ?? 1,
                        'value_numeric' => $valNumeric,
                        'value_text' => null,
                        'unit' => $reqP->testParameter->unit,
                        'method' => $reqP->testParameter->method,
                        'tested_at' => now()->subDays(3)->format('Y-m-d'),
                        'status' => TestResultStatus::Validated,
                        'is_locked' => true,
                    ]
                );

                ResultValidation::updateOrCreate(
                    ['test_result_id' => $res->id],
                    [
                        'validator_id' => $validator?->id ?? 1,
                        'stage' => ValidationStage::ManagerLab,
                        'decision' => ValidationDecision::Approved,
                        'notes' => 'Hasil telah diverifikasi dan memenuhi standar baku mutu.',
                        'decided_at' => now()->subDays(2),
                    ]
                );

                $testResults[] = $res;
            }

            $report = TestReport::updateOrCreate(
                ['report_number' => 'LHU-2026-00088'],
                [
                    'test_request_id' => $req1->id,
                    'report_template_id' => $template?->id ?? 1,
                    'verifier_id' => $analis?->id ?? 1,
                    'validator_id' => $validator?->id ?? 1,
                    'issued_by' => $pelayanan?->id ?? 1,
                    'version' => 1,
                    'status' => ReportStatus::Final,
                    'considerations' => 'Berdasarkan hasil pengujian parameter fisik, kimia, dan mikrobiologi, sampel memenuhi syarat kualitas air minum sesuai Permenkes No. 2 Tahun 2023.',
                    'file_path' => 'reports/lhu-2026-00088.pdf',
                    'validated_on' => now()->subDays(1)->format('Y-m-d'),
                    'issued_at' => now()->subDays(1),
                ]
            );

            foreach ($testResults as $tRes) {
                TestReportItem::updateOrCreate(
                    [
                        'test_report_id' => $report->id,
                        'test_result_id' => $tRes->id,
                    ],
                    [
                        'sample_name' => $sample1->sample_name,
                        'sample_number' => $sample1->sample_code,
                        'parameter' => $tRes->testRequestParameter->testParameter->name,
                        'unit' => $tRes->unit,
                        'result' => (string) $tRes->value_numeric,
                        'method' => $tRes->method,
                        'threshold' => '6.5 - 8.5',
                    ]
                );
            }

            ReportHandover::updateOrCreate(
                ['test_report_id' => $report->id],
                [
                    'handed_by' => $pelayanan?->id ?? 1,
                    'received_by_name' => 'Budi Santoso (Pemohon)',
                    'handed_at' => now()->subHours(6),
                    'notes' => 'LHU fisik asli diserahkan di loket beserta lembar tanda terima.',
                ]
            );

            DocumentAccessLog::create([
                'test_report_id' => $report->id,
                'customer_id' => $customerOnline->id,
                'employee_id' => null,
                'action' => DocumentAccessAction::Download,
                'ip_address' => '114.125.10.22',
                'accessed_at' => now()->subHours(4),
            ]);

            $statuses = [
                ['status' => TestRequestStatus::Submitted, 'note' => 'Pendaftaran online berhasil diajukan oleh pemohon.'],
                ['status' => TestRequestStatus::AwaitingPayment, 'note' => 'Verifikasi administrasi selesai. Menunggu konfirmasi pembayaran.'],
                ['status' => TestRequestStatus::AwaitingSample, 'note' => 'Pembayaran lunas terverifikasi. Menunggu penyerahan sampel.'],
                ['status' => TestRequestStatus::InProgress, 'note' => 'Sampel diterima dalam kondisi baik. Pengujian sedang berlangsung.'],
                ['status' => TestRequestStatus::Validated, 'note' => 'Seluruh parameter selesai diuji dan disetujui Validator Lab.'],
                ['status' => TestRequestStatus::ReportIssued, 'note' => 'Laporan Hasil Uji (LHU) final telah terbit dan siap diunduh.'],
                ['status' => TestRequestStatus::Completed, 'note' => 'LHU fisik telah diserahkan kepada pemohon di loket pelayanan.'],
            ];

            foreach ($statuses as $item) {
                StatusHistory::create([
                    'test_request_id' => $req1->id,
                    'changed_by' => $pelayanan?->id,
                    'status' => $item['status'],
                    'note' => $item['note'],
                    'created_at' => now()->subDays(5),
                ]);
            }
        }

        // -------------------------------------------------------------
        // KASUS 2: Sedang Diuji di Laboratorium (Makanan - WhatsApp)
        // -------------------------------------------------------------
        $makananGroup = ServiceGroup::where('code', 'MAKANAN')->first();
        $makananSampleType = SampleType::where('service_group_id', $makananGroup?->id)->first();
        if ($makananGroup) {
            $req2 = TestRequest::updateOrCreate(
                ['fpps_number' => 'FPPS-20261016-0002'],
                [
                    'request_number' => 'REQ-20261016-0002',
                    'customer_id' => $customerWa->id,
                    'service_group_id' => $makananGroup->id,
                    'created_by' => $pelayanan?->id,
                    'purpose' => 'Skrining Bahan Berbahaya Makanan Pasien Rawat Inap',
                    'channel' => RegistrationChannel::WhatsApp,
                    'sampling_location' => 'Instalasi Gizi RSUD Haulussy',
                    'sampling_date' => now()->subDay()->format('Y-m-d'),
                    'notes' => 'Pendaftaran dibantu oleh petugas pelayanan via WhatsApp.',
                    'total_amount' => 680000.00,
                    'payment_status' => PaymentStatus::Paid,
                    'status' => TestRequestStatus::InProgress,
                    'estimated_completion_date' => now()->addDays(7)->format('Y-m-d'),
                    'registered_at' => now()->subDay(),
                ]
            );

            Payment::updateOrCreate(
                ['test_request_id' => $req2->id],
                [
                    'recorded_by' => $pelayanan?->id ?? 1,
                    'amount' => 680000.00,
                    'status' => PaymentStatus::Paid,
                    'paid_at' => now()->subDay(),
                ]
            );

            $sample2 = Sample::updateOrCreate(
                ['sample_code' => 'SMP-20261016-002'],
                [
                    'test_request_id' => $req2->id,
                    'sample_type_id' => $makananSampleType?->id ?? 1,
                    'sample_name' => 'Lauk Ikan Saus Tomat & Sayur Sup',
                    'quantity' => 300.0,
                    'unit' => 'gram',
                    'collected_at' => now()->subDay(),
                    'status' => SampleStatus::Accepted,
                ]
            );

            SampleReceipt::updateOrCreate(
                ['sample_id' => $sample2->id],
                [
                    'received_by' => $penerima?->id ?? 1,
                    'received_at' => now()->subHours(18),
                    'received_volume' => 300.0,
                    'condition' => 'Kemasan tertutup rapat dalam coolbox suhu 5°C',
                    'decision' => SampleDecision::Accepted,
                    'follow_up' => 'Diserahkan ke analis mikrobiologi pangan',
                ]
            );

            TestJob::updateOrCreate(
                ['job_number' => 'JOB-20261016-002'],
                [
                    'sample_id' => $sample2->id,
                    'laboratory_unit_id' => $makananGroup->laboratory_unit_id,
                    'assigned_to' => $analis?->id,
                    'status' => TestJobStatus::InProgress,
                    'started_at' => now()->subHours(12),
                ]
            );

            StatusHistory::create([
                'test_request_id' => $req2->id,
                'changed_by' => $pelayanan?->id,
                'status' => TestRequestStatus::InProgress,
                'note' => 'Sampel diterima dan sedang diproses di laboratorium mikrobiologi.',
                'created_at' => now(),
            ]);
        }

        // -------------------------------------------------------------
        // KASUS 3: Sampel Ditolak karena Pecah/Bocor (Swab Alat - Loket)
        // -------------------------------------------------------------
        $swabGroup = ServiceGroup::where('code', 'SWAB_ALAT')->first();
        $swabSampleType = SampleType::where('service_group_id', $swabGroup?->id)->first();
        if ($swabGroup) {
            $req3 = TestRequest::updateOrCreate(
                ['fpps_number' => 'FPPS-20261016-0003'],
                [
                    'request_number' => 'REQ-20261016-0003',
                    'customer_id' => $customerLoket->id,
                    'service_group_id' => $swabGroup->id,
                    'created_by' => $pelayanan?->id,
                    'purpose' => 'Uji Higiene Sanitasi Peralatan Medis Poli Gigi',
                    'channel' => RegistrationChannel::Counter,
                    'sampling_location' => 'Poli Gigi Puskesmas Rijali',
                    'sampling_date' => now()->format('Y-m-d'),
                    'notes' => 'Diantar langsung oleh sanitarian puskesmas',
                    'total_amount' => 228000.00,
                    'payment_status' => PaymentStatus::Unpaid,
                    'status' => TestRequestStatus::Cancelled,
                    'estimated_completion_date' => null,
                    'registered_at' => now(),
                ]
            );

            $sample3 = Sample::updateOrCreate(
                ['sample_code' => 'SMP-20261016-003'],
                [
                    'test_request_id' => $req3->id,
                    'sample_type_id' => $swabSampleType?->id ?? 1,
                    'sample_name' => 'Usap Dental Unit & Sonde',
                    'quantity' => 1.0,
                    'unit' => 'set',
                    'collected_at' => now()->subHours(2),
                    'status' => SampleStatus::Rejected,
                ]
            );

            SampleReceipt::updateOrCreate(
                ['sample_id' => $sample3->id],
                [
                    'received_by' => $penerima?->id ?? 1,
                    'received_at' => now(),
                    'received_volume' => 0.0,
                    'condition' => 'Tabung reaksi pecah dalam perjalanan dan media BPW tumpah',
                    'decision' => SampleDecision::Rejected,
                    'rejection_reason' => 'Wadah tabung media transport pecah dan lidi kapas swab terkontaminasi di luar wadah.',
                    'follow_up' => 'Petugas telah berkoordinasi dengan pemohon untuk pengambilan usap alat ulang menggunakan tabung baru.',
                ]
            );

            StatusHistory::create([
                'test_request_id' => $req3->id,
                'changed_by' => $penerima?->id,
                'status' => TestRequestStatus::Cancelled,
                'note' => 'Sampel ditolak di loket penerimaan sampel karena wadah pecah.',
                'created_at' => now(),
            ]);
        }

        // -------------------------------------------------------------
        // KASUS 4: Menunggu Pembayaran (Penelitian Mahasiswa S1)
        // -------------------------------------------------------------
        $penelitianGroup = ServiceGroup::where('code', 'PENELITIAN')->first();
        $paketS1 = ServicePackage::where('service_group_id', $penelitianGroup?->id)->where('name', 'Studi S1')->first();
        if ($penelitianGroup) {
            $req4 = TestRequest::updateOrCreate(
                ['fpps_number' => 'FPPS-20261016-0004'],
                [
                    'request_number' => 'REQ-20261016-0004',
                    'customer_id' => $customerMahasiswa->id,
                    'service_group_id' => $penelitianGroup->id,
                    'service_package_id' => $paketS1?->id,
                    'created_by' => null,
                    'purpose' => 'Penelitian Skripsi: Analisis Senyawa Metabolit Sekunder Ekstrak Pala Banda',
                    'channel' => RegistrationChannel::Online,
                    'sampling_location' => 'Laboratorium Kimia Dasar Unpatti',
                    'sampling_date' => now()->format('Y-m-d'),
                    'notes' => 'Melampirkan surat izin penelitian Dekan FMIPA No. 124/UN13.1.8/KM/2026',
                    'total_amount' => 120000.00,
                    'payment_status' => PaymentStatus::Unpaid,
                    'status' => TestRequestStatus::AwaitingPayment,
                    'estimated_completion_date' => now()->addDays(14)->format('Y-m-d'),
                    'registered_at' => now(),
                ]
            );

            StatusHistory::create([
                'test_request_id' => $req4->id,
                'changed_by' => $pelayanan?->id,
                'status' => TestRequestStatus::AwaitingPayment,
                'note' => 'Pendaftaran online disetujui. Silakan lakukan pembayaran retribusi melalui kode billing SIMPONI.',
                'created_at' => now(),
            ]);
        }

        // -------------------------------------------------------------
        // KASUS 5: Tervalidasi dengan Jejak Koreksi / Revisi (Air Limbah Faskes)
        // -------------------------------------------------------------
        $limbahGroup = ServiceGroup::where('code', 'AIR_LIMBAH_FASKES')->first();
        $paramPb = TestParameter::where('service_group_id', $limbahGroup?->id)->where('name', 'Timbal (Pb)')->first();
        if ($limbahGroup && $paramPb) {
            $req5 = TestRequest::updateOrCreate(
                ['fpps_number' => 'FPPS-20261016-0005'],
                [
                    'request_number' => 'REQ-20261016-0005',
                    'customer_id' => $customerDlh->id,
                    'service_group_id' => $limbahGroup->id,
                    'created_by' => $pelayanan?->id,
                    'purpose' => 'Uji Petik Pengawasan Pengelolaan Limbah Cair Rumah Sakit',
                    'channel' => RegistrationChannel::Counter,
                    'sampling_location' => 'Outlet IPAL RS Swasta Kota Ambon',
                    'sampling_date' => now()->subDays(3)->format('Y-m-d'),
                    'total_amount' => 373000.00,
                    'payment_status' => PaymentStatus::Paid,
                    'status' => TestRequestStatus::Validated,
                    'estimated_completion_date' => now()->addDays(4)->format('Y-m-d'),
                    'registered_at' => now()->subDays(3),
                ]
            );

            $reqParamPb = TestRequestParameter::updateOrCreate(
                [
                    'test_request_id' => $req5->id,
                    'test_parameter_id' => $paramPb->id,
                ],
                [
                    'price' => 120000.00,
                ]
            );

            $sample5 = Sample::updateOrCreate(
                ['sample_code' => 'SMP-20261016-005'],
                [
                    'test_request_id' => $req5->id,
                    'sample_type_id' => SampleType::where('service_group_id', $limbahGroup->id)->first()?->id ?? 1,
                    'sample_name' => 'Air Limbah Outlet IPAL',
                    'quantity' => 2000.0,
                    'unit' => 'ml',
                    'collected_at' => now()->subDays(3),
                    'status' => SampleStatus::Accepted,
                ]
            );

            $job5 = TestJob::updateOrCreate(
                ['job_number' => 'JOB-20261016-005'],
                [
                    'sample_id' => $sample5->id,
                    'laboratory_unit_id' => $limbahGroup->laboratory_unit_id,
                    'assigned_to' => $analis?->id,
                    'status' => TestJobStatus::Completed,
                    'started_at' => now()->subDays(2),
                    'completed_at' => now()->subDay(),
                ]
            );

            $resultPb = TestResult::updateOrCreate(
                [
                    'test_job_id' => $job5->id,
                    'test_request_parameter_id' => $reqParamPb->id,
                ],
                [
                    'analyst_id' => $analis?->id ?? 1,
                    'value_numeric' => 0.045, // Nilai setelah koreksi
                    'unit' => 'mg/L',
                    'method' => 'SNI 6989.8:2009 (AAS)',
                    'tested_at' => now()->subDay()->format('Y-m-d'),
                    'status' => TestResultStatus::Validated,
                    'is_locked' => true,
                ]
            );

            // Jejak revisi / audit trail
            ResultRevision::create([
                'test_result_id' => $resultPb->id,
                'revised_by' => $analis?->id ?? 1,
                'old_value' => '0.082',
                'new_value' => '0.045',
                'reason' => 'Koreksi faktor pengenceran sampel destruksi asam nitrat pada pembacaan spektrofotometer serapan atom (AAS).',
                'created_at' => now()->subHours(10),
            ]);

            // Validasi Manager Lab
            ResultValidation::updateOrCreate(
                ['test_result_id' => $resultPb->id],
                [
                    'validator_id' => $validator?->id ?? 1,
                    'stage' => ValidationStage::ManagerLab,
                    'decision' => ValidationDecision::Approved,
                    'notes' => 'Koreksi perhitungan diterima dan kurva kalibrasi AAS telah diverifikasi ulang.',
                    'decided_at' => now()->subHours(6),
                ]
            );
        }
    }
}
