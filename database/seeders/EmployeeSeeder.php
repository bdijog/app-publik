<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LaboratoryUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder pegawai Balai Labkesmas Ambon dengan minimal 20+ baris data,
     * mencakup Pimpinan, Administrator/IT, Petugas Pelayanan, Penerimaan Sampel,
     * serta Analis dan Validator di seluruh 6 Instalasi Laboratorium.
     */
    public function run(): void
    {
        $units = LaboratoryUnit::pluck('id', 'code');
        $defaultPassword = Hash::make('password');

        $employees = [
            // =========================================================
            // 1. PIMPINAN & MANAJEMEN STRUKTURAL
            // =========================================================
            [
                'email' => 'pimpinan@labkesmas-ambon.go.id',
                'name' => 'dr. J. H. Pattinama, M.Kes',
                'phone' => '081240001006',
                'nip' => '197506122000031001',
                'unit_code' => null,
                'role' => 'leader',
            ],
            [
                'email' => 'kasubbag.tu@labkesmas-ambon.go.id',
                'name' => 'Willem Soukotta, S.Sos, M.Si',
                'phone' => '081240001007',
                'nip' => '197804152003121002',
                'unit_code' => null,
                'role' => 'leader',
            ],

            // =========================================================
            // 2. ADMINISTRATOR APLIKASI & PRANATA KOMPUTER
            // =========================================================
            [
                'email' => 'admin@labkesmas-ambon.go.id',
                'name' => 'Rizqi Satria Andhika Gusni, S.Kom',
                'phone' => '081240001001',
                'nip' => '199501152022031001',
                'unit_code' => null,
                'role' => 'admin',
            ],
            [
                'email' => 'it.support@labkesmas-ambon.go.id',
                'name' => 'Dian Purnama Sari, A.Md.Kom',
                'phone' => '081240001008',
                'nip' => '199708222020122005',
                'unit_code' => null,
                'role' => 'admin',
            ],

            // =========================================================
            // 3. PETUGAS PELAYANAN & ADMINISTRASI LOKET
            // =========================================================
            [
                'email' => 'pelayanan@labkesmas-ambon.go.id',
                'name' => 'Icsa Alif Algiffari Yunus, SKM',
                'phone' => '081240001002',
                'nip' => '199605202022031002',
                'unit_code' => null,
                'role' => 'service_officer',
            ],
            [
                'email' => 'novita.patty@labkesmas-ambon.go.id',
                'name' => 'Novita Grace Patty, S.Tr.Kes',
                'phone' => '081240001009',
                'nip' => '199511102020122004',
                'unit_code' => null,
                'role' => 'service_officer',
            ],
            [
                'email' => 'hendra.rmik@labkesmas-ambon.go.id',
                'name' => 'Hendra Saputra, A.Md.RMIK',
                'phone' => '081240001010',
                'nip' => '199803142022031003',
                'unit_code' => null,
                'role' => 'service_officer',
            ],
            [
                'email' => 'kasir.loket@labkesmas-ambon.go.id',
                'name' => 'Siti Rahmawati, S.E.',
                'phone' => '081240001011',
                'nip' => '199209182019032006',
                'unit_code' => null,
                'role' => 'service_officer',
            ],

            // =========================================================
            // 4. PETUGAS PENERIMAAN & PENGAMBILAN SAMPEL
            // =========================================================
            [
                'email' => 'penerima@labkesmas-ambon.go.id',
                'name' => 'Dominggus Frans Patty, A.Md.AK',
                'phone' => '081240001003',
                'nip' => '199408102020121003',
                'unit_code' => null,
                'role' => 'sample_receiver',
            ],
            [
                'email' => 'maria.leatemia@labkesmas-ambon.go.id',
                'name' => 'Maria Christine Leatemia, S.Si',
                'phone' => '081240001012',
                'nip' => '199307252019032007',
                'unit_code' => null,
                'role' => 'sample_receiver',
            ],
            [
                'email' => 'ahmad.tuasikal@labkesmas-ambon.go.id',
                'name' => 'Ahmad Fauzi Tuasikal, A.Md.Kes',
                'phone' => '081240001013',
                'nip' => '199702112022031004',
                'unit_code' => null,
                'role' => 'sample_receiver',
            ],

            // =========================================================
            // 5. INSTALASI KIMIA LINGKUNGAN & PANGAN (LAB-KIM)
            // =========================================================
            [
                'email' => 'daniel.hattu@labkesmas-ambon.go.id',
                'name' => 'Daniel Hattu, S.Si, M.Si',
                'phone' => '081240001014',
                'nip' => '198405122009031002',
                'unit_code' => 'LAB-KIM',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'analis.kimia@labkesmas-ambon.go.id',
                'name' => 'Juliana Siahaya, A.Md.AK',
                'phone' => '081240001004',
                'nip' => '199302142019031004',
                'unit_code' => 'LAB-KIM',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'ronald.titaley@labkesmas-ambon.go.id',
                'name' => 'Ronald Titaley, S.Tr.Kes',
                'phone' => '081240001015',
                'nip' => '199110052015031003',
                'unit_code' => 'LAB-KIM',
                'role' => 'lab_officer',
            ],

            // =========================================================
            // 6. INSTALASI MIKROBIOLOGI & PARASITOLOGI (LAB-MIK)
            // =========================================================
            [
                'email' => 'validator.lab@labkesmas-ambon.go.id',
                'name' => 'dr. Meilany Wattimena, Sp.PK',
                'phone' => '081240001005',
                'nip' => '198503252010011005',
                'unit_code' => 'LAB-MIK',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'yohana.latuheru@labkesmas-ambon.go.id',
                'name' => 'Yohana Latuheru, S.Si',
                'phone' => '081240001016',
                'nip' => '199012032014032004',
                'unit_code' => 'LAB-MIK',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'fikri.mikro@labkesmas-ambon.go.id',
                'name' => 'Fikri Ardiansyah, A.Md.Kes',
                'phone' => '081240001017',
                'nip' => '199604182020121006',
                'unit_code' => 'LAB-MIK',
                'role' => 'lab_officer',
            ],

            // =========================================================
            // 7. INSTALASI BIOLOGI & PENGENDALIAN VEKTOR (LAB-VEK)
            // =========================================================
            [
                'email' => 'willem.nanlohy@labkesmas-ambon.go.id',
                'name' => 'Willem Nanlohy, SKM, M.Sc',
                'phone' => '081240001018',
                'nip' => '198206192008011007',
                'unit_code' => 'LAB-VEK',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'beatrice.vektor@labkesmas-ambon.go.id',
                'name' => 'Beatrice Sahertian, A.Md.Kes',
                'phone' => '081240001019',
                'nip' => '199509282020122008',
                'unit_code' => 'LAB-VEK',
                'role' => 'lab_officer',
            ],

            // =========================================================
            // 8. INSTALASI FISIKA & UDARA (LAB-FIS)
            // =========================================================
            [
                'email' => 'irwan.fisika@labkesmas-ambon.go.id',
                'name' => 'Irwan Syahputra, S.T.',
                'phone' => '081240001020',
                'nip' => '198801102012121004',
                'unit_code' => 'LAB-FIS',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'martha.gaspersz@labkesmas-ambon.go.id',
                'name' => 'Martha Gaspersz, S.Si',
                'phone' => '081240001021',
                'nip' => '199203152019032009',
                'unit_code' => 'LAB-FIS',
                'role' => 'lab_officer',
            ],

            // =========================================================
            // 9. INSTALASI PATOLOGI KLINIS & BIOMOLEKULER (LAB-PAT)
            // =========================================================
            [
                'email' => 'dr.christian.tahitu@labkesmas-ambon.go.id',
                'name' => 'dr. Christian Tahitu, M.Biomed',
                'phone' => '081240001022',
                'nip' => '198309142009121003',
                'unit_code' => 'LAB-PAT',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'nurul.biomol@labkesmas-ambon.go.id',
                'name' => 'Nurul Hidayati, S.Si',
                'phone' => '081240001023',
                'nip' => '199411082020122010',
                'unit_code' => 'LAB-PAT',
                'role' => 'lab_officer',
            ],

            // =========================================================
            // 10. INSTALASI KALIBRASI & PENUNJANG (LAB-KAL)
            // =========================================================
            [
                'email' => 'alexander.pelupessy@labkesmas-ambon.go.id',
                'name' => 'Alexander Pelupessy, S.T.',
                'phone' => '081240001024',
                'nip' => '198704222011011005',
                'unit_code' => 'LAB-KAL',
                'role' => 'lab_officer',
            ],
            [
                'email' => 'fransina.liklikwatil@labkesmas-ambon.go.id',
                'name' => 'Fransina Liklikwatil, A.Md.TEM',
                'phone' => '081240001025',
                'nip' => '199612012022032008',
                'unit_code' => 'LAB-KAL',
                'role' => 'lab_officer',
            ],
        ];

        foreach ($employees as $data) {
            $employee = Employee::updateOrCreate(
                ['email' => $data['email']],
                [
                    'laboratory_unit_id' => $data['unit_code'] ? ($units[$data['unit_code']] ?? null) : null,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'nip' => $data['nip'],
                    'password' => $defaultPassword,
                    'is_active' => true,
                ]
            );

            $employee->syncRoles([$data['role']]);
        }
    }
}
