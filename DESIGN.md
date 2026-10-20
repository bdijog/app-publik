---
name: Labkesmas Ambon Public Portal
version: 1.0.0
description: Official design system specification and public UI screens for Balai Laboratorium Kesehatan Masyarakat Ambon (Kemenkes RI).
colors:
  primary: "#0F766E"
  primary-hover: "#115E59"
  primary-light: "#F0FDFA"
  primary-muted: "#CCFBF1"
  secondary: "#0F172A"
  secondary-light: "#1E293B"
  surface: "#FFFFFF"
  surface-hover: "#F8FAFC"
  background: "#F8FAFC"
  on-surface: "#0F172A"
  on-primary: "#FFFFFF"
  border: "#E2E8F0"
  border-hover: "#CBD5E1"
  accent: "#0284C7"
  accent-light: "#E0F2FE"
  success: "#16A34A"
  success-light: "#DCFCE7"
  warning: "#D97706"
  warning-light: "#FEF3C7"
  danger: "#DC2626"
  danger-light: "#FEE2E2"
  muted: "#64748B"
  muted-light: "#94A3B8"
typography:
  fontFamily: "'Plus Jakarta Sans', system-ui, -apple-system, sans-serif"
  h1:
    fontSize: "2.75rem"
    fontWeight: 800
    lineHeight: 1.15
  h2:
    fontSize: "2rem"
    fontWeight: 700
    lineHeight: 1.25
  h3:
    fontSize: "1.5rem"
    fontWeight: 600
    lineHeight: 1.3
  h4:
    fontSize: "1.125rem"
    fontWeight: 600
    lineHeight: 1.4
  body-lg:
    fontSize: "1.125rem"
    fontWeight: 400
    lineHeight: 1.6
  body-md:
    fontSize: "0.9375rem"
    fontWeight: 400
    lineHeight: 1.6
  body-sm:
    fontSize: "0.8125rem"
    fontWeight: 400
    lineHeight: 1.5
  caption:
    fontSize: "0.75rem"
    fontWeight: 500
    lineHeight: 1.4
rounded:
  sm: "6px"
  md: "10px"
  lg: "16px"
  xl: "20px"
  2xl: "24px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
  2xl: "48px"
  3xl: "64px"
components:
  button:
    primary-bg: "{colors.primary}"
    primary-hover: "{colors.primary-hover}"
    primary-text: "{colors.on-primary}"
    radius: "{rounded.lg}"
    padding: "12px 24px"
  card:
    bg: "{colors.surface}"
    border: "{colors.border}"
    radius: "{rounded.2xl}"
    shadow: "0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)"
    padding: "{spacing.lg}"
  input:
    bg: "{colors.surface}"
    border: "{colors.border}"
    border-focus: "{colors.primary}"
    radius: "{rounded.lg}"
    padding: "10px 14px"
  badge:
    radius: "{rounded.full}"
    padding: "4px 10px"
    font-size: "{typography.caption.fontSize}"
---

# SISTEM DESAIN ANTARMUKA PUBLIK: BALAI LABKESMAS AMBON

## 1. Ikhtisar & Identitas Brand (Brand Identity)

Aplikasi Web Publik **Balai Laboratorium Kesehatan Masyarakat (Labkesmas) Ambon** merupakan sarana pelayanan pengujian laboratorium kesehatan dan lingkungan terakreditasi di bawah naungan Kementerian Kesehatan Republik Indonesia. 

Sistem desain ini ditujukan untuk memberikan pengalaman pengguna (*user experience*) yang:
- **Tepercaya & Resmi (Trustworthy & Clinical)**: Memancarkan wibawa institusi pelayanan publik kesehatan dengan standar mutu tinggi dan akreditasi ISO/IEC 17025.
- **Transparan & Mudah Digunakan (Accessible & Transparent)**: Memudahkan masyarakat, rumah sakit, puskesmas, instansi pemerintah, dan pelaku usaha dalam menelusuri katalog layanan, memeriksa tarif resmi, mengajukan permohonan pengujian secara mandiri, serta memantau progres sampel secara real-time.
- **Aman & Menjaga Privasi (Secure & Confidential)**: Menjaga kerahasiaan data hasil uji dan sertifikat Laporan Hasil Uji (LHU) dengan otentikasi ganda (Pencocokan Nomor FPPS + Nomor HP Terdaftar).

---

## 2. Palet Warna & Panduan Penggunaan (Color System)

