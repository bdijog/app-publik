<?php

namespace Database\Seeders;

use App\Models\SampleType;
use App\Models\ServiceGroup;
use Illuminate\Database\Seeder;

class SampleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data jenis sampel, volume minimum, dan persyaratan wadah
     * untuk seluruh 17 kelompok layanan sesuai standar laboratorium.
     */
    public function run(): void
    {
        $groups = ServiceGroup::pluck('id', 'code');

        $sampleTypes = [
            'AIR_LAUT' => [
                'name' => 'Air Laut Permukaan / Muara',
                'min_volume' => 2000.0,
                'container_requirement' => 'Jerigen Polyethylene 2L (Kimia) + Botol Kaca Steril 500ml (Mikro)',
                'requirements' => 'Pengambilan dilakukan saat pasang/surut sesuai tujuan; simpan dalam coolbox suhu 4°C dan diantar < 24 jam.',
            ],
            'AIR_LIMBAH_DOMESTIK' => [
                'name' => 'Air Limbah Outlet IPAL Domestik',
                'min_volume' => 1500.0,
                'container_requirement' => 'Botol Plastik PE Bersih 1.5L',
                'requirements' => 'Sampel diambil pada titik outlet IPAL sebelum dibuang ke badan air; tambahkan pengawet H2SO4 sampai pH < 2 untuk parameter COD.',
            ],
            'AIR_LIMBAH_FASKES' => [
                'name' => 'Air Limbah Rumah Sakit / Puskesmas',
                'min_volume' => 2000.0,
                'container_requirement' => 'Jerigen PE 2L (Dianjurkan botol bebas logam untuk uji AAS)',
                'requirements' => 'Sampel representatif outlet pengolahan limbah cair faskes; simpan suhu 4°C dan diberi label bahaya biologis/limbah medis.',
            ],
            'VEKTOR' => [
                'name' => 'Spesimen Vektor Penyakit (Nyamuk/Lalat/Tikus/Kecoa)',
                'min_volume' => 1.0,
                'container_requirement' => 'Tabung Spesimen / Vial Berisi Alkohol 70% atau Kotak Khusus Vektor',
                'requirements' => 'Kondisi spesimen utuh tidak hancur; identitas lokasi penangkapan (GPS/desa/faskes) wajib dicantumkan pada label.',
            ],
            'MAKANAN' => [
                'name' => 'Makanan Olahan / Jajanan / Pangan Siap Saji',
                'min_volume' => 250.0, // dalam gram
                'container_requirement' => 'Plastik Zip Steril / Kotak Sampel Makanan Bersih & Kedap Udara',
                'requirements' => 'Sampel diambil utuh 1 porsi saji secara aseptis; simpan dalam coolbox pendingin jika makanan basah atau cepat basi.',
            ],
            'SWAB_ALAT' => [
                'name' => 'Usap Alat Makan & Alat Medis / Permukaan Kerja',
                'min_volume' => 1.0,
                'container_requirement' => 'Tabung Reaksi Berisi 10 ml Buffer Peptone Water (BPW) Steril',
                'requirements' => 'Pengusapan luas 25 cm2 (5x5 cm) menggunakan kapas lidi steril secara rotasi; segera kirim ke lab dalam wadah pendingin.',
            ],
            'AIR_BERSIH' => [
                'name' => 'Air Bersih Sanitasi (Sumur Bor/Gali/PDAM)',
                'min_volume' => 1500.0,
                'container_requirement' => 'Jerigen PE 1L (Kimia) + Botol Kaca Cokelat Steril 500ml (Bakteriologi)',
                'requirements' => 'Kran dialirkan 2-3 menit sebelum sampling; mulut kran disterilkan dengan api/alkohol 70%.',
            ],
            'AIR_SUNGAI' => [
                'name' => 'Air Permukaan Sungai / Danau',
                'min_volume' => 2000.0,
                'container_requirement' => 'Jerigen PE 2L + Botol Kaca Steril 500ml',
                'requirements' => 'Sampel diambil di tengah aliran pada kedalaman 0.2x dan 0.8x kedalaman total (metode terpadu).',
            ],
            'AIR_KOLAM_RENANG' => [
                'name' => 'Air Kolam Renang / Pemandian Wisata',
                'min_volume' => 1000.0,
                'container_requirement' => 'Botol Kaca Steril 500ml (Bakteriologi) + Botol Plastik 500ml (Kimia)',
                'requirements' => 'Sampel diambil 20 cm di bawah permukaan air kolam pada titik terjauh dari inlet pengisian air.',
            ],
            'BAKTERIOLOGI_UDARA' => [
                'name' => 'Media Cawan Terpapar / Air Sampling',
                'min_volume' => 1.0,
                'container_requirement' => 'Cawan Petri Berisi Media Nutrient Agar (NA) / Blood Agar Tersegel',
                'requirements' => 'Cawan dipaparkan selama 15 menit pada ketinggian 1-1.5 meter di tengah ruangan; bungkus rapat dan simpan suhu ruang terlindung cahaya.',
            ],
            'UDARA_AMBIEN' => [
                'name' => 'Filter Debu & Larutan Penjerap Udara Ambien',
                'min_volume' => 1.0,
                'container_requirement' => 'Kaset Filter Partikulat + Botol Impinger Kaca Gelap Berisi Reagen Penjerap',
                'requirements' => 'Pengukuran dilakukan di stasiun pemantau selama periode waktu yang ditetapkan (1 jam / 24 jam) sesuai regulasi.',
            ],
            'UDARA_RUANGAN' => [
                'name' => 'Titik Pengukuran Lingkungan Kerja Fisik',
                'min_volume' => 1.0,
                'container_requirement' => 'Formulir Catatan Lapangan & Instrumen Digital Terkalibrasi',
                'requirements' => 'Pengukuran dilakukan saat ruangan beroperasi normal dengan pencahayaan dan ventilasi aktif.',
            ],
            'PATOLOGI_KLINIS' => [
                'name' => 'Serum Darah Vena / Plasma Heparin',
                'min_volume' => 3.0,
                'container_requirement' => 'Tabung Vacutainer Tutup Merah / Kuning (Gel Clot Activator)',
                'requirements' => 'Pasien berpuasa 10-12 jam sebelum pengambilan darah untuk profil metabolik; serum segera dipisahkan dalam 2 jam.',
            ],
            'AIR_MINUM' => [
                'name' => 'Air Minum Olahan / AMDK / Depot Air Minum',
                'min_volume' => 1500.0,
                'container_requirement' => 'Botol Kaca Cokelat Steril 500ml (Mikroba) + Jerigen PE Bersih 1L (Kimia)',
                'requirements' => 'Kemasan botol utuh bersegel atau diambil langsung dari nozzle pengisian depot secara higienis aseptis.',
            ],
            'PENELITIAN' => [
                'name' => 'Bahan & Spesimen Riset Mahasiswa / Peneliti',
                'min_volume' => 1.0,
                'container_requirement' => 'Wadah Standar Penelitian Laboratorium Sesuai Protokol Proposal',
                'requirements' => 'Melampirkan surat pengantar izin penelitian dari universitas/fakultas dan protokol metodologi uji.',
            ],
            'MIKRO_PARASIT_BIOMOL' => [
                'name' => 'Swab VTM / Sputum / Darah EDTA / Feses',
                'min_volume' => 2.0,
                'container_requirement' => 'Viral Transport Medium (VTM) / Pot Dahak Steril / Tabung EDTA Ungu / Pot Feses',
                'requirements' => 'Wajib menggunakan wadah berstandar triple packaging untuk spesimen infeksius berisiko tinggi; simpan suhu 2-8°C.',
            ],
            'KALIBRASI' => [
                'name' => 'Peralatan Medis & Laboratorium Kesehatan',
                'min_volume' => 1.0,
                'container_requirement' => 'Unit Alat Lengkap dengan Kabel Daya, Aksesoris Standar, dan Buku Petunjuk Operasi',
                'requirements' => 'Alat dalam kondisi bersih, bebas kontaminan bahan infeksius, dan dapat menyala secara fungsional normal.',
            ],
        ];

        foreach ($sampleTypes as $groupCode => $data) {
            $groupId = $groups[$groupCode] ?? null;
            if (! $groupId) {
                continue;
            }

            SampleType::updateOrCreate(
                [
                    'service_group_id' => $groupId,
                    'name' => $data['name'],
                ],
                [
                    'min_volume' => $data['min_volume'],
                    'container_requirement' => $data['container_requirement'],
                    'requirements' => $data['requirements'],
                ]
            );
        }
    }
}
