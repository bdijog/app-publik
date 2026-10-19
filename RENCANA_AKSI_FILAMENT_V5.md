# RENCANA AKSI PENERAPAN FILAMENT V5
## Pengelolaan Aplikasi Sistem Informasi Laboratorium Balai Labkesmas Ambon

| Dokumen | Rencana Aksi Implementasi Filament v5 |
|---|---|
| **Referensi Utama** | [PRD_dan_Desain_Basis_Data.md](file:///c:/laragon/www/app-publik/PRD_dan_Desain_Basis_Data.md) |
| **Framework & Versi** | Laravel 13.x, Filament v5.10.x, Livewire 4.x, PHP 8.4, PostgreSQL 18 |
| **Paket Keamanan** | Spatie Laravel-Permission v8.x (Guard: `employee`) |
| **Status Dokumen** | Selesai Diimplementasikan (Fase 1 s.d. Fase 6 Lengkap & Lolos Uji) |

---

## 1. Ringkasan Eksekutif & Tujuan Arsitektur

Dokumen ini merupakan panduan implementasi teknis untuk membangun seluruh antarmuka manajemen internal (back-office) Balai Laboratorium Kesehatan Masyarakat (Labkesmas) Ambon berbasis **Filament v5**. 

> [!IMPORTANT]
> **Batasan Ruang Lingkup (Scope Boundary):**
> Dokumen ini **100% hanya mencakup antarmuka Panel Admin Internal Pegawai (`/admin`)** dengan guard `employee`.
> Rencana aksi ini **TIDAK mencakup dan TIDAK menyentuh halaman frontend publik pelanggan** (portal pendaftaran mandiri pelanggan, halaman publik lacak status FPPS, maupun login customer). Modul frontend pelanggan tersebut berada di luar ruang lingkup dokumen ini dan dikembangkan secara terpisah.

Aplikasi dirancang sebagai sistem terintegrasi yang berfokus pada **tata kelola operasional layanan laboratorium** dari hulu ke hilir:
1. **Pendaftaran & Permohonan Uji** (Kanal Online, WhatsApp, dan Loket Pelayanan).
2. **Pencatatan Pembayaran & Konfirmasi Syarat**.
3. **Penerimaan Sampel Fisik** (Pemeriksaan kelayakan, keputusan Terima/Tolak, penomoran kode sampel).
4. **Distribusi SPK & Pengujian Laboratorium** (Penugasan analis per instalasi, input hasil angka/teks, komparasi ambang batas).
5. **Validasi Hasil Bertahap** (Review Manager Lab, Validasi Rekomendasi, mekanisme retur & revisi berjejak audit).
6. **Penerbitan Laporan Hasil Uji (LHU)** (Template resmi, snapshot hasil, nomor LHU unik, pengesahan berjenjang, penerbitan PDF terkontrol).
7. **Penyerahan Hasil & Log Akses** (Pencatatan serah terima fisik/digital, audit trail unduh/cetak).
8. **Rekapitulasi Data Operasional** (Pencarian, pemfilteran, dan rekapitulasi data layanan untuk keperluan operasional harian).

> [!TIP]
> **Fokus Saat Ini: Tata Kelola Operasional Layanan Laboratorium.**
> Sesuai arahan, pengembangan difokuskan sepenuhnya pada **alur tata kelola layanan** agar proses bisnis dari pendaftaran hingga LHU diserahkan dapat berjalan sempurna.
> **Halaman Dashboard Analitik, grafik visual, dan widget KPI diakhirkan terlebih dahulu** dan akan dikembangkan pada **Fase Lanjutan (fase berikutnya)** setelah transaksi data operasional sudah berjalan stabil. Pada tahap awal ini, panel menggunakan dashboard landing default Filament standar yang bersih.

Seluruh pegawai (`employees`) mengakses panel ini dengan pembatasan hak akses berbasis peran (RBAC Spatie) yang ketat pada guard `employee`.

---

## 2. Arsitektur Panel & Hak Akses (Multi-Role RBAC)

### 2.1 Konfigurasi Panel Provider (`app/Providers/Filament/AdminPanelProvider.php`)
- **Guard Autentikasi**: `employee` (model `App\Models\Employee`).
- **Brand Name**: *Balai Labkesmas Ambon - Sistem Laboratorium*.
- **Warna Identitas (Color Scheme)**:
  - Primary: Emerald / Teal (merepresentasikan nuansa kesehatan & lingkungan).
  - Info: Sky blue.
  - Success: Green.
  - Warning: Amber.
  - Danger: Rose / Red.
- **Fitur Panel**:
  - Kolaps navigasi & grup navigasi.
  - Global Search (pencarian instan berdasarkan Nomor FPPS, Nomor Permohonan, Kode Sampel, dan Nomor LHU).
  - Profil Pegawai & Ganti Password.
  - Session timeout & penguncian otomatis.
  - Landing page menggunakan `Dashboard::class` standar (tanpa widget analitik berat di awal).

### 2.2 Matriks Hak Akses Peran Pegawai (Spatie RBAC Guard: `employee`)

| Peran (Role) | Model / Halaman yang Dapat Diakses | Fitur Utama Tata Kelola Layanan |
|---|---|---|
| **Administrator** (`admin`) | Seluruh Resource, Role, Permission, Activity Logs, Settings | Akses penuh; mengelola akun pegawai, hak akses, master katalog layanan, tarif, estimasi penyelesaian, dan template LHU. |
| **Petugas Pelayanan** (`service_officer`) | `Customer`, `TestRequest`, `Payment`, `ReportHandover` | Menerima & menginput permohonan (WhatsApp/Loket), memverifikasi kelengkapan berkas, mencatat pembayaran, mencetak/menyerahkan LHU final. |
| **Petugas Penerimaan Sampel** (`sample_receiver`) | `TestRequest` (view), `Sample`, `SampleReceipt` | Memeriksa fisik sampel, mencocokkan volume/wadah, memutuskan Terima/Tolak dengan alasan wajib. |
| **Petugas Laboratorium / Analis & Validator** (`lab_officer`) | `TestJob`, `TestResult`, `ResultValidation`, `ResultRevision`, `TestReport`, Template LHU | Mengambil antrean pekerjaan, input hasil uji, submit ke validasi, melakukan validasi teknis (Manager Lab) & rekomendasi, generate draf LHU. |
| **Pimpinan / Kepala Balai** (`leader`) | Rekapitulasi & Laporan, Log Akses, View-only Transaksi Layanan | Memantau seluruh data operasional, status permohonan, riwayat LHU, unduh rekap PDF/Excel. *Read-only, tidak dapat mengubah hasil uji.* |

---

## 3. Peta Navigasi & Struktur Menu Filament v5

Untuk mendukung fokus pada **tata kelola layanan**, struktur navigasi diprioritaskan pada modul operasional harian:

```
├── 📝 Pelayanan & Permohonan (Prioritas 1)
│   ├── Permohonan Pengujian (TestRequestResource)
│   ├── Pembayaran & Tagihan (PaymentResource)
│   ├── Data Pelanggan / Pemohon (CustomerResource)
│   └── Penyerahan Hasil Uji (ReportHandoverResource)
│
├── 🧪 Penerimaan Sampel (Prioritas 2)
│   ├── Antrean Penerimaan Sampel (SampleReceiptResource / Action)
│   └── Data Sampel Fisik (SampleResource)
│
├── 🔬 Pengujian Laboratorium (Prioritas 3)
│   ├── Perintah Kerja Uji / Job (TestJobResource)
│   ├── Input & Verifikasi Hasil (TestResultResource)
│   ├── Riwayat Koreksi & Revisi Hasil (ResultRevisionResource - Audit)
│
├── 📑 Laporan Hasil Uji - LHU (Prioritas 4)
│   ├── Dokumen LHU (TestReportResource)
│   └── Template Format LHU (ReportTemplateResource)
│
├── 📦 Klaster Katalog Layanan [Filament Cluster] (Prioritas 5)
│   ├── Kelompok Layanan (ServiceGroupResource)
│   ├── Paket Pemeriksaan (ServicePackageResource)
│   ├── Master Parameter Uji (TestParameterResource)
│   ├── Master Tarif & Masa Berlaku (TariffResource)
│   ├── Standar Estimasi Selesai / SLA (CompletionEstimateResource)
│   ├── Jenis Sampel & Persyaratan Wadah (SampleTypeResource)
│   └── Unit / Instalasi Lab (LaboratoryUnitResource)
│
├── 👥 Manajemen Pengguna & Hak Akses (Prioritas 6)
│   ├── Data Pegawai Balai (EmployeeResource)
│   ├── Peran Pegawai (RoleResource)
│   └── Hak Akses Sistem (PermissionResource)
│
├── 📑 Rekapitulasi Operasional & Ekspor (Prioritas 7)
│   └── Rekap Data Layanan & Ekspor Excel/PDF (ReportsPage)
│
├── 🛡️ Audit & Jejak Sistem (Prioritas 8)
│   ├── Log Akses Dokumen LHU (DocumentAccessLogResource)
│   └── Log Aktivitas Pegawai (ActivityLogResource)
│
└── 📊 Dashboard Analitik Eksekutif [Diakhirkan - Fase Lanjutan]
    └── Widget KPI, Statistik Layanan, & Grafik Tren Visual
```

---

## 4. Spesifikasi Teknis Resource Filament v5

Setiap Resource akan dibuat menggunakan konvensi Filament v5 (`Filament\Schemas\Components\...` untuk layout/form, `Filament\Tables\Columns\...` untuk tabel, dan `Filament\Actions\...` untuk tindakan).

---

### MODUL 1: KLASTER KATALOG LAYANAN (`CatalogCluster`)

#### 1.1 `LaboratoryUnitResource`
- **Tabel**: `laboratory_units`
- **Form Schema**:
  - `TextInput::make('code')->required()->unique(ignoreRecord: true)->maxLength(20)->label('Kode Instalasi')`
  - `TextInput::make('name')->required()->maxLength(255)->label('Nama Instalasi / Unit Laboratorium')`
- **Table Columns**:
  - `TextColumn::make('code')->badge()->sortable()->searchable()`
  - `TextColumn::make('name')->sortable()->searchable()`
  - `TextColumn::make('service_groups_count')->counts('serviceGroups')->label('Kelompok Layanan')`
  - `TextColumn::make('employees_count')->counts('employees')->label('Jumlah Pegawai')`

#### 1.2 `ServiceGroupResource`
- **Tabel**: `service_groups`
- **Form Schema**:
  - `Select::make('laboratory_unit_id')->relationship('laboratoryUnit', 'name')->required()->searchable()->preload()`
  - `TextInput::make('code')->required()->unique(ignoreRecord: true)->maxLength(20)->label('Kode Layanan')`
  - `TextInput::make('name')->required()->maxLength(255)->label('Nama Kelompok Layanan (Air Laut, Makanan, dll.)')`
  - `Textarea::make('description')->rows(3)->columnSpanFull()`
  - `Toggle::make('is_active')->default(true)->label('Status Aktif')`
- **Table Columns**:
  - `TextColumn::make('code')->badge()`
  - `TextColumn::make('name')->searchable()->sortable()`
  - `TextColumn::make('laboratoryUnit.name')->label('Instalasi Penanggung Jawab')->sortable()`
  - `IconColumn::make('is_active')->boolean()`
- **Relation Managers**:
  - `TestParametersRelationManager`, `ServicePackagesRelationManager`, `SampleTypesRelationManager`, `TariffsRelationManager`.

#### 1.3 `ServicePackageResource`
- **Tabel**: `service_packages`
- **Form Schema**:
  - `Select::make('service_group_id')->relationship('serviceGroup', 'name')->required()->searchable()`
  - `TextInput::make('name')->required()->maxLength(255)->label('Nama Paket Uji')`
  - `CheckboxList::make('parameters')->relationship('testParameters', 'name')->columns(2)->label('Parameter dalam Paket')`
  - `Toggle::make('is_active')->default(true)`
- **Table Columns**:
  - `TextColumn::make('name')->searchable()->sortable()`
  - `TextColumn::make('serviceGroup.name')->sortable()`
  - `TextColumn::make('test_parameters_count')->counts('testParameters')->label('Jumlah Parameter')`
  - `IconColumn::make('is_active')->boolean()`

#### 1.4 `TestParameterResource`
- **Tabel**: `test_parameters`
- **Form Schema**:
  - `Select::make('service_group_id')->relationship('serviceGroup', 'name')->required()->searchable()`
  - `TextInput::make('name')->required()->label('Nama Parameter Uji (mis. pH, BOD, Coliform)')`
  - `TextInput::make('unit')->label('Satuan Baku (mis. mg/L, CFU/mL, °C)')`
  - `TextInput::make('method')->label('Metode Analisis Baku (SNI, APHA, dll.)')`
  - `Select::make('result_type')->options(['numeric' => 'Angka (Numeric)', 'text' => 'Teks / Kualitatif'])->required()->live()`
  - `Grid::make(2)->schema([
        TextInput::make('threshold_min')->numeric()->label('Batas Minimum (Limit Bawah)')->visible(fn (Get $get) => $get('result_type') === 'numeric'),
        TextInput::make('threshold_max')->numeric()->label('Batas Maksimum (Limit Atas)')->visible(fn (Get $get) => $get('result_type') === 'numeric'),
    ])`
  - `TextInput::make('threshold_text')->label('Keterangan Batas Baku Mutu (Teks)')->visible(fn (Get $get) => $get('result_type') === 'text')`
  - `Toggle::make('is_active')->default(true)`
- **Table Columns**:
  - `TextColumn::make('name')->searchable()->sortable()`
  - `TextColumn::make('serviceGroup.name')->badge()`
  - `TextColumn::make('unit')->placeholder('-')`
  - `TextColumn::make('method')->limit(30)`
  - `TextColumn::make('threshold')->state(fn ($record) => $record->result_type === 'numeric' ? "{$record->threshold_min} - {$record->threshold_max}" : $record->threshold_text)->label('Baku Mutu')`
  - `IconColumn::make('is_active')->boolean()`

#### 1.5 `TariffResource`
- **Tabel**: `tariffs`
- **Business Logic**: Tarif dapat ditentukan per Kelompok Layanan, per Paket, atau per Parameter individu. Memiliki masa berlaku (`effective_from` s.d. `effective_until`).
- **Form Schema**:
  - `Select::make('tariff_type')->options(['group' => 'Per Kelompok Layanan', 'package' => 'Per Paket', 'parameter' => 'Per Parameter'])->live()`
  - `Select::make('service_group_id')->relationship('serviceGroup', 'name')->visible(fn (Get $get) => $get('tariff_type') === 'group')`
  - `Select::make('service_package_id')->relationship('servicePackage', 'name')->visible(fn (Get $get) => $get('tariff_type') === 'package')`
  - `Select::make('test_parameter_id')->relationship('testParameter', 'name')->visible(fn (Get $get) => $get('tariff_type') === 'parameter')`
  - `TextInput::make('amount')->numeric()->prefix('Rp')->required()->label('Nominal Tarif')`
  - `DatePicker::make('effective_from')->required()->label('Berlaku Mulai')`
  - `DatePicker::make('effective_until')->nullable()->label('Berlaku Sampai (Kosongkan bila masih berlaku)')`
- **Table Columns**:
  - `TextColumn::make('target')->state(fn ($record) => $record->testParameter?->name ?? $record->servicePackage?->name ?? $record->serviceGroup?->name)->label('Item Layanan')`
  - `TextColumn::make('amount')->money('IDR', locale: 'id_ID')->sortable()`
  - `TextColumn::make('effective_from')->date()->sortable()`
  - `TextColumn::make('effective_until')->date()->placeholder('Berlaku Sekarang')`

#### 1.6 `CompletionEstimateResource`
- **Tabel**: `completion_estimates`
- **Business Logic**: Durasi estimasi hari kalender (misal: 10 hari sesuai brosur atau 8 hari) dihitung dari titik awal (`payment` atau `receipt`).
- **Form Schema**:
  - `Select::make('service_group_id')->relationship('serviceGroup', 'name')->nullable()->helperText('Kosongkan jika berlaku umum untuk seluruh kelompok layanan')`
  - `TextInput::make('days')->numeric()->required()->minValue(1)->label('Estimasi (Hari Kalender)')`
  - `Select::make('start_point')->options(['payment' => 'Sejak Pembayaran / Registrasi Lunas', 'receipt' => 'Sejak Sampel Diterima Fisik'])->required()`
  - `DatePicker::make('effective_from')->required()`
- **Table Columns**:
  - `TextColumn::make('serviceGroup.name')->placeholder('Semua Kelompok (Default Balai)')`
  - `TextColumn::make('days')->suffix(' Hari Kalender')->sortable()`
  - `TextColumn::make('start_point')->badge()->colors(['info' => 'payment', 'success' => 'receipt'])`
  - `TextColumn::make('effective_from')->date()`

#### 1.7 `SampleTypeResource`
- **Tabel**: `sample_types`
- **Form Schema**:
  - `Select::make('service_group_id')->relationship('serviceGroup', 'name')->required()`
  - `TextInput::make('name')->required()->label('Nama Jenis Sampel (Air Limbah, Usap Alat, Darah, dll.)')`
  - `TextInput::make('min_volume')->numeric()->label('Volume / Bobot Minimum')`
  - `TextInput::make('container_requirement')->label('Persyaratan Wadah (mis. Botol Gelap Steril, Coolbox 4°C)')`
  - `Textarea::make('requirements')->label('Persyaratan Khusus Pengambilan & Pengiriman')`
- **Table Columns**:
  - `TextColumn::make('name')->searchable()->sortable()`
  - `TextColumn::make('serviceGroup.name')->badge()`
  - `TextColumn::make('min_volume')`
  - `TextColumn::make('container_requirement')->limit(40)`

---

### MODUL 2: PELAYANAN & PERMOHONAN (FRONT OFFICE)

#### 2.1 `CustomerResource`
- **Tabel**: `customers`
- **Business Logic**: Pelanggan dapat berupa perorangan, instansi pemerintah/swasta, atau faskes. Nomor HP dienkripsi di database, dan diindeks melalui `phone_hash` untuk verifikasi cepat saat pelacakan/unduh LHU.
- **Form Schema**:
  - `TextInput::make('name')->required()->label('Nama Lengkap Pemohon / Kontak Person')`
  - `TextInput::make('organization_name')->nullable()->label('Nama Instansi / Perusahaan / Faskes')`
  - `Select::make('customer_type')->options(['individual' => 'Perorangan', 'institution' => 'Instansi / Perusahaan', 'health_facility' => 'Fasilitas Kesehatan (RS/Puskesmas)'])->required()`
  - `TextInput::make('phone')->tel()->required()->label('Nomor WhatsApp / HP Aktif')`
  - `TextInput::make('email')->email()->nullable()->label('Email (Opsional)')`
  - `Textarea::make('address')->rows(3)->label('Alamat Lengkap')`
- **Table Columns**:
  - `TextColumn::make('name')->searchable()->sortable()`
  - `TextColumn::make('organization_name')->placeholder('-')->searchable()`
  - `TextColumn::make('customer_type')->badge()`
  - `TextColumn::make('phone')->label('Nomor HP')`
  - `TextColumn::make('test_requests_count')->counts('testRequests')->label('Total Permohonan')`

#### 2.2 `TestRequestResource`
- **Tabel**: `test_requests`
- **Business Logic Kritis**:
  - Nomor permohonan (`request_number`) dan nomor FPPS (`fpps_number`) digenerate secara otomatis dan unik (format: `FPPS/TAHUN/BULAN/XXXX`).
  - Menyimpan snapshot tarif ke `test_request_parameters` sehingga bila master katalog berubah di kemudian hari, nilai permohonan lama tidak terpengaruh.
  - Perhitungan otomatis `total_amount` berdasarkan parameter yang dipilih.
  - Perhitungan dinamis `estimated_completion_date` berdasarkan `completion_estimates`.
  - Jejak riwayat status dicatat ke `status_histories`.
- **Form Schema (Wizard / Section Step-by-Step)**:
  - **Section 1: Identitas & Kanal Permohonan**:
    - `TextInput::make('fpps_number')->disabled()->dehydrated()->label('Nomor FPPS')`
    - `Select::make('channel')->options(['counter' => 'Loket Pelayanan', 'whatsapp' => 'Bantuan WhatsApp', 'online' => 'Pendaftaran Online Mandiri'])->required()`
    - `Select::make('customer_id')->relationship('customer', 'name')->searchable()->preload()->required()->createOptionForm([...])`
    - `TextInput::make('purpose')->required()->label('Tujuan Pengujian (mis. Syarat Izin Edar, Monitoring Kualitas, dll.)')`
  - **Section 2: Layanan & Parameter Uji**:
    - `Select::make('service_group_id')->relationship('serviceGroup', 'name')->required()->live()->afterStateUpdated(fn ($set) => $set('service_package_id', null))`
    - `Select::make('service_package_id')->relationship('servicePackage', 'name', fn ($query, Get $get) => $query->where('service_group_id', $get('service_group_id')))->nullable()->live()`
    - `Repeater::make('orderedParameters')->relationship('requestParameters')->schema([
          Select::make('test_parameter_id')->relationship('testParameter', 'name')->required()->live()->afterStateUpdated(function ($state, Set $set) {
              $tariff = \App\Models\Tariff::where('test_parameter_id', $state)->whereNull('effective_until')->value('amount') ?? 0;
              $set('price', $tariff);
          }),
          TextInput::make('price')->numeric()->prefix('Rp')->required()->label('Harga Snapshot'),
      ])->columns(2)->live()->afterStateUpdated(function (Get $get, Set $set) {
          $total = collect($get('orderedParameters'))->sum('price');
          $set('total_amount', $total);
      })`
    - `TextInput::make('total_amount')->numeric()->prefix('Rp')->readOnly()->label('Total Biaya Retribusi')`
  - **Section 3: Informasi Pengambilan Sampel**:
    - `TextInput::make('sampling_location')->label('Lokasi Pengambilan Sampel (Titik Sampling)')`
    - `DatePicker::make('sampling_date')->label('Tanggal Pengambilan')`
    - `Textarea::make('notes')->rows(2)->label('Catatan Khusus')`
  - **Section 4: Dokumen Pendukung (Storage Privat)**:
    - `FileUpload::make('documents')->disk('local')->directory('test_request_documents')->visibility('private')->multiple()->label('Surat Pengantar / Bukti Sampling')`
- **Table Columns**:
  - `TextColumn::make('fpps_number')->badge()->color('primary')->searchable()->sortable()`
  - `TextColumn::make('customer.name')->searchable()->sortable()`
  - `TextColumn::make('serviceGroup.name')->badge()`
  - `TextColumn::make('total_amount')->money('IDR', locale: 'id_ID')->sortable()`
  - `TextColumn::make('payment_status')->badge()->colors(['warning' => 'unpaid', 'success' => 'paid', 'danger' => 'cancelled'])`
  - `TextColumn::make('status')->badge()->colors([
        'gray' => 'draft',
        'warning' => 'submitted',
        'info' => 'sample_received',
        'primary' => 'in_progress',
        'teal' => 'validated',
        'success' => 'completed',
    ])`
  - `TextColumn::make('estimated_completion_date')->date()->sortable()`
- **Custom Header / Table Actions**:
  - `Action::make('cetak_bukti_pendaftaran')->icon(Heroicon::OutlinedPrinter)->action(...)`
  - `Action::make('update_status')->icon(Heroicon::OutlinedArrowPath)->schema([...])`
- **Relation Managers**:
  - `ParametersRelationManager`, `DocumentsRelationManager`, `PaymentsRelationManager`, `SamplesRelationManager`, `StatusHistoriesRelationManager`.

#### 2.3 `PaymentResource`
- **Tabel**: `payments`
- **Business Logic**: Petugas mencatat nominal yang dibayar, bukti bayar (jika transfer/retribusi daerah). Saat berstatus lunas, `test_requests.payment_status` otomatis terupdate dan tanggal estimasi SLA mulai dihitung (jika konfigurasi SLA start_point = `payment`).
- **Form Schema**:
  - `Select::make('test_request_id')->relationship('testRequest', 'fpps_number')->searchable()->required()->live()`
  - `TextInput::make('amount')->numeric()->prefix('Rp')->required()->label('Jumlah Bayar')`
  - `Select::make('status')->options(['pending' => 'Menunggu Verifikasi', 'paid' => 'Lunas / Diterima', 'failed' => 'Gagal'])->required()`
  - `DateTimePicker::make('paid_at')->default(now())->required()`
- **Table Columns**:
  - `TextColumn::make('testRequest.fpps_number')->label('Nomor FPPS')->searchable()`
  - `TextColumn::make('testRequest.customer.name')->label('Pelanggan')`
  - `TextColumn::make('amount')->money('IDR', locale: 'id_ID')->sortable()`
  - `TextColumn::make('status')->badge()`
  - `TextColumn::make('paid_at')->dateTime()->sortable()`
  - `TextColumn::make('recorder.name')->label('Petugas Pencatat')`

---

### MODUL 3: PENERIMAAN SAMPEL (SAMPLE RECEPTION)

#### 3.1 `SampleResource`
- **Tabel**: `samples`
- **Business Logic**: Sampel fisik yang diserahkan pelanggan. Kode sampel unik di-generate otomatis (`SMP/TAHUN/XXXX`).
- **Form Schema**:
  - `Select::make('test_request_id')->relationship('testRequest', 'fpps_number')->required()->searchable()`
  - `Select::make('sample_type_id')->relationship('sampleType', 'name')->required()`
  - `TextInput::make('sample_code')->required()->unique(ignoreRecord: true)->label('Kode Sampel Balai')`
  - `TextInput::make('sample_name')->required()->label('Nama / Keterangan Sampel (mis. Air Sumur Bor Warga A)')`
  - `Grid::make(2)->schema([
        TextInput::make('quantity')->numeric()->required()->label('Jumlah / Volume Fisik Diserahkan'),
        TextInput::make('unit')->required()->default('mL')->label('Satuan Volume / Bobot'),
    ])`
  - `DateTimePicker::make('collected_at')->label('Waktu Pengambilan Sampel')`
  - `Select::make('status')->options(['pending' => 'Menunggu Fisik', 'received' => 'Diterima di Lab', 'rejected' => 'Ditolak'])->default('pending')`
- **Table Columns**:
  - `TextColumn::make('sample_code')->badge()->searchable()->sortable()`
  - `TextColumn::make('testRequest.fpps_number')->label('Nomor FPPS')->searchable()`
  - `TextColumn::make('sample_name')->searchable()`
  - `TextColumn::make('sampleType.name')->badge()`
  - `TextColumn::make('quantity')->state(fn ($r) => "{$r->quantity} {$r->unit}")`
  - `TextColumn::make('status')->badge()->colors(['warning' => 'pending', 'success' => 'received', 'danger' => 'rejected'])`

#### 3.2 Alur Penerimaan / Penolakan Sampel (`SampleReceiptAction`)
- **Tabel**: `sample_receipts`
- **Tindakan Khusus pada Sampel**:
  - Tombol Aksi **"Verifikasi Penerimaan Fisik"** membuka Modal Dialog Filament:
    - Input: Volume diterima riil, Kondisi fisik (Suhu, Wadah utuh/pecah, Label jelas).
    - Keputusan: `accepted` (Diterima) ATAU `rejected` (Ditolak).
    - Jika `rejected`: Kolom `rejection_reason` dan `follow_up` **wajib diisi**; sampel ditandai ditolak dan permohonan berhenti dari antrean uji.
    - Jika `accepted`: Otomatis memicu pembentukan Surat Perintah Kerja (`test_jobs`) ke instalasi pengujian yang sesuai.

---

### MODUL 4: PENGUJIAN & VALIDASI LABORATORIUM

#### 4.1 `TestJobResource` (Surat Perintah Kerja Pengujian)
- **Tabel**: `test_jobs`
- **Business Logic**: Daftar pekerjaan pengujian per sampel per instalasi lab. Kepala Instalasi/Koordinator dapat menugaskan analis pelaksana (`assigned_to`).
- **Form Schema**:
  - `TextInput::make('job_number')->required()->unique(ignoreRecord: true)->label('Nomor SPK Pengujian')`
  - `Select::make('sample_id')->relationship('sample', 'sample_code')->required()->searchable()`
  - `Select::make('laboratory_unit_id')->relationship('laboratoryUnit', 'name')->required()`
  - `Select::make('assigned_to')->relationship('assignedAnalyst', 'name')->label('Analis Penguji Ditugaskan')->searchable()`
  - `Select::make('status')->options(['pending' => 'Menunggu Analisis', 'in_progress' => 'Sedang Diuji', 'completed' => 'Selesai Uji'])->default('pending')`
  - `DateTimePicker::make('started_at')`
  - `DateTimePicker::make('completed_at')`
- **Table Columns**:
  - `TextColumn::make('job_number')->badge()->searchable()->sortable()`
  - `TextColumn::make('sample.sample_code')->label('Kode Sampel')->searchable()`
  - `TextColumn::make('laboratoryUnit.name')->label('Instalasi')`
  - `TextColumn::make('assignedAnalyst.name')->label('Analis Pelaksana')->placeholder('Belum Ditugaskan')`
  - `TextColumn::make('status')->badge()`
- **Relation Managers**:
  - `TestResultsRelationManager` (Tabel inline untuk menginput seluruh parameter sampel ini).

#### 4.2 `TestResultResource` (Pencatatan Hasil Uji & Komparasi Baku Mutu)
- **Tabel**: `test_results`
- **Business Logic**:
  - Menampilkan parameter yang diuji, satuan, metode analisis.
  - Nilai dapat berupa angka (`value_numeric`) atau teks (`value_text`).
  - Sistem memberikan indikator visual apakah hasil memenuhi baku mutu (`Normal` / `Melewati Ambang Batas`).
  - Status hasil: `draft` -> `submitted` -> `validated` (atau `returned`).
  - Setelah `validated`, record dikunci (`is_locked = true`).
- **Form Schema**:
  - `TextInput::make('parameter_name')->state(fn ($record) => $record->testRequestParameter?->testParameter?->name)->disabled()->label('Parameter Uji')`
  - `Grid::make(2)->schema([
        TextInput::make('value_numeric')->numeric()->label('Hasil Uji (Angka)')->visible(fn ($record) => $record->testRequestParameter?->testParameter?->result_type === 'numeric'),
        TextInput::make('value_text')->label('Hasil Uji (Kualitatif / Teks)')->visible(fn ($record) => $record->testRequestParameter?->testParameter?->result_type === 'text'),
    ])`
  - `TextInput::make('unit')->label('Satuan')`
  - `TextInput::make('method')->label('Metode yang Digunakan')`
  - `DatePicker::make('tested_at')->default(now())->label('Tanggal Selesai Pengujian')`
  - `Textarea::make('notes')->label('Catatan Analis')`
- **Table Columns**:
  - `TextColumn::make('testJob.job_number')->label('SPK')`
  - `TextColumn::make('testRequestParameter.testParameter.name')->label('Parameter')`
  - `TextColumn::make('result_value')->state(fn ($r) => $r->value_numeric ?? $r->value_text)->label('Hasil Uji')`
  - `TextColumn::make('unit')`
  - `TextColumn::make('status')->badge()->colors(['gray' => 'draft', 'info' => 'submitted', 'danger' => 'returned', 'success' => 'validated'])`
  - `IconColumn::make('is_locked')->boolean()->label('Terkunci')`
- **Actions Khusus**:
  - `Action::make('submit_for_validation')->label('Kirim ke Validator')->color('info')->action(...)`
  - `Action::make('validate_result')->label('Validasi (Manager/Rekomendasi)')->color('success')->visible(fn ($r) => auth('employee')->user()->can('result.validate'))->action(...)`
  - `Action::make('revise_result')->label('Koreksi / Revisi Hasil')->color('warning')->visible(fn ($r) => $r->is_locked && auth('employee')->user()->can('result.revise'))->form([...])`

#### 4.3 Alur Validasi Bertahap (`ResultValidation`) & Jejak Revisi (`ResultRevision`)
- **Tahap 1: Validasi Manager Lab (`stage = manager_lab`)**:
  - Memeriksa kesesuaian metode, blanko, akurasi, dan kalkulasi.
  - Pilihan: `approved` (Disetujui) atau `returned` (Kembalikan ke analis dengan catatan perbaikan).
- **Tahap 2: Validasi Rekomendasi (`stage = recommendation`)**:
  - Bila permohonan memerlukan pertimbangan teknis/rekomendasi medis/lingkungan.
- **Audit Jejak Revisi (`ResultRevisionResource`)**:
  - Jika hasil yang telah divalidasi harus dikoreksi karena temuan ketidaksinkronan, form revisi mewajibkan pengisian alasan koreksi (`reason`).
  - Sistem secara otomatis mencatat `old_value`, `new_value`, waktu, dan pegawai pengubah (`revised_by`).

---

### MODUL 5: LAPORAN HASIL UJI (LHU) & PENYERAHAN

#### 5.1 `ReportTemplateResource`
- **Tabel**: `report_templates`
- **Business Logic**: Template Blade / HTML untuk format cetak LHU resmi Balai Labkesmas Ambon. Memuat kop resmi, variabel dinamis, tabel hasil, barcode/QR verifikasi, dan blok tanda tangan.
- **Form Schema**:
  - `TextInput::make('name')->required()->label('Nama Template (Air, Makanan, Udara, Klinis)')`
  - `TextInput::make('version')->required()->default('1.0')`
  - `Textarea::make('content')->rows(15)->required()->label('Struktur Template HTML / Blade')`
  - `Toggle::make('is_active')->default(true)`

#### 5.2 `TestReportResource` (Penerbitan LHU Terkontrol)
- **Tabel**: `test_reports`
- **Business Logic Kritis**:
  - LHU hanya dapat diterbitkan jika **seluruh parameter pengujian telah berstatus `validated`**.
  - Mengenerate nomor resmi LHU (`report_number`) yang unik dan tidak boleh ganda.
  - Membuat snapshot data hasil ke tabel `test_report_items` (`sample_name`, `sample_number`, `parameter`, `unit`, `result`, `method`, `threshold`) agar data di LHU final permanen.
  - Memilih Pejabat Penandatangan: Verifikator Hasil (`verifier_id`), Validator Hasil (`validator_id`), dan Penerbit/Kepala Balai (`issued_by`).
  - Mengenerate file PDF terkontrol ke private storage (`storage/app/private/reports/`).
  - **Mekanisme Revisi Versi**: Bila LHU yang sudah final perlu diubah, sistem membuat baris baru dengan `parent_report_id`, menaikkan nomor versi (`version = 2`), status awal `revised`, sehingga rekam jejak versi sebelumnya tetap tersimpan utuh.
- **Form Schema**:
  - `Select::make('test_request_id')->relationship('testRequest', 'fpps_number')->required()->searchable()->live()`
  - `Select::make('report_template_id')->relationship('reportTemplate', 'name')->required()`
  - `TextInput::make('report_number')->required()->unique(ignoreRecord: true)->label('Nomor LHU')`
  - `Grid::make(3)->schema([
        Select::make('verifier_id')->relationship('verifier', 'name')->required()->label('Verifikator Hasil'),
        Select::make('validator_id')->relationship('validator', 'name')->required()->label('Validator Hasil'),
        Select::make('issued_by')->relationship('issuer', 'name')->required()->label('Pejabat Penerbit / Pengesah'),
    ])`
  - `Textarea::make('considerations')->label('Pertimbangan / Kesimpulan Hasil Uji')`
  - `Select::make('status')->options(['draft' => 'Draf LHU', 'final' => 'LHU Final Terbit', 'revised' => 'Revisi Versi Baru'])->default('draft')`
- **Table Columns**:
  - `TextColumn::make('report_number')->badge()->color('success')->searchable()->sortable()`
  - `TextColumn::make('testRequest.fpps_number')->label('Nomor FPPS')->searchable()`
  - `TextColumn::make('version')->state(fn ($r) => "V{$r->version}")->badge()`
  - `TextColumn::make('status')->badge()->colors(['gray' => 'draft', 'success' => 'final', 'warning' => 'revised'])`
  - `TextColumn::make('issued_at')->dateTime()->sortable()`
- **Actions Khusus**:
  - `Action::make('preview_pdf')->label('Pratinjau PDF')->icon(Heroicon::OutlinedEye)->action(...)`
  - `Action::make('terbitkan_final')->label('Terbitkan LHU Final')->icon(Heroicon::OutlinedCheckBadge)->color('success')->requiresConfirmation()->action(...)`
  - `Action::make('buat_revisi')->label('Terbitkan Versi Revisi')->icon(Heroicon::OutlinedDocumentDuplicate)->color('warning')->action(...)`

#### 5.3 `ReportHandoverResource` (Penyerahan LHU)
- **Tabel**: `report_handovers`
- **Business Logic**: Mencatat waktu dan identitas penerima saat LHU diserahkan secara fisik di loket atau dikirimkan resmi.
- **Form Schema**:
  - `Select::make('test_report_id')->relationship('testReport', 'report_number')->required()->searchable()`
  - `TextInput::make('received_by_name')->required()->label('Nama Lengkap Penerima Hasil')`
  - `DateTimePicker::make('handed_at')->default(now())->required()->label('Waktu Penyerahan')`
  - `Textarea::make('notes')->label('Catatan Penyerahan / Identitas Pengambil')`
- **Table Columns**:
  - `TextColumn::make('testReport.report_number')->label('Nomor LHU')->searchable()`
  - `TextColumn::make('received_by_name')->label('Diterima Oleh')->searchable()`
  - `TextColumn::make('handed_at')->dateTime()->sortable()`
  - `TextColumn::make('handedBy.name')->label('Petugas Loket Penyerah')`

---

### MODUL 6: MANAJEMEN PENGGUNA & KEAMANAN

#### 6.1 `EmployeeResource`
- **Tabel**: `employees`
- **Business Logic**: Akun login pegawai internal. Guard `employee`, satu pegawai dapat memiliki penempatan unit laboratorium dan satu atau lebih Role Spatie.
- **Form Schema**:
  - `TextInput::make('name')->required()->label('Nama Lengkap & Gelar')`
  - `TextInput::make('nip')->nullable()->unique(ignoreRecord: true)->label('NIP Pegawai')`
  - `TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->label('Email Dinas / Login')`
  - `TextInput::make('phone')->tel()->label('Nomor Telepon')`
  - `Select::make('laboratory_unit_id')->relationship('laboratoryUnit', 'name')->nullable()->label('Unit / Instalasi Penempatan')`
  - `Select::make('roles')->relationship('roles', 'name', fn ($q) => $q->where('guard_name', 'employee'))->multiple()->preload()->required()->label('Peran (Role RBAC)')`
  - `TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create')`
  - `Toggle::make('is_active')->default(true)->label('Status Akun Aktif')`
- **Table Columns**:
  - `TextColumn::make('name')->searchable()->sortable()`
  - `TextColumn::make('nip')->placeholder('-')->searchable()`
  - `TextColumn::make('email')->searchable()`
  - `TextColumn::make('laboratoryUnit.name')->badge()->placeholder('Non-Laboratorium')`
  - `TextColumn::make('roles.name')->badge()->color('info')`
  - `IconColumn::make('is_active')->boolean()`
  - `TextColumn::make('last_login_at')->dateTime()->sortable()`

#### 6.2 `RoleResource` & `PermissionResource`
- Manajemen Peran dan Izin berbasis Spatie Laravel Permission (khusus guard `employee`).
- Menjamin pemisahan tugas (Separation of Duties) antar petugas loket, penerima sampel, analis, validator, dan pimpinan.

---

### MODUL 7: JEJAK AUDIT & LOG SISTEM

#### 7.1 `DocumentAccessLogResource`
- **Tabel**: `document_access_logs`
- **Karakteristik**: Read-only, tanpa form create/edit.
- **Table Columns**:
  - `TextColumn::make('testReport.report_number')->label('Nomor LHU')->searchable()`
  - `TextColumn::make('actor')->state(fn ($r) => $r->customer?->name ? "Pelanggan: {$r->customer->name}" : "Pegawai: {$r->employee?->name}")->label('Pengakses')`
  - `TextColumn::make('action')->badge()->colors(['info' => 'view', 'success' => 'download', 'primary' => 'print'])`
  - `TextColumn::make('ip_address')->label('Alamat IP')`
  - `TextColumn::make('accessed_at')->dateTime()->sortable()`

#### 7.2 `ActivityLogResource`
- **Tabel**: `activity_logs`
- **Karakteristik**: Read-only log audit untuk melacak aksi perubahan data penting oleh pegawai atau sistem.

---

## 5. Dashboard Analitik & Widget Khusus [Fase Lanjutan - Diakhirkan]

> [!NOTE]
> **Status: Diakhirkan ke Fase Lanjutan.**
> Sesuai arahan, pengembangan tahap awal difokuskan penuh pada **tata kelola transaksi layanan operasional**.
> Pada tahap awal ini, panel admin Filament menggunakan dashboard bawaan yang bersih (`Filament\Pages\Dashboard::class`). 
> Widget visual, grafik statistik, dan indikator KPI di bawah ini disimpan sebagai cetak biru untuk diterapkan pada **Fase Lanjutan** setelah seluruh data permohonan, sampel, pengujian, dan LHU aktif digunakan:

### 5.1 Rencana Widget Petugas Pelayanan (`service_officer`) - *Fase Lanjutan*
- **Widget StatsOverview**: Permohonan baru hari ini, menunggu verifikasi bayar, menunggu sampel, LHU siap diserahkan.
- **Widget Table Antrean Pelayanan**: Tabel antrean permohonan yang membutuhkan tindakan tindak lanjut.

### 5.2 Rencana Widget Laboratorium (`lab_officer`) - *Fase Lanjutan*
- **Widget StatsOverview**: Antrean pekerjaan aktif, sedang diuji, menunggu validasi Manager Lab, hasil dikembalikan.
- **Widget Critical Alert (SLA Monitoring)**: Daftar sampel yang mendekati batas waktu (H-2) atau melewati estimasi penyelesaian.
- **Widget Distribusi Pekerjaan per Instalasi (Chart Widget)**: Grafik batang beban kerja di Kimia, Mikrobiologi, Patologi, dan Lingkungan.

### 5.3 Rencana Widget Eksekutif Pimpinan (`leader`) - *Fase Lanjutan*
- **Widget KPI Eksekutif**: Total permohonan bulanan, persentase kepatuhan SLA (On-Time %), rata-rata durasi penyelesaian, dan PNBP/retribusi.
- **Widget Distribusi 17 Kelompok Layanan Brosur (Pie/Donut Chart)**: Proporsi sampel Air Laut, Makanan, Air Bersih, dll.
- **Tabel Kendala & Antrean Prioritas**: Pemantauan pekerjaan yang mengalami kendala teknis atau retur berulang.

---

## 6. Modul Laporan & Rekapitulasi Operasional (Bab 4 PRD)

Sebagai ganti widget visual, fokus operasional awal adalah penyediaan **Halaman Rekapitulasi & Ekspor Data** (`App\Filament\Pages\ReportsPage.php`) untuk rekap operasional harian:
1. **Filter Fleksibel**:
   - Rentang Tanggal (Dari - Sampai).
   - Kelompok Layanan (Air Laut, Makanan, Vektor, dll.).
   - Instalasi / Unit Laboratorium.
   - Status Permohonan & Status Pembayaran.
2. **Katalog Laporan Operasional yang Dihasilkan**:
   - **Rekap Permohonan & Sampel**: Detail nomor FPPS, tanggal daftar, pemohon, kelompok uji, dan status progres.
   - **Rekap 17 Jenis Layanan Brosur**: Rekapitulasi agregat volume pemeriksaan per kategori brosur.
   - **Rekap Waktu Penyelesaian & Kepatuhan SLA**: Analisis waktu mulai bayar/terima sampel hingga terbit LHU, mengidentifikasi sampel on-time vs overdue.
   - **Rekap Penerimaan Sampel**: Statistik sampel diterima vs ditolak beserta persentase alasan penolakan.
   - **Rekap LHU & Penyerahan**: Nomor LHU terbit, versi/revisi, tanggal pengesahan, dan nama penerima hasil.
3. **Format Unduhan**:
   - **Excel (.xlsx)** via Spout / Laravel-Excel untuk analisis data lanjutan.
   - **PDF Resmi** dengan format rekap ber-kop surat resmi Balai Labkesmas Ambon.

---

## 7. Aturan Bisnis Kritis & Integritas Data (PRD Compliance)

1. **Penyimpanan Dokumen Terkontrol (Private Storage)**:
   - Dokumen permohonan dan file PDF LHU **tidak boleh** ditaruh di folder `public/storage`.
   - Menggunakan disk privat (`storage/app/private/`) yang hanya dapat diakses melalui controller otorisasi Filament atau URL bertanda tangan (signed URL) sementara.
2. **Kekebalan Data LHU Final (Data Immutability)**:
   - Sekali LHU disahkan (`status = final`), baris hasil uji di `test_report_items` terkunci permanen.
   - Jika ada koreksi hasil, tidak boleh ada penimpaan langsung (no direct edit/overwrite); wajib melalui alur `buat_revisi` yang menghasilkan nomor versi baru (V2, V3) dengan referensi ke record lama (`parent_report_id`).
3. **Perlindungan Data Pribadi Pelanggan**:
   - Nomor telepon pelanggan disimpan terenkripsi.
   - Tampilan di ringkasan data tidak memunculkan data pribadi atau hasil uji klinis rahasia secara terbuka kepada peran yang tidak berwenang.
4. **Pencegahan Data Sampel Ditolak**:
   - Sampel dengan keputusan `rejected` secara sistem diblokir agar tidak dapat dibuatkan SPK pengujian (`test_jobs`).

---

## 8. Rencana Tahapan Eksekusi (Roadmap Pelaksanaan)

Rencana aksi difokuskan pada **6 Fase Tata Kelola Layanan Operasional**, diikuti oleh **Fase Lanjutan** untuk analitik:

```
┌────────┐     ┌────────┐     ┌────────┐     ┌────────┐     ┌────────┐     ┌────────┐     ═══════════════════════
│ FASE 1 │ ──> │ FASE 2 │ ──> │ FASE 3 │ ──> │ FASE 4 │ ──> │ FASE 5 │ ──> │ FASE 6 │ ──> ║    FASE LANJUTAN      ║
│Fondasi │     │Master  │     │Front   │     │Uji &   │     │LHU &   │     │Rekap & │     ║ (Diakhirkan)          ║
│& RBAC  │     │Katalog │     │Office  │     │Validasi│     │Penye-  │     │Uji E2E │     ║ Dashboard Analitik,   ║
│        │     │        │     │        │     │        │     │rahan   │     │(Pint)  │     ║ Widget KPI & Grafik   ║
└────────┘     └────────┘     └────────┘     └────────┘     └────────┘     └────────┘     ═══════════════════════
```

### Fase 1: Fondasi Panel & Otorisasi RBAC (Hari 1)
- Konfigurasi branding, warna, guard `employee`, dan discover path di [AdminPanelProvider.php](file:///c:/laragon/www/app-publik/app/Providers/Filament/AdminPanelProvider.php).
- Pembuatan Laravel Policies (`EmployeePolicy`, `RolePolicy`, dll.) yang terhubung ke permission Spatie (guard `employee`).
- Pembuatan `EmployeeResource`, `RoleResource`, dan `PermissionResource`.

### Fase 2: Master Data & Klaster Katalog Layanan (Hari 2)
- Pembuatan Cluster `CatalogCluster`.
- Implementasi 7 Resource Katalog: `LaboratoryUnitResource`, `ServiceGroupResource`, `ServicePackageResource`, `TestParameterResource`, `TariffResource`, `CompletionEstimateResource`, `SampleTypeResource`.
- Integrasi relasi parameter ke paket dan logika penentuan tarif berlaku.

### Fase 3: Front Office, Pembayaran & Penerimaan Sampel (Hari 3)
- Pembuatan `CustomerResource`.
- Pembuatan `TestRequestResource` dengan Form Permohonan, snapshot harga parameter, auto-generate FPPS, dan dokumen pendukung privat.
- Pembuatan `PaymentResource` dan auto-update status tagihan serta estimasi SLA.
- Pembuatan `SampleResource` dan modal aksi verifikasi fisik `SampleReceipt` (Terima / Tolak dengan alasan wajib).

### Fase 4: Pengujian & Validasi Laboratorium (Hari 4)
- Pembuatan `TestJobResource` (Surat Perintah Kerja per instalasi).
- Pembuatan `TestResultResource` dengan auto-komparasi nilai terhadap baku mutu (threshold) dan status terkunci.
- Implementasi workflow aksi Validasi Bertahap (*Manager Lab* & *Rekomendasi*).
- Implementasi modal koreksi berjejak audit `ResultRevisionResource`.

### Fase 5: Penerbitan LHU, Cetak PDF & Penyerahan (Hari 5)
- Pembuatan `ReportTemplateResource`.
- Pembuatan `TestReportResource` dengan generator nomor LHU unik, snapshot ke `test_report_items`, dan generator PDF terkontrol.
- Implementasi alur revisi berversi (V1 -> V2).
- Pembuatan `ReportHandoverResource` untuk rekam jejak serah terima hasil.
- Pencatatan log akses otomatis ke `document_access_logs`.

### Fase 6: Rekapitulasi Operasional, Pengujian Terpadu & Standarisasi (Hari 6)
- Pembuatan Custom Page / Exporter `ReportsPage` (Filter periode, 17 kelompok layanan, ekspor XLSX & PDF).
- Pembuatan Feature Tests (PHPUnit) untuk menguji otorisasi per role pada setiap Resource alur layanan.
- Menjalankan Laravel Pint (`vendor/bin/pint --format agent`) untuk standarisasi kode.
- Uji coba end-to-end simulasi alur: Pendaftaran -> Bayar -> Terima Sampel -> Uji -> Validasi -> Terbit LHU -> Serah Terima.

### ─── FASE LANJUTAN (Pasca Tata Kelola Layanan Stabil) ───
- Pembuatan Custom Dashboard Widgets (StatsOverview, Chart Distribusi Brosur, Monitoring SLA).
- Analitik KPI Eksekutif Pimpinan dan visualisasi tren waktu penyelesaian pengujian.

---

## 9. Katalog Perintah Artisan Filament v5 yang Akan Digunakan

### 9.1 Perintah Utama (Fase 1 s.d. Fase 6 - Tata Kelola Layanan)
```bash
# Cluster Katalog Layanan
php artisan make:filament-cluster CatalogCluster --no-interaction

# Resources Katalog (dalam Cluster)
php artisan make:filament-resource LaboratoryUnit --cluster=CatalogCluster --no-interaction
php artisan make:filament-resource ServiceGroup --cluster=CatalogCluster --no-interaction
php artisan make:filament-resource ServicePackage --cluster=CatalogCluster --no-interaction
php artisan make:filament-resource TestParameter --cluster=CatalogCluster --no-interaction
php artisan make:filament-resource Tariff --cluster=CatalogCluster --no-interaction
php artisan make:filament-resource CompletionEstimate --cluster=CatalogCluster --no-interaction
php artisan make:filament-resource SampleType --cluster=CatalogCluster --no-interaction

# Resources Front Office & Pelayanan
php artisan make:filament-resource Customer --no-interaction
php artisan make:filament-resource TestRequest --view --no-interaction
php artisan make:filament-resource Payment --no-interaction

# Resources Penerimaan Sampel
php artisan make:filament-resource Sample --no-interaction

# Resources Laboratorium & Validasi
php artisan make:filament-resource TestJob --no-interaction
php artisan make:filament-resource TestResult --no-interaction
php artisan make:filament-resource ResultRevision --no-interaction

# Resources LHU & Penyerahan
php artisan make:filament-resource ReportTemplate --no-interaction
php artisan make:filament-resource TestReport --view --no-interaction
php artisan make:filament-resource ReportHandover --no-interaction

# Resources Keamanan & Log
php artisan make:filament-resource Employee --no-interaction
php artisan make:filament-resource DocumentAccessLog --no-interaction
php artisan make:filament-resource ActivityLog --no-interaction

# Halaman Rekapitulasi & Ekspor Operasional
php artisan make:filament-page ReportsPage --no-interaction
```

### 9.2 Perintah untuk Fase Lanjutan (Dashboard Analitik & Widget)
```bash
# [Fase Lanjutan] Dashboard Widgets & Analitik Visual
php artisan make:filament-widget StatsOverviewWidget --no-interaction
php artisan make:filament-widget ServiceDistributionChartWidget --no-interaction
php artisan make:filament-widget SlaMonitoringTableWidget --no-interaction
```

---

## 10. Status Implementasi & Verifikasi Akhir

> [!NOTE]
> Seluruh fase implementasi tata kelola layanan berbasis Filament v5 telah **selesai dieksekusi 100%**:
> 
> - **Fase 1 (Fondasi & RBAC)**: `AdminPanelProvider`, `EmployeeResource`, `RoleResource`, `PermissionResource`, beserta seluruh Policy RBAC.
> - **Fase 2 (Master Data & Klaster Layanan)**: `CatalogCluster` dengan 7 resources lengkap (`LaboratoryUnit`, `ServiceGroup`, `ServicePackage`, `TestParameter`, `Tariff`, `CompletionEstimate`, `SampleType`).
> - **Fase 3 (Front Office & Penerimaan Sampel)**: `CustomerResource`, `TestRequestResource` (auto no. FPPS & request), `PaymentResource` (auto lunas & SLA), `SampleResource` (verifikasi terima/tolak).
> - **Fase 4 (Pengujian & Validasi Laboratorium)**: `TestJobResource` (SPK), `TestResultResource` (input, multi-stage validasi, revisi berjejak), `ResultRevisionResource` (audit trail).
> - **Fase 5 (Penerbitan LHU & Penyerahan)**: `ReportTemplateResource`, `TestReportResource` (penomoran LHU unik, aksi terbitkan, revisi v1->v2), `ReportHandoverResource` (tanda terima fisik/resi), `DocumentAccessLogResource`, `ActivityLogResource`.
> - **Fase 6 (Rekapitulasi Operasional & Verifikasi)**: Custom Page `OperationalReports` (17 kelompok layanan, kepatuhan SLA, analisis penolakan sampel), 18/18 feature tests lolos (100% pass), standarisasi kode dengan Laravel Pint.