| Token Warna | Nilai HEX | Peran & Penggunaan |
|---|---|---|
| `primary` | `#0F766E` | Warna identitas utama (Teal). Digunakan pada tombol aksi utama, header navigasi, link aktif, dan ikon utama. |
| `primary-hover` | `#115E59` | Status hover dan fokus pada tombol interaktif utama. |
| `primary-light` | `#F0FDFA` | Latar belakang aksen ringan, kontainer info khusus, dan badge kategori aktif. |
| `secondary` | `#0F172A` | Slate gelap untuk judul utama, teks kontras tinggi, dan elemen footer berwibawa. |
| `surface` | `#FFFFFF` | Latar belakang kartu (*card*), modal, dan kontainer formulir. |
| `background` | `#F8FAFC` | Latar belakang halaman publik global (Slate 50). |
| `border` | `#E2E8F0` | Garis pembatas kartu halus (Slate 200). |
| `accent` | `#0284C7` | Sky Blue untuk elemen progres air bersih dan indikator timeline yang sedang berjalan (*in-progress*). |
| `success` | `#16A34A` | Hijau emerald untuk status "Sampel Diterima", "LHU Terbit", dan verifikasi keaslian dokumen. |
| `warning` | `#D97706` | Amber untuk status "Menunggu Konfirmasi", "Menunggu Pembayaran", atau "Validasi Uji". |
| `danger` | `#DC2626` | Merah untuk status "Sampel Ditolak", "Tidak Memenuhi Syarat", atau "Gagal Verifikasi". |

---

## 3. Tipografi & Skala Teks (Typography Scale)

Menggunakan keluarga font modern **Plus Jakarta Sans** (atau fallback Inter) dengan keterbacaan tinggi di berbagai resolusi layar:

- **Display / Hero (`h1`)**: 44px (Desktop) / 32px (Mobile), ExtraBold 800, `tracking-tight`.
- **Judul Seksi (`h2`)**: 32px (Desktop) / 24px (Mobile), Bold 700.
- **Sub-judul / Judul Kartu (`h3`)**: 24px (Desktop) / 20px (Mobile), SemiBold 600.
- **Judul Grup / Label Tebal (`h4`)**: 18px, SemiBold 600.
- **Teks Paragraf Utama (`body-lg`)**: 18px, Regular 400, `leading-relaxed`.
- **Teks Paragraf Standar (`body-md`)**: 15px, Regular 400, `leading-normal`.
- **Teks Keterangan / Form Helper (`body-sm`)**: 13px, Regular 400.
- **Badge & Meta Info (`caption`)**: 12px, Medium 500, `uppercase tracking-wider`.

---

## 4. Pola Tata Letak & Spasi (Layout & Grid)

- **Bento-Grid Architecture**: Kartu-kartu layanan dan metrik pelacakan disusun menggunakan bento-grid fleksibel (1 kolom pada mobile, 2-3 kolom pada tablet/desktop).
- **Radius Sudut Halus**: Menggunakan `rounded-2xl` (24px) untuk kartu konten utama guna memberikan kesan modern, ramah, dan tidak kaku.
- **Efek Bayangan (Elevation)**: Bayangan halus `shadow-sm` saat diam, bertransisi mulus ke `shadow-md` saat disentuh atau di-*hover* pengguna.
- **Responsivitas**: Optimal pada desktop 1440px dan perangkat smartphone 390px (mobile-first layout).

---

## 5. Layar & Alur Pengguna Publik (Screen Specifications)

### SCR-01: Landing Page & Beranda Publik
- **Tujuan**: Memperkenalkan Balai Labkesmas Ambon, menyediakan pelacakan cepat (*Quick FPPS Search*), eksplorasi 17 kelompok layanan, alur 4 langkah permohonan, dan kontak pelayanan.
- **Komponen Kunci**:
  - *Sticky Navbar* dengan logo Kemenkes/Labkesmas, menu navigasi, dan tombol aksi "Lacak Hasil FPPS".
  - *Hero Section* dengan headline akreditasi dan dual search card (Lacak FPPS & Cari Parameter Uji).
  - *Bento Grid Layanan Unggulan* (Air Bersih, Makanan, Vektor, Udara, Patologi Klinis, Kalibrasi).
  - *4-Step Infografis Alur Permohonan* (Daftar -> Kirim Sampel -> Uji Lab -> Unduh LHU).
  - *Footer* berisi alamat kantor di Ambon, jam operasional loket, WhatsApp helpdesk, dan tautan regulasi.

