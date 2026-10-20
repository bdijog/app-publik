# KOLEKSI MASTER PROMPT GOOGLE STITCH
## Desain Antarmuka Publik Sistem Informasi Laboratorium Balai Labkesmas Ambon
**URL Tool:** [https://stitch.withgoogle.com/](https://stitch.withgoogle.com/)  
**Referensi:** [PRD_dan_Desain_Basis_Data.md](file:///home/kurnia/HTDOCS/app-publik/PRD_dan_Desain_Basis_Data.md) & [RENCANA_AKSI_DESAIN_FRONTEND_GOOGLE_STITCH.md](file:///home/kurnia/HTDOCS/app-publik/RENCANA_AKSI_DESAIN_FRONTEND_GOOGLE_STITCH.md)

---

## 📌 Panduan Penggunaan di Google Stitch

1. Masuk ke [https://stitch.withgoogle.com/](https://stitch.withgoogle.com/).
2. Buat proyek baru (*New Project*) dengan nama: **`Labkesmas Ambon - Public Portal`**.
3. Pertama kali, jalankan **Prompt 0: Inisialisasi Proyek & Sistem Desain Global** untuk mengatur tema, warna, dan tipografi dasar pada canvas.
4. Selanjutnya, jalankan masing-masing **Master Prompt Layar (SCR-01 s.d. SCR-07)** sesuai kebutuhan.
5. Gunakan **Prompt Komponen & Variasi State** untuk menyempurnakan kondisi khusus (misal: kondisi sampel ditolak, versi mobile viewport, dan format sertifikat LHU).

---

## 0. Inisialisasi Proyek & Sistem Desain Global (Project Context)

Gunakan prompt ini saat pertama kali membuat canvas di Stitch untuk mengunci bahasa desain (*design system*) di seluruh layar:

```text
Set up a cohesive, modern, and accessible design system for a public-facing healthcare and environmental testing laboratory web application named "Balai Laboratorium Kesehatan Masyarakat Ambon (Balai Labkesmas Ambon)".

Design Guidelines & Tokens:
- Brand Aesthetic: Authoritative, clean, highly trustworthy, modern clinical health & environmental service.
- Color Palette:
  * Primary Brand: Deep Teal (#0F766E) and Vibrant Teal (#0D9488) symbolizing health, water purity, and environmental science.
  * Secondary / Surface: Deep Slate Navy (#0F172A) for high-contrast headings, soft slate (#F8FAFC) for backgrounds, and pure white (#FFFFFF) for card surfaces.
  * Semantic Accents: Emerald Green (#16A34A) for success/verified states, Amber (#D97706) for in-progress/pending, Rose Red (#DC2626) for rejected/error states, and Sky Blue (#0284C7) for informational callouts.
- Typography: Modern sans-serif ('Plus Jakarta Sans' or 'Inter'), clean letter-spacing, generous line heights (leading-relaxed), and distinct visual hierarchy.
- Component Style: Rounded-2xl cards with 1px subtle borders (slate-200), soft elevation shadows (shadow-sm to shadow-md), accessible focus rings (ring-teal-600/20), and tactile interactive buttons.
- Target Devices: Desktop (1440px) and Mobile-first responsiveness (390px).
```

---

## 1. SCR-01: Landing Page & Beranda Publik (Homepage)

### A. Master Prompt (Bahasa Inggris - Optimal untuk Engine Stitch)
```text
Create a modern, highly trustworthy, responsive homepage for a government public health and environmental testing laboratory named "Balai Laboratorium Kesehatan Masyarakat Ambon (Balai Labkesmas Ambon)".

Design language: Clean healthcare aesthetic, primary colors in deep teal (#0F766E) and crisp emerald, slate accents (#0F172A), soft neutral background (#F8FAFC), modern typography using Plus Jakarta Sans, rounded-2xl containers, subtle borders, and smooth shadows.

Include the following sections on a desktop web layout with mobile responsiveness:
1. Navigation Bar:
   - Official Labkesmas Ambon logo placeholder.
   - Menu links: Beranda, Katalog Layanan, Lacak Sampel, Pendaftaran Online, Panduan Layanan, Kontak Balai.
   - High-contrast action button: "Lacak Status FPPS".
2. Hero Section:
   - Catchy headline: "Layanan Pengujian Laboratorium Kesehatan & Lingkungan Terakreditasi di Maluku"
   - Subheadline: "Melayani pengujian kualitas air, makanan, vektor penyakit, udara, dan patologi klinis dengan standar mutu terpercaya dan Laporan Hasil Uji (LHU) berstempel digital resmi."
   - Dual Search Card on Hero:
     * Card A (Quick FPPS Tracker): Input box "Masukkan Nomor FPPS Anda" with a prominent "Lacak Sekarang" button.
     * Card B (Catalog Quick Search): "Cari parameter uji (mis. Bakteri E. Coli, Boraks, Logam Berat)".
   - Trust Badges Row: Akreditasi KAN ISO/IEC 17025, Sertifikasi Kemenkes RI, Layanan Cepat & Akurat, Standar Rantai Dingin Sampel.
3. Key Services Bento-Grid:
   - Highlighting core test categories: Air Bersih & Minum, Air Limbah Faskes/Domestik, Makanan & Minuman, Vektor Penyakit, Udara & Emisi, Patologi Klinis, dan Kalibrasi Alat Lab.
   - Each card features: Custom clinical icon, list of popular parameters, SLA badge ("Estimasi 8-10 Hari"), and "Lihat Parameter & Tarif" link.
4. 4-Step "Alur Layanan Mudah":
   - Step 1: Pendaftaran Online (Pilih parameter & isi profil pemohon).
   - Step 2: Penyerahan Sampel Fisik (Diantar ke loket Balai Ambon atau kirim via ekspedisi).
   - Step 3: Pengujian Laboratorium & Validasi Ahli (Dikerjakan analis terakreditasi).
   - Step 4: Unduh LHU Terverifikasi (Akses PDF LHU resmi dengan verifikasi nomor HP).
5. Quick Notice Banner:
   - Protocols for sample temperature, sterile container packaging, and walk-in counter hours.
6. Footer:
   - Office address in Ambon, customer service WhatsApp hotline button, operational hours, FAQ links, and privacy statement.
```

### B. Versi Ringkas / Mobile Refinement Prompt
```text
Adapt the Balai Labkesmas Ambon homepage layout into a clean, mobile-first view (390px width). Ensure the Quick FPPS Tracker input is fixed or immediately visible above the fold, category cards stack vertically into a smooth swipeable carousel or 2-column grid, and a bottom sticky navigation bar is added with: Beranda, Katalog, Lacak, and Kontak WA.
```

---

## 2. SCR-02: Katalog Layanan & Simulasi Tarif Interaktif

### A. Master Prompt (Bahasa Inggris)
```text
Design a comprehensive, user-friendly interactive Service Catalog & Pricing Page for "Balai Laboratorium Kesehatan Masyarakat Ambon".

Design style: Clinical, transparent, clean bento-grid, emerald & teal accents, intuitive search & multi-category filtering.

Include:
1. Top Filter Header:
   - Prominent search bar: "Cari parameter uji (mis. pH, Coliform, Boraks, Salmonella, Logam Berat, Timbal, dll.)"
   - Category Pills / Filter Tabs covering all 17 official service groups:
     [Semua Layanan], [Air Bersih], [Air Minum], [Air Laut], [Air Sungai], [Air Kolam Renang], [Air Limbah Domestik], [Air Limbah Faskes], [Makanan & Minuman], [Swab Alat], [Vektor Penyakit], [Bakteriologi Udara], [Udara Ambien], [Udara Ruangan], [Patologi Klinis], [Penelitian], [Mikrobiologi/Biomolekuler], [Kalibrasi Alat].
2. Interactive Catalog Grid (Cards):
   - Card Header: Category badge (e.g. "Air Lingkungan"), Service Name (e.g. "Pemeriksaan Kualitas Air Minum Lengkap").
   - Parameter Pills: List of included parameters (Bau, Kekeruhan, pH, Nitrit, Besi, Mangan, E. Coli, Total Coliform).
   - Sample Requirements Box: Minimum volume ("Minimal 1000 mL dalam botol steril gelap"), preservation note ("Kondisi dingin 4°C, hindari paparan cahaya matahari langsung").
   - Estimated SLA Badge: "Estimasi SLA: 8-10 Hari Kerja".
   - Official Tariff Tag: High-contrast price display (e.g., "Rp 503.000").
   - Action Button: "Pilih & Ajukan Uji" (CTA button that adds the item to the online order draft).
3. Sticky Cost Estimation Summary Drawer / Bottom Sheet:
   - Floating at the bottom of the screen.
   - Shows: Number of selected test packages/parameters, estimated total cost in IDR (e.g., "Total Estimasi: Rp 846.000"), and a prominent CTA button: "Lanjutkan ke Formulir Pendaftaran ->".
```

### B. Prompt State Khusus: Mode Pencarian Parameter Kustom
```text
Show a modal / slide-over state on the Service Catalog page for "Custom Parameter Selection" within the Air Bersih category. The user can uncheck individual parameters and see the price adjust dynamically per item (e.g., pH: Rp 25.000, Kekeruhan: Rp 30.000, Total Coliform: Rp 85.000). Include clear subtotal calculation and container requirement notes.
```

---

## 3. SCR-03: Formulir Pendaftaran Permohonan Pengujian Online (Wizard)

### A. Master Prompt (Bahasa Inggris)
```text
Design a 5-step responsive Wizard Form for laboratory test sample registration for "Balai Laboratorium Kesehatan Masyarakat Ambon".

Visual tone: Reassuring, structured, distraction-free layout with progress stepper bar at the top, clear validation indicators, and accessible inputs.

Top Stepper:
Horizontal stepper showing: 1. Profil Pemohon -> 2. Parameter Uji -> 3. Informasi Sampel -> 4. Metode Penyerahan -> 5. Bukti Pendaftaran.

Step Details:
1. Step 1 - Profil Pemohon:
   - Radio selector: Kategori Pemohon [Perorangan | Instansi / Perusahaan | Fasilitas Kesehatan (Puskesmas/RS/Klinik)].
   - Inputs: Nama Lengkap Pemohon, Nama Instansi (if applicable), Nomor WhatsApp Aktif (with info badge: "Digunakan untuk verifikasi akses pengunduhan LHU"), Alamat Email, Alamat Lengkap Penagihan/Sampling.
2. Step 2 - Pilihan Layanan & Parameter:
   - Dropdown Kelompok Layanan.
   - Selection mode: Paket Pemeriksaan Standar OR Pilihan Parameter Kustom (checklist with individual item tariffs).
   - Real-time running total price calculation card.
3. Step 3 - Informasi Sampel Fisik & Titik Sampling:
   - Inputs: Nama/Identitas Sampel (e.g., "Air Sumur Gali Dusun A"), Titik/Lokasi Pengambilan Sampel, Tanggal & Jam Pengambilan (Sampling Date & Time), Jumlah Wadah / Volume.
   - File upload dropzone: Upload Surat Permohonan / Surat Pengantar Tugas (PDF/JPG max 5MB).
4. Step 4 - Metode Penyerahan Sampel & Persetujuan:
   - Choice: [Diantar Langsung ke Loket Balai di Ambon] OR [Dikirim via Ekspedisi / Kurir Rantai Dingin].
   - Instructions on sample preservation conditions.
   - Mandatory Checkbox: "Saya menyatakan bahwa sampel diambil sesuai standar dan informasi yang diberikan adalah benar."
   - Action buttons: "Kembali" and "Kirim Permohonan Uji".
5. Step 5 - Konfirmasi & Bukti Pendaftaran (Success Receipt Screen):
   - Celebratory success checkmark.
   - Prominently displays Generated FPPS Number (e.g., "FPPS-2026-AMB-0142") and Request Number ("REQ-2026-10-0089").
   - QR Code for counter check-in scanning.
   - Instructions card: Payment confirmation steps via Bank Transfer/Loket, and sample labeling instructions.
   - Buttons: "Cetak Bukti Pendaftaran (PDF)" and "Lacak Progres Sampel".
```

---

## 4. SCR-04: Lacak Status Sampel Publik / FPPS Tracker

### A. Master Prompt (Bahasa Inggris)
```text
Design an intuitive, high-transparency Public Sample Tracking Page for "Balai Laboratorium Kesehatan Masyarakat Ambon".

Design style: Trustworthy status dashboard, sleek progress steppers, timeline cards, teal and slate color scheme.

Key Components:
1. Search Bar Header:
   - Large prominent search card: "Masukkan Nomor FPPS Anda (Contoh: FPPS-2026-AMB-0142)".
   - Clean "Lacak Sampel" button with loading spinner state.
2. Tracking Result Container (When FPPS is Found):
   - Header Card:
     * Nomor FPPS: FPPS-2026-AMB-0142
     * Jenis Layanan: Pemeriksaan Kualitas Air Minum
     * Tanggal Pendaftaran: 18 Oktober 2026
     * Status Terkini Badge: "Sedang Dalam Pengujian Laboratorium" (Teal/Sky badge with animated pulse dot).
     * Estimasi SLA Selesai: "26 Oktober 2026 (Estimasi 8 Hari Kerja)".
3. Horizontal / Vertical Progress Stepper:
   - Step 1: Pendaftaran Permohonan (Completed - 18 Okt 2026, 09:30 WIT)
   - Step 2: Konfirmasi Pembayaran & Syarat (Completed - 18 Okt 2026, 11:15 WIT)
   - Step 3: Penerimaan Sampel Fisik di Balai (Completed - 19 Okt 2026, 08:45 WIT - Catatan: "Sampel diterima dalam kondisi baik, volume 1200 mL, suhu coolbox 4.2°C").
   - Step 4: Pengujian Laboratorium (Active state with pulse animation: "Sedang diuji oleh Laboratorium Kimia & Mikrobiologi Air").
   - Step 5: Validasi Manager Lab & Rekomendasi (Upcoming / In Queue).
   - Step 6: Penerbitan Laporan Hasil Uji - LHU (Upcoming).
4. Privacy Disclaimer Box:
   - "Informasi pelacakan ini hanya menyajikan status tahapan kerja. Nilai dan data hasil uji klinis/lingkungan bersifat rahasia dan hanya dapat diakses pada dokumen LHU Final melalui verifikasi keamanan."
5. Action Buttons:
   - Button 1: "Unduh Bukti Progres Saat Ini" (Generates PDF progress sheet).
   - Button 2: "Verifikasi untuk Unduh LHU Final" (Disabled if still in progress, active when LHU is final).
```

### B. Prompt State Khusus: Kondisi Sampel Ditolak (Sample Rejected)
```text
Design an alternative state for the FPPS Tracking screen when a sample is rejected during Step 3 (Penerimaan Sampel).
- Status Badge: "Sampel Ditolak / Tidak Memenuhi Syarat" in Rose Red (#DC2626).
- Rejection Detail Card: Clear reason ("Volume sampel kurang dari batas minimum 500 mL dan wadah mengalami kebocoran saat pengiriman").
- Follow-up Guidance: "Tindak Lanjut: Silakan menghubungi Petugas Pelayanan Balai Labkesmas Ambon via WhatsApp (+62 811-xxxx-xxxx) untuk pengiriman ulang sampel pengganti tanpa biaya registrasi ulang."
- Direct CTA button: "Hubungi Petugas Pelayanan".
```

---

## 5. SCR-05: Gerbang Verifikasi & Unduh LHU Final

### A. Master Prompt (Bahasa Inggris)
```text
Design a secure Dual-Verification Download Gate for official Laboratory Test Reports (LHU) for "Balai Laboratorium Kesehatan Masyarakat Ambon".

Design style: High-security feel, clean government portal, confidential data protection notice, clear authentication feedback.

Components:
1. Security Notice Card:
   - Shield padlock icon with text: "Perlindungan Kerahasiaan Data Hasil Uji. Berdasarkan kebijakan privasi Balai Labkesmas Ambon, dokumen Laporan Hasil Uji (LHU) hanya dapat diunduh oleh pemilik sampel yang sah."
2. Dual Verification Card:
   - Input 1: Nomor FPPS (e.g., "FPPS-2026-AMB-0142").
   - Input 2: Nomor WhatsApp / Telepon Pemohon yang terdaftar saat permohonan dibuat (formatted phone input with +62 prefix).
   - Security Captcha checkbox: "Saya bukan robot".
   - Primary Button: "Verifikasi & Buka Dokumen LHU".
3. Success Document Preview Card (Shown after matching FPPS + Phone Hash):
   - Certificate preview banner with verified green badge: "Dokumen Laporan Hasil Uji Resmi Siap Diunduh".
   - Document Metadata Table:
     * Nomor LHU: LHU/042/LK-AMB/X/2026
     * Tanggal Pengesahan: 20 Oktober 2026
     * Nama Pemohon / Instansi: Dinas Kesehatan Kab. Maluku Tengah
     * Jenis Sampel: Air Minum Jaringan Perpipaan
     * Verifikator: Analis Ahli Muda Labkesmas
     * Validator / Penandatangan: Kepala Balai Labkesmas Ambon (NIP 198501xxxxxxxx)
   - Security Integrity Stamp: "Dilengkapi Tanda Tangan Elektronik Tersertifikasi & Segel Pengaman QR Code".
   - Action Buttons:
     * Primary CTA: "Unduh Dokumen LHU Resmi (PDF)" with download icon.
     * Secondary Button: "Unduh Bukti Progres Sampel".
4. Support Callout:
   - "Nomor telepon tidak cocok? Hubungi Petugas Loket Pelayanan Balai Labkesmas Ambon dengan menyertakan bukti identitas pemohon."
```

### B. Prompt State Khusus: Gagal Verifikasi (Phone Mismatch)
```text
Design an error feedback state for the LHU Download Gate when the entered phone number does not match the registered FPPS record.
- Error Alert Box: "Verifikasi Gagal: Nomor telepon yang Anda masukkan tidak sesuai dengan data permohonan FPPS-2026-AMB-0142."
- Reassurance Note: "Pastikan Anda menggunakan nomor WhatsApp yang dicatatkan saat pendaftaran awal. Jika ada perubahan nomor kontak, silakan lakukan verifikasi manual ke loket Balai."
```

---

## 6. SCR-06: Portal Pelanggan / Dashboard Faskes (Opsional)

### A. Master Prompt (Bahasa Inggris)
```text
Design a dedicated Customer Portal Dashboard for regular healthcare facilities (Puskesmas, Hospitals, Clinics) and Enterprise Clients of "Balai Labkesmas Ambon".

Design layout: Responsive client dashboard with a clean sidebar/topbar navigation, stats summary, and interactive data table.

Key Components:
1. Top Header:
   - Organization Name: "RSUD Dr. M. Haulussy Ambon" with a verified badge.
   - User profile pill, notification bell for newly published LHUs, and Logout button.
2. Quick Metrics Row (3 Bento Cards):
   - Metric 1: Permohonan Aktif (4 Sampel Sedang Diuji).
   - Metric 2: LHU Siap Unduh (12 Dokumen Final Terbit).
   - Metric 3: Total Pengujian Tahun Ini (48 Permohonan Selesai).
3. Action Bar:
   - Primary Button: "+ Buat Permohonan Uji Baru" (links directly to registration wizard).
   - Filter dropdown by service category and status (Menunggu Sampel, Sedang Diuji, Validasi, LHU Selesai).
4. Test Request History Table:
   - Columns: Tanggal Daftar, No. FPPS, Nama Sampel / Lokasi Sampling, Kelompok Layanan, Status Progres (colored pill badges), Dokumen LHU (Direct download button if final, or disabled pill "Dalam Proses"), dan Tombol Opsi/Detail.
5. Invoicing & Billing Summary Card:
   - Showing paid invoices, pending payments, and official e-receipt downloads.
```

---

## 7. SCR-07: Halaman Verifikasi Publik Keaslian LHU via QR Code

### A. Master Prompt (Bahasa Inggris)
```text
Design a Public Document Authenticity Verification Page that opens when scanning the QR Code printed on an official test report document of "Balai Laboratorium Kesehatan Masyarakat Ambon".

Design style: Official document verification badge, green checkmark seal of authenticity, responsive mobile-first view.

Key Elements:
1. Verification Seal Header:
   - Animated green checkmark badge: "DOKUMEN RESMI TERVERIFIKASI".
   - Subtext: "Dokumen ini terdaftar secara sah di Sistem Informasi Laboratorium Balai Labkesmas Ambon."
2. Document Identification Card:
   - Nomor LHU: LHU/042/LK-AMB/X/2026
   - Nomor FPPS: FPPS-2026-AMB-0142
   - Tanggal Diterbitkan: 20 Oktober 2026
   - Nama Pemohon / Instansi: Dinas Kesehatan Kota Ambon
   - Jenis Pemeriksaan: Mikrobiologi & Kimia Air Bersih
3. Official Signatories & Validation:
   - Disahkan oleh: Validator Resmi Laboratorium & Kepala Balai Labkesmas Ambon.
   - Status Integritas: "Tervalidasi secara elektronik & tidak mengalami perubahan sejak diterbitkan."
4. Privacy Disclaimer:
   - "Rincian angka dan nilai parameter hasil uji tidak ditampilkan secara terbuka demi menjaga kerahasiaan data medis/pengujian sesuai undang-undang."
```

---

## 8. Prompt Komponen Mandiri (Micro-Components)

Gunakan prompt berikut jika Anda ingin Stitch menghasilkan komponen UI secara terisolasi untuk modularitas:

### A. Navigation Bar & Sticky Mobile Nav
```text
Create a standalone responsive navigation bar for Balai Labkesmas Ambon.
Desktop: Left logo with text "Balai Laboratorium Kesehatan Masyarakat Ambon - Kemenkes RI", center nav links (Beranda, Katalog Layanan, Lacak Sampel, Pendaftaran Online), right CTA "Lacak FPPS".
Mobile: Compact logo, hamburger menu, and a bottom sticky bar with 4 quick buttons: [Beranda], [Katalog], [Lacak], [WhatsApp].
```

### B. Progress Stepper Alur Pengujian
```text
Design an interactive 6-stage status tracker component (desktop horizontal, mobile vertical) showing:
1. Registrasi Permohonan
2. Konfirmasi Pembayaran
3. Penerimaan Sampel (with accepted/rejected state toggle)
4. Pengujian Laboratorium (with pulsing active dot)
5. Validasi Hasil
6. Penerbitan LHU
Use Teal (#0F766E) for completed, Sky Blue (#0284C7) for in-progress, and Slate-200 for upcoming steps.
```

### C. Sticky Cost Estimation Drawer (Bottom Sheet)
```text
Design a floating sticky cost estimation bar for the bottom of the screen.
Displays: "2 Paket Pemeriksaan Dipilih", subtotal amount "Rp 680.000", an expandable accordion showing parameter breakdown, and a green CTA button "Lanjut ke Pendaftaran ->".
```

---

## 9. Prompt Ekstraksi & Ekspor (Stitch Post-Design)

Setelah desain pada canvas Stitch selesai, jalankan prompt berikut di panel chat Stitch untuk mendapatkan aset siap pakai:

```text
Please generate the comprehensive DESIGN.md documentation and export the production-ready HTML and Tailwind CSS code bundle for all generated screens.

Ensure the export includes:
1. DESIGN.md containing complete color hex codes, font hierarchy, component spacing, and responsive breakpoints.
2. Clean semantic HTML5 files for each screen.
3. Utility classes using modern Tailwind CSS syntax.
4. Inline SVGs for all healthcare and status icons.
```
