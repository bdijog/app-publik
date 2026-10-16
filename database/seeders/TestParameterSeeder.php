<?php

namespace Database\Seeders;

use App\Enums\ResultType;
use App\Models\ServiceGroup;
use App\Models\TestParameter;
use Illuminate\Database\Seeder;

class TestParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data parameter diambil lengkap dari PRD Bagian 3 (Brosur Layanan),
     * dilengkapi satuan, metode uji standar (SNI, APHA, Permenkes, WHO),
     * dan ambang batas baku mutu resmi.
     */
    public function run(): void
    {
        $groups = ServiceGroup::pluck('id', 'code');

        $parameters = [
            // 1. Air Laut
            'AIR_LAUT' => [
                ['name' => 'Suhu', 'unit' => '°C', 'method' => 'SNI 06-6989.23-2005', 'result_type' => ResultType::Numeric, 'min' => 28.0, 'max' => 32.0],
                ['name' => 'TDS (Total Dissolved Solid)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.27-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 35000.0],
                ['name' => 'TSS (Total Suspended Solid)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.3-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 20.0],
                ['name' => 'Warna', 'unit' => 'Pt-Co', 'method' => 'SNI 06-6989.24-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 30.0],
                ['name' => 'pH', 'unit' => 'pH Unit', 'method' => 'SNI 06-6989.11-2004', 'result_type' => ResultType::Numeric, 'min' => 7.0, 'max' => 8.5],
                ['name' => 'BOD (Biochemical Oxygen Demand)', 'unit' => 'mg/L', 'method' => 'SNI 6989.72:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 20.0],
                ['name' => 'COD (Chemical Oxygen Demand)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.2-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 40.0],
                ['name' => 'DO (Dissolved Oxygen)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.14-2004', 'result_type' => ResultType::Numeric, 'min' => 5.0, 'max' => null],
                ['name' => 'Logam Berat Timbal (Pb)', 'unit' => 'mg/L', 'method' => 'SNI 6989.8:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.008],
                ['name' => 'Deterjen (MBAS)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.51-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 1.0],
                ['name' => 'Total Coliform', 'unit' => 'MPN/100ml', 'method' => 'APHA 9221 B', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 1000.0],
            ],

            // 2. Air Limbah Domestik
            'AIR_LIMBAH_DOMESTIK' => [
                ['name' => 'pH', 'unit' => 'pH Unit', 'method' => 'SNI 06-6989.11-2004', 'result_type' => ResultType::Numeric, 'min' => 6.0, 'max' => 9.0],
                ['name' => 'BOD', 'unit' => 'mg/L', 'method' => 'SNI 6989.72:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 30.0],
                ['name' => 'COD', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.2-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 100.0],
                ['name' => 'TSS', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.3-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 30.0],
                ['name' => 'Amoniak (NH3-N)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.30-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 10.0],
                ['name' => 'Total Coliform', 'unit' => 'MPN/100ml', 'method' => 'APHA 9221 B', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 3000.0],
            ],

            // 3. Air Limbah Faskes
            'AIR_LIMBAH_FASKES' => [
                ['name' => 'TDS', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.27-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 2000.0],
                ['name' => 'Deterjen (MBAS)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.51-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 2.0],
                ['name' => 'NH3-N (Amoniak Bebas)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.30-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 5.0],
                ['name' => 'Timbal (Pb)', 'unit' => 'mg/L', 'method' => 'SNI 6989.8:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.1],
                ['name' => 'Besi (Fe)', 'unit' => 'mg/L', 'method' => 'SNI 6989.4:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 5.0],
                ['name' => 'Mangan (Mn)', 'unit' => 'mg/L', 'method' => 'SNI 6989.5:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 2.0],
                ['name' => 'Tembaga (Cu)', 'unit' => 'mg/L', 'method' => 'SNI 6989.6:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.5],
                ['name' => 'Seng (Zn)', 'unit' => 'mg/L', 'method' => 'SNI 6989.7:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 5.0],
                ['name' => 'Kadmium (Cd)', 'unit' => 'mg/L', 'method' => 'SNI 6989.16:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.05],
            ],

            // 4. Vektor
            'VEKTOR' => [
                ['name' => 'Identifikasi Spesies Lalat (Musca domestica)', 'unit' => null, 'method' => 'Morfologi Mikroskopis', 'result_type' => ResultType::Text, 'threshold_text' => 'Indeks Kepadatan Lalat < 2'],
                ['name' => 'Identifikasi Spesies Tikus (Rattus norvegicus / tanezumi)', 'unit' => null, 'method' => 'Morfometri & Kunci Taksonomi', 'result_type' => ResultType::Text, 'threshold_text' => 'Trap Success < 1%'],
                ['name' => 'Identifikasi Jentik & Nyamuk (Aedes / Anopheles / Culex)', 'unit' => null, 'method' => 'Kunci Identifikasi Ditjen P2P Kemenkes', 'result_type' => ResultType::Text, 'threshold_text' => 'Angka Bebas Jentik (ABJ) ≥ 95%'],
                ['name' => 'Identifikasi Spesies Kecoa (Periplaneta americana)', 'unit' => null, 'method' => 'Morfologi Mikroskopis', 'result_type' => ResultType::Text, 'threshold_text' => 'Indeks Populasi Kecoa < 1'],
            ],

            // 5. Makanan
            'MAKANAN' => [
                ['name' => 'Boraks', 'unit' => null, 'method' => 'Uji Kualitatif Kurkumin', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Formalin', 'unit' => null, 'method' => 'Uji Kualitatif Asam Kromatropat', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Rhodamin B', 'unit' => null, 'method' => 'Kromatografi Kertas / TLC', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Methanil Yellow', 'unit' => null, 'method' => 'Kromatografi Kertas / TLC', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Angka Lempeng Total (ALT)', 'unit' => 'koloni/g', 'method' => 'SNI 7388:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 10000.0],
                ['name' => 'Coliform', 'unit' => 'APM/g', 'method' => 'SNI ISO 4832:2012', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 10.0],
                ['name' => 'Salmonella sp.', 'unit' => null, 'method' => 'SNI ISO 6579:2015', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif / 25g'],
                ['name' => 'Vibrio cholerae / parahaemolyticus', 'unit' => null, 'method' => 'ISO 21872-1:2017', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif / 25g'],
            ],

            // 6. Swab Alat
            'SWAB_ALAT' => [
                ['name' => 'Angka Kuman', 'unit' => 'koloni/cm2', 'method' => 'Permenkes No. 1096/2011', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 100.0],
                ['name' => 'Coliform', 'unit' => 'koloni/cm2', 'method' => 'Permenkes No. 1096/2011', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.0],
                ['name' => 'Escherichia coli', 'unit' => 'koloni/cm2', 'method' => 'Permenkes No. 1096/2011', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.0],
            ],

            // 7. Air Bersih
            'AIR_BERSIH' => [
                ['name' => 'Suhu', 'unit' => '°C', 'method' => 'SNI 06-6989.23-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 30.0],
                ['name' => 'Kekeruhan', 'unit' => 'NTU', 'method' => 'SNI 06-6989.25-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 25.0],
                ['name' => 'TDS (Total Dissolved Solid)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.27-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 1000.0],
                ['name' => 'pH', 'unit' => 'pH Unit', 'method' => 'SNI 06-6989.11-2004', 'result_type' => ResultType::Numeric, 'min' => 6.5, 'max' => 8.5],
                ['name' => 'Besi (Fe)', 'unit' => 'mg/L', 'method' => 'SNI 6989.4:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 1.0],
                ['name' => 'Mangan (Mn)', 'unit' => 'mg/L', 'method' => 'SNI 6989.5:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.5],
                ['name' => 'Kromium Valensi 6 (Cr-VI)', 'unit' => 'mg/L', 'method' => 'SNI 6989.71:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.05],
                ['name' => 'Total Coliform', 'unit' => 'CFU/100ml', 'method' => 'APHA 9222 B', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 50.0],
                ['name' => 'Escherichia coli', 'unit' => 'CFU/100ml', 'method' => 'APHA 9222 G', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.0],
            ],

            // 8. Air Sungai
            'AIR_SUNGAI' => [
                ['name' => 'Suhu', 'unit' => '°C', 'method' => 'SNI 06-6989.23-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 30.0],
                ['name' => 'TDS', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.27-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 1000.0],
                ['name' => 'TSS', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.3-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 50.0],
                ['name' => 'pH', 'unit' => 'pH Unit', 'method' => 'SNI 06-6989.11-2004', 'result_type' => ResultType::Numeric, 'min' => 6.0, 'max' => 9.0],
                ['name' => 'BOD', 'unit' => 'mg/L', 'method' => 'SNI 6989.72:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 3.0],
                ['name' => 'COD', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.2-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 25.0],
                ['name' => 'DO (Oksigen Terlarut)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.14-2004', 'result_type' => ResultType::Numeric, 'min' => 4.0, 'max' => null],
                ['name' => 'Fosfat Total (PO4-P)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.31-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.2],
                ['name' => 'Logam Berat Timbal (Pb)', 'unit' => 'mg/L', 'method' => 'SNI 6989.8:2009 (AAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.03],
                ['name' => 'Deterjen (MBAS)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.51-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.2],
                ['name' => 'Total Coliform', 'unit' => 'MPN/100ml', 'method' => 'APHA 9221 B', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 1000.0],
            ],

            // 9. Air Kolam Renang
            'AIR_KOLAM_RENANG' => [
                ['name' => 'Bau', 'unit' => null, 'method' => 'Organoleptik', 'result_type' => ResultType::Text, 'threshold_text' => 'Bebas Bau Asing'],
                ['name' => 'Kekeruhan', 'unit' => 'NTU', 'method' => 'SNI 06-6989.25-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 5.0],
                ['name' => 'Suhu', 'unit' => '°C', 'method' => 'SNI 06-6989.23-2005', 'result_type' => ResultType::Numeric, 'min' => 21.0, 'max' => 32.0],
                ['name' => 'pH', 'unit' => 'pH Unit', 'method' => 'SNI 06-6989.11-2004', 'result_type' => ResultType::Numeric, 'min' => 7.0, 'max' => 7.8],
                ['name' => 'Alkalinitas', 'unit' => 'mg/L CaCO3', 'method' => 'SNI 06-2422-1991', 'result_type' => ResultType::Numeric, 'min' => 80.0, 'max' => 120.0],
                ['name' => 'Sisa Klor Bebas', 'unit' => 'mg/L', 'method' => 'DPD Kolorimetri', 'result_type' => ResultType::Numeric, 'min' => 1.0, 'max' => 3.0],
                ['name' => 'Heterotrophic Plate Count (HPC / Angka Kuman)', 'unit' => 'CFU/ml', 'method' => 'APHA 9215 B', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 200.0],
            ],

            // 10. Bakteriologi Udara
            'BAKTERIOLOGI_UDARA' => [
                ['name' => 'Angka Kuman Udara Ruang', 'unit' => 'CFU/m3', 'method' => 'Cawan Terpapar / Air Sampler', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 200.0],
            ],

            // 11. Udara Ambien
            'UDARA_AMBIEN' => [
                ['name' => 'Kebisingan Lingkungan', 'unit' => 'dBA', 'method' => 'Sound Level Meter SNI 8427:2017', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 55.0],
                ['name' => 'Suhu Udara', 'unit' => '°C', 'method' => 'Termometer Digital Terkalibrasi', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 35.0],
                ['name' => 'Kelembapan Relatif', 'unit' => '%', 'method' => 'Higrometer Digital', 'result_type' => ResultType::Numeric, 'min' => 40.0, 'max' => 80.0],
                ['name' => 'Tekanan Barometrik', 'unit' => 'mmHg', 'method' => 'Barometer Digital', 'result_type' => ResultType::Numeric, 'min' => 750.0, 'max' => 770.0],
                ['name' => 'Nitrogen Dioksida (NO2)', 'unit' => 'µg/m3', 'method' => 'SNI 19-7119.2-2005 (Griess Saltzman)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 200.0],
                ['name' => 'Sulfur Dioksida (SO2)', 'unit' => 'µg/m3', 'method' => 'SNI 19-7119.7-2005 (Pararosanilin)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 150.0],
                ['name' => 'Amoniak (NH3)', 'unit' => 'ppm', 'method' => 'SNI 19-7119.1-2005 (Indofenol)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 2.0],
                ['name' => 'Ozon (O3)', 'unit' => 'µg/m3', 'method' => 'SNI 19-7119.8-2005 (NBKI)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 150.0],
                ['name' => 'Kecepatan dan Arah Angin', 'unit' => 'm/s', 'method' => 'Anemometer Portabel', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 10.0],
                ['name' => 'Total Suspended Particulate (TSP 24 Jam)', 'unit' => 'µg/m3', 'method' => 'High Volume Air Sampler (HVAS)', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 230.0],
            ],

            // 12. Udara Ruangan
            'UDARA_RUANGAN' => [
                ['name' => 'Tingkat Kebisingan Ruangan', 'unit' => 'dBA', 'method' => 'Sound Level Meter SNI 7231:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 65.0],
                ['name' => 'Suhu Ruangan', 'unit' => '°C', 'method' => 'Termometer Ruang SNI 16-7061-2004', 'result_type' => ResultType::Numeric, 'min' => 23.0, 'max' => 26.0],
                ['name' => 'Kelembapan Udara Ruangan', 'unit' => '%', 'method' => 'Higrometer Ruangan', 'result_type' => ResultType::Numeric, 'min' => 40.0, 'max' => 60.0],
                ['name' => 'Intensitas Pencahayaan', 'unit' => 'Lux', 'method' => 'Luxmeter SNI 16-7062-2004', 'result_type' => ResultType::Numeric, 'min' => 200.0, 'max' => null],
            ],

            // 13. Patologi Klinis
            'PATOLOGI_KLINIS' => [
                ['name' => 'Kolesterol Total', 'unit' => 'mg/dL', 'method' => 'CHOD-PAP Enzimatik', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 200.0],
                ['name' => 'Asam Urat', 'unit' => 'mg/dL', 'method' => 'Urikase-PAP Enzimatik', 'result_type' => ResultType::Numeric, 'min' => 3.5, 'max' => 7.2],
                ['name' => 'Gula Darah Sewaktu', 'unit' => 'mg/dL', 'method' => 'GOD-PAP Enzimatik', 'result_type' => ResultType::Numeric, 'min' => 70.0, 'max' => 140.0],
                ['name' => 'Trigliserida', 'unit' => 'mg/dL', 'method' => 'GPO-PAP Enzimatik', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 150.0],
            ],

            // 14. Air Minum
            'AIR_MINUM' => [
                ['name' => 'Bau', 'unit' => null, 'method' => 'Organoleptik', 'result_type' => ResultType::Text, 'threshold_text' => 'Tidak Berbau'],
                ['name' => 'Rasa', 'unit' => null, 'method' => 'Organoleptik', 'result_type' => ResultType::Text, 'threshold_text' => 'Tidak Berasa'],
                ['name' => 'Kekeruhan', 'unit' => 'NTU', 'method' => 'SNI 06-6989.25-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 3.0],
                ['name' => 'Warna', 'unit' => 'TCU', 'method' => 'SNI 06-6989.24-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 10.0],
                ['name' => 'TDS', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.27-2005', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 300.0],
                ['name' => 'pH', 'unit' => 'pH Unit', 'method' => 'SNI 06-6989.11-2004', 'result_type' => ResultType::Numeric, 'min' => 6.5, 'max' => 8.5],
                ['name' => 'Nitrit (NO2-N)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.9-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 3.0],
                ['name' => 'Nitrat (NO3-N)', 'unit' => 'mg/L', 'method' => 'SNI 06-6989.9-2004', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 50.0],
                ['name' => 'Besi (Fe)', 'unit' => 'mg/L', 'method' => 'SNI 6989.4:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.2],
                ['name' => 'Mangan (Mn)', 'unit' => 'mg/L', 'method' => 'SNI 6989.5:2009', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.1],
                ['name' => 'Total Coliform', 'unit' => 'CFU/100ml', 'method' => 'APHA 9222 B', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.0],
                ['name' => 'Escherichia coli', 'unit' => 'CFU/100ml', 'method' => 'APHA 9222 G', 'result_type' => ResultType::Numeric, 'min' => null, 'max' => 0.0],
            ],

            // 15. Penelitian
            'PENELITIAN' => [
                ['name' => 'Fasilitas Alat & Preparasi Sampel Penelitian', 'unit' => 'Sesi / Hari', 'method' => 'SOP Penggunaan Laboratorium Riset', 'result_type' => ResultType::Text, 'threshold_text' => 'Laporan Hasil Kerja'],
                ['name' => 'Konsultasi & Pengujian Instrumen Kimia / Mikrobiologi', 'unit' => 'Sampel', 'method' => 'Metode Sesuai Proposal Penelitian', 'result_type' => ResultType::Text, 'threshold_text' => 'Data Mentah & Analisis'],
            ],

            // 16. Mikrobiologi, Parasitologi, dan Biomolekuler
            'MIKRO_PARASIT_BIOMOL' => [
                ['name' => 'Deteksi Virus Influenza (RT-PCR)', 'unit' => null, 'method' => 'Real-Time RT-PCR', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif / Tidak Terdeteksi'],
                ['name' => 'Sequencing SARS-CoV-2 (WGS)', 'unit' => null, 'method' => 'Next Generation Sequencing (NGS)', 'result_type' => ResultType::Text, 'threshold_text' => 'Laporan Lineage / Varian'],
                ['name' => 'Serologi Antibodi / Antigen Spesifik', 'unit' => null, 'method' => 'ELISA / Chemiluminescence', 'result_type' => ResultType::Text, 'threshold_text' => 'Non-Reaktif'],
                ['name' => 'Pemeriksaan TB (TCM GenXpert / BTA)', 'unit' => null, 'method' => 'Molecular GeneXpert MTB/RIF', 'result_type' => ResultType::Text, 'threshold_text' => 'MTB Not Detected'],
                ['name' => 'Pemeriksaan Difteri (Corynebacterium diphtheriae)', 'unit' => null, 'method' => 'Kultur Media Tellurite & PCR Toksin', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Pemeriksaan Pertusis (Bordetella pertussis)', 'unit' => null, 'method' => 'Real-Time PCR', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Diagnosis Malaria (Mikroskopis & RDT)', 'unit' => null, 'method' => 'Pewarnaan Giemsa Darah Tebal & Tipis', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif / Tidak Ditemukan Plasmodium'],
                ['name' => 'Pemeriksaan Filariasis (Mikrofilaria Darah Malam)', 'unit' => null, 'method' => 'Pewarnaan Giemsa Darah Jari Malam', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
                ['name' => 'Pemeriksaan Telur Cacing (Helminth Eggs)', 'unit' => null, 'method' => 'Kato-Katz / Flotasi Feses', 'result_type' => ResultType::Text, 'threshold_text' => 'Negatif'],
            ],

            // 17. Kalibrasi
            'KALIBRASI' => [
                ['name' => 'Kalibrasi Photometer / Spektrofotometer', 'unit' => 'Abs / nm', 'method' => 'Filter Standar CRM Terkalibrasi', 'result_type' => ResultType::Text, 'threshold_text' => 'Sertifikat Kalibrasi KAN'],
                ['name' => 'Kalibrasi Inkubator / Oven Laboratorium', 'unit' => '°C', 'method' => 'Data Logger Multi-Point Thermocouple', 'result_type' => ResultType::Text, 'threshold_text' => 'Sertifikat Kalibrasi KAN'],
                ['name' => 'Kalibrasi Konduktometer', 'unit' => 'µS/cm', 'method' => 'Standar Konduktivitas KCl CRM', 'result_type' => ResultType::Text, 'threshold_text' => 'Sertifikat Kalibrasi KAN'],
                ['name' => 'Kalibrasi pH Meter', 'unit' => 'pH Unit', 'method' => 'Larutan Buffer CRM pH 4.01, 7.00, 10.01', 'result_type' => ResultType::Text, 'threshold_text' => 'Sertifikat Kalibrasi KAN'],
                ['name' => 'Kalibrasi Sentrifus (Centrifuge)', 'unit' => 'RPM / Menit', 'method' => 'Tachometer Optik & Timer Terkalibrasi', 'result_type' => ResultType::Text, 'threshold_text' => 'Sertifikat Kalibrasi KAN'],
            ],
        ];

        foreach ($parameters as $groupCode => $paramList) {
            $groupId = $groups[$groupCode] ?? null;
            if (! $groupId) {
                continue;
            }

            foreach ($paramList as $p) {
                TestParameter::updateOrCreate(
                    [
                        'service_group_id' => $groupId,
                        'name' => $p['name'],
                    ],
                    [
                        'unit' => $p['unit'] ?? null,
                        'method' => $p['method'] ?? null,
                        'result_type' => $p['result_type'],
                        'threshold_min' => $p['min'] ?? null,
                        'threshold_max' => $p['max'] ?? null,
                        'threshold_text' => $p['threshold_text'] ?? null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