### SCR-02: Katalog Layanan & Simulasi Tarif Interaktif
- **Tujuan**: Menampilkan seluruh katalog pemeriksaan (17 kelompok layanan) dengan transparansi parameter, tarif resmi, persyaratan wadah, volume minimum sampel, dan estimasi waktu selesai (SLA).
- **17 Kelompok Layanan Sesuai PRD**:
  1. Air Laut
  2. Air Limbah Domestik
  3. Air Limbah Faskes
  4. Vektor Penyakit
  5. Makanan & Minuman
  6. Swab Alat
  7. Air Bersih
  8. Air Sungai
  9. Air Kolam Renang
  10. Bakteriologi Udara
  11. Udara Ambien
  12. Udara Ruangan
  13. Patologi Klinis
  14. Air Minum
  15. Penelitian (D3 / S1 / S2)
  16. Mikrobiologi, Parasitologi & Biomolekuler
  17. Kalibrasi Alat Kesehatan/Lab
- **Fitur Khusus**:
  - Filter kategori berbentuk tombol pil interaktif (*category pills*).
  - Pencarian parameter instan (misal: "E. Coli", "Boraks", "pH", "Timbal").
  - *Sticky Cost Estimation Bar* di bagian bawah yang menghitung total estimasi biaya saat parameter dipilih.

### SCR-03: Formulir Pendaftaran Permohonan Pengujian Online (Wizard)
- **Tujuan**: Memandu pemohon mendaftarkan sampel secara mandiri dengan alur bertahap tanpa kebingungan.
- **5 Langkah Wizard**:
  1. *Profil Pemohon*: Pilihan jenis pemohon [Perorangan / Instansi / Faskes], Nama Pemohon, Nama Instansi, No. WhatsApp Aktif (wajib valid untuk verifikasi LHU), Email, Alamat Lengkap.
  2. *Pilihan Layanan & Parameter*: Dropdown kelompok layanan, mode paket uji atau kustom parameter, serta kalkulator rincian biaya real-time.
  3. *Informasi Sampel Fisik*: Nama/Kode sampel, titik/lokasi sampling, tanggal & waktu pengambilan sampel, jumlah wadah/volume, serta upload surat permohonan/tugas (PDF/JPG).
  4. *Metode Penyerahan Sampel*: Pilihan penyerahan [Diantar Langsung ke Loket Balai di Ambon] atau [Dikirim via Ekspedisi/Kurir Rantai Dingin], panduan pengemasan wadah steril, dan persetujuan pernyataan keabsahan.
  5. *Layar Sukses & Bukti Registrasi*: Penerbitan Nomor FPPS resmi (mis. `FPPS-2026-AMB-0142`), QR Code untuk scan cepat di loket, panduan transfer pembayaran, serta tombol cetak bukti pendaftaran PDF.

### SCR-04: Lacak Status Sampel Publik (FPPS Tracker)
- **Tujuan**: Pelacakan progres pengerjaan sampel berdasarkan Nomor FPPS.
- **Mekanisme Privasi PRD**: Menampilkan tahapan pengerjaan tanpa membocorkan nilai hasil uji yang bersifat rahasia.
- **Tahapan Progres (Stepper)**:
  1. *Permohonan Didaftarkan* (Tanggal & Waktu)
  2. *Penerimaan & Pemeriksaan Sampel* (Kondisi sampel diterima atau ditolak beserta alasan)
  3. *Pengujian Laboratorium* (Sedang dikerjakan analis laboratorium terkait)
  4. *Validasi Hasil Bertahap* (Validasi teknis Manager Lab & Rekomendasi)
  5. *LHU Terbit & Siap Diambil/Diunduh*
- **Aksi Tersedia**: Unduh Bukti Progres Sementara (PDF) dan tautan menuju Gerbang Verifikasi LHU Final.

### SCR-05: Gerbang Verifikasi & Unduh Dokumen LHU Final
- **Tujuan**: Otentikasi ganda untuk melindungi data pribadi dan medis pemohon.
- **Validasi Ganda**: Pemohon wajib memasukkan **Nomor FPPS** DAN **Nomor HP Terdaftar**. Sistem mencocokkan nomor telepon dengan `phone_hash` pada database `customers`.
- **Tampilan Berhasil Verifikasi**:
  - Banner status sertifikat resmi siap unduh.
  - Metadata LHU: Nomor LHU resmi, tanggal pengesahan, nama instansi pemohon, nama verifikator dan validator (Kepala Balai/Dokter Validator).
  - Segel Keabsahan: Tanda tangan elektronik tersertifikasi & segel pengaman QR Code.
  - Tombol Unduh: "Unduh Dokumen LHU Resmi (PDF)" dan "Unduh Rekap Riwayat Progres".

### SCR-06: Portal Pelanggan / Dashboard Faskes (Opsional)
- **Tujuan**: Area login mandiri bagi pelanggan rutin (Puskesmas, RSUD, Dinas Kesehatan, Pelaku Industri).
- **Fitur Kunci**: Metrik permohonan aktif, daftar seluruh riwayat pengujian lampau, filter status pembayaran (lunas/belum lunas), dan arsip seluruh dokumen LHU yang pernah diterbitkan.

### SCR-07: Halaman Publik Verifikasi Keaslian LHU (Scan QR Code)
- **Tujuan**: Halaman yang terbuka saat pihak ketiga atau instansi memeriksa keaslian dokumen fisik LHU dengan memindai QR Code tercetak.
- **Tampilan**: Indikator status dokumen "DOKUMEN RESMI TERVERIFIKASI" dengan segel centang hijau, menampilkan nomor LHU, tanggal penerbitan, nama instansi pemohon, serta identitas pejabat penandatangan tanpa menampilkan angka hasil uji rahasia.

---

## 6. Prompt Siap Pakai di Canvas Google Stitch

Salin dan tempel prompt di bawah ini ke canvas Google Stitch untuk menghasilkan layar antarmuka:

### Prompt 1: Landing Page (SCR-01)
```text
Create a modern, highly trustworthy, responsive homepage for a government public health and environmental testing laboratory named "Balai Laboratorium Kesehatan Masyarakat Ambon (Balai Labkesmas Ambon)".
Design language: Clean healthcare aesthetic, primary colors in deep teal (#0F766E), slate accents (#0F172A), soft background (#F8FAFC), typography using Plus Jakarta Sans, rounded-2xl containers, subtle borders, and smooth shadows.
Include:
1. Header Navbar: Logo, Navigation Links (Beranda, Katalog Layanan, Lacak Sampel, Pendaftaran Online, Kontak), and CTA "Lacak Hasil FPPS".
2. Hero Section: Headline "Layanan Pengujian Laboratorium Kesehatan & Lingkungan Terakreditasi di Maluku", subheadline, Dual Search Card (Quick FPPS Search & Parameter Search), and Trust Badges (KAN ISO/IEC 17025, Kemenkes RI).
3. Bento Grid of 6 Core Services: Air Bersih & Minum, Makanan, Vektor Penyakit, Udara, Patologi Klinis, and Kalibrasi Alat with icons, parameter samples, and SLA badges.
4. 4-Step How-It-Works Infographic: (1) Daftar Online, (2) Serahkan Sampel, (3) Uji Lab & Validasi, (4) Unduh LHU Digital.
5. Footer: Official address in Ambon, WhatsApp hotline button, operational hours, FAQ, and privacy policy.
```

### Prompt 2: Service Catalog & Pricing (SCR-02)
```text
Design an interactive Service Catalog & Pricing Page for "Balai Laboratorium Kesehatan Masyarakat Ambon".
Style: Clinical, transparent bento-grid, deep teal (#0F766E) and crisp slate.
Include:
1. Search & Filter Header: Search input for parameters (pH, Coliform, Boraks, etc.) and category pills for 17 service groups: Air Laut, Air Limbah Domestik, Air Limbah Faskes, Vektor, Makanan, Swab Alat, Air Bersih, Air Sungai, Air Kolam Renang, Bakteriologi Udara, Udara Ambien, Udara Ruangan, Patologi Klinis, Air Minum, Penelitian, Mikrobiologi/Biomolekuler, Kalibrasi.
2. Interactive Catalog Cards: Category badge, Service Name, Parameter tags, Sample requirements box (minimum volume and container type), SLA badge (e.g. "8-10 Hari Kerja"), official tariff in IDR (e.g. "Rp 503.000"), and "Pilih & Ajukan Uji" button.
3. Sticky Bottom Estimation Bar: Displays count of selected tests, total estimated cost in Rupiah, and "Lanjutkan ke Pendaftaran ->" button.
```

### Prompt 3: Registration Wizard Form (SCR-03)
```text
Design a 5-step responsive Wizard Form for laboratory test sample registration for "Balai Labkesmas Ambon".
Visual style: Distraction-free, structured stepper at the top, clear validation states.
Steps:
1. Step 1 - Profil Pemohon: Radio selector for applicant type [Perorangan | Instansi | Faskes], Full Name, Organization Name, Active WhatsApp Number (with badge: "Digunakan untuk verifikasi LHU"), Email, and Address.
2. Step 2 - Layanan & Parameter: Service group dropdown, selection between Standard Package or Custom Parameter checklist, and dynamic price summary card.
3. Step 3 - Informasi Sampel Fisik: Sample Name, Sampling Location, Sampling Date & Time, Container Count/Volume, and file dropzone for request letter (PDF/JPG).
4. Step 4 - Penyerahan & Persetujuan: Choice between [Diantar ke Loket Balai Ambon] or [Kirim via Ekspedisi Rantai Dingin], packaging instructions, and mandatory agreement checkbox.
5. Step 5 - Bukti Registrasi (Success): Big success checkmark, generated FPPS Number ("FPPS-2026-AMB-0142"), Request ID, QR Code for counter scan, bank transfer details, and "Cetak Bukti Pendaftaran (PDF)" button.
```

### Prompt 4: Public FPPS Tracker (SCR-04)
```text
Design a Public Sample Tracking Page for "Balai Laboratorium Kesehatan Masyarakat Ambon".
Style: Trustworthy dashboard, horizontal & vertical progress stepper, teal and slate colors.
Include:
1. FPPS Search Input Card: "Masukkan Nomor FPPS Anda" with a "Lacak Sampel" button.
2. Found Result Card: Displays FPPS Number, Service Name, Registration Date, Status Badge ("Sedang Dalam Pengujian Laboratorium"), and SLA Target ("26 Oktober 2026").
3. 6-Stage Visual Stepper:
   - 1. Pendaftaran Permohonan (Completed)
   - 2. Konfirmasi Pembayaran (Completed)
   - 3. Penerimaan Sampel (Completed - "Diterima: Volume 1200 mL, Suhu 4.2°C")
   - 4. Pengujian Laboratorium (Active state with pulse animation)
   - 5. Validasi Hasil (Pending)
   - 6. Penerbitan LHU (Pending)
4. Confidentiality Note: Explaining detailed clinical results are kept confidential until final report issuance.
5. Action Buttons: "Unduh Bukti Progres (PDF)" and "Verifikasi untuk Unduh LHU Final".
```

### Prompt 5: Dual Verification LHU Download Gate (SCR-05)
```text
Design a secure Dual-Verification Download Gate for official Laboratory Test Reports (LHU) for "Balai Labkesmas Ambon".
Style: High-security government portal feel, clear authentication feedback.
Include:
1. Security Notice Card: Padlock icon explaining data confidentiality policies.
2. Dual Input Form:
   - Input 1: Nomor FPPS (e.g. "FPPS-2026-AMB-0142")
   - Input 2: Nomor WhatsApp / Telepon Pemohon terdaftar (+62 formatted)
   - Captcha checkbox and "Verifikasi & Buka Dokumen LHU" button.
3. Success Document Preview Card (Shown after matching FPPS + Phone Hash):
   - Verified badge: "Dokumen Laporan Hasil Uji Resmi Siap Diunduh"
   - Metadata: Nomor LHU, Tanggal Pengesahan, Nama Pemohon/Faskes, Validator Resmi.
   - Integrity Seal: Certified Digital Signature and QR Code Security Stamp.
   - Action Button: "Unduh Dokumen LHU Resmi (PDF)".
```

### Prompt 6: Document Authenticity Verification via QR (SCR-07)
```text
Design a Public Document Authenticity Verification Page that opens when scanning the QR Code printed on an official test report document of "Balai Laboratorium Kesehatan Masyarakat Ambon".
Style: Official green checkmark verification seal, clean mobile-first view.
Include:
1. Animated Green Checkmark Seal: "DOKUMEN RESMI TERVERIFIKASI".
2. Document Details Card: Nomor LHU, Nomor FPPS, Tanggal Pengesahan, Nama Pemohon/Instansi, and Jenis Pemeriksaan.
3. Signatories: Validator Resmi Laboratorium & Kepala Balai Labkesmas Ambon.
4. Integrity Notice: "Dokumen tervalidasi secara elektronik dan sah sesuai arsip digital Balai Labkesmas Ambon."
```
