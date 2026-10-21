<div class="flex flex-col w-full">
    <!-- HERO SECTION -->
    <section class="relative w-full overflow-hidden bg-gradient-to-b from-teal-surface via-surface to-surface-container-low/40 pb-space-xl">
        <!-- Ambient laboratory pattern background -->
        <div class="absolute inset-0 pointer-events-none opacity-40">
            <svg class="w-full h-full" height="100%" width="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern height="48" id="grid-pattern" patternUnits="userSpaceOnUse" width="48">
                        <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#CBD5E1" stroke-dasharray="2 4" stroke-width="0.75"></path>
                        <circle cx="24" cy="24" fill="#134E4A" opacity="0.3" r="1.2"></circle>
                    </pattern>
                </defs>
                <rect fill="url(#grid-pattern)" height="100%" width="100%"></rect>
            </svg>
        </div>

        <!-- Glowing ambient orb -->
        <div class="absolute top-12 left-1/2 -translate-x-1/2 w-[720px] h-[360px] bg-gradient-to-r from-teal-muted/40 via-sky-tint/30 to-teal-surface/50 blur-3xl pointer-events-none rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-gutter pt-space-xl">
            <!-- Accreditation & Trust Pill -->
            <div class="flex flex-wrap items-center justify-center gap-space-xs text-center mb-space-md">
                <div class="inline-flex items-center gap-2.5 bg-surface-container-lowest/90 backdrop-blur-md px-space-md py-1.5 rounded-full shadow-sm border border-border-hairline">
                    <img src="{{ asset('images/logo-labkesmas-icon.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                    <span class="font-label-sm text-label-sm text-primary tracking-wide font-semibold">
                        Balai Laboratorium Kesehatan Masyarakat Ambon
                    </span>
                    <span class="hidden sm:inline-block w-1.5 h-1.5 rounded-full bg-primary/40"></span>
                    <span class="hidden sm:inline-block font-label-sm text-label-sm text-slate-subtle">Kemenkes RI</span>
                </div>
                <div class="inline-flex items-center gap-space-xs bg-surface-container-lowest/90 backdrop-blur-md px-space-md py-1.5 rounded-full shadow-sm border border-border-hairline">
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-verified-tint text-verified-green">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    </span>
                    <span class="font-label-sm text-label-sm text-primary tracking-wide font-semibold">
                        Akreditasi KAN ISO/IEC 17025:2017
                    </span>
                    <span class="hidden sm:inline-block font-label-sm text-label-sm text-slate-subtle">(LP-892-IDN)</span>
                </div>
            </div>

            <!-- Headline & Subheadline -->
            <div class="text-center max-w-4xl mx-auto mb-space-lg">
                <h1 class="font-headline-xl text-headline-xl md:text-[2.75rem] md:leading-[1.18] text-primary tracking-tight mb-space-md font-bold">
                    Layanan Pengujian Laboratorium Kesehatan &amp; Lingkungan Terakreditasi di Maluku
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto leading-relaxed">
                    Melayani pengujian kualitas air, makanan &amp; minuman, vektor penyakit, udara ambien, patologi klinis, dan kalibrasi alat kesehatan secara akurat, transparan, dan terpercaya untuk seluruh wilayah Maluku dan Indonesia Bagian Timur.
                </p>
            </div>

            <!-- Dual Search Bento Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-md max-w-5xl mx-auto mb-space-xl">
                <!-- Card 1: Lacak Status Sampel Cepat -->
                <div class="lg:col-span-6 bg-surface-container-lowest p-space-lg rounded-2xl shadow-md transition-all hover:shadow-lg flex flex-col justify-between relative overflow-hidden border border-border-hairline">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary to-sky-progress"></div>
                    <div>
                        <div class="flex items-center justify-between mb-space-sm">
                            <div class="flex items-center gap-space-xs text-primary">
                                <span class="material-symbols-outlined text-[22px]">radar</span>
                                <span class="font-label-md text-label-md font-bold uppercase tracking-wider">Pelacakan Real-time</span>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-label-sm px-2.5 py-0.5 rounded-full bg-teal-surface text-primary border border-teal-muted font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-verified-green animate-pulse"></span> Sistem Aktif
                            </span>
                        </div>
                        <h2 class="font-headline-md text-headline-md text-slate-body mb-space-xs font-bold">Lacak Status Sampel Cepat</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-md">
                            Pantau tahapan pengerjaan pengujian laboratorium Anda dari registrasi, preparasi, hingga terbitnya LHU sah.
                        </p>
                        <form wire:submit="searchFpps" class="space-y-space-xs">
                            <label class="block font-label-sm text-label-sm text-on-surface-variant font-semibold" for="fpps-input">Nomor Registrasi FPPS</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3 text-slate-subtle text-[18px]">tag</span>
                                <input wire:model="fppsQuery" 
                                       class="w-full pl-10 pr-24 py-2.5 rounded-xl bg-surface font-code-tracking text-body-sm text-slate-body placeholder:text-outline-variant focus:outline-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all border border-border-hairline" 
                                       id="fpps-input" 
                                       placeholder="FPPS-YYYYMM-XXXX" 
                                       type="text"/>
                                <button class="absolute right-1.5 px-space-md py-1.5 bg-primary-container text-on-primary font-label-md text-label-sm rounded-lg hover:bg-teal-hover transition-colors flex items-center gap-1 shadow-sm" type="submit">
                                    <span>Lacak</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="pt-space-md mt-space-sm flex items-center justify-between text-slate-subtle font-body-sm text-body-sm border-t border-border-hairline/60">
                        <span class="flex items-center gap-1 text-xs">
                            <span class="material-symbols-outlined text-[15px] text-primary">security</span> Kerahasiaan data medis terjamin
                        </span>
                        <a href="{{ route('lacak') }}" class="text-primary hover:text-teal-hover font-label-sm text-xs font-semibold underline underline-offset-2">
                            Bantuan Lacak
                        </a>
                    </div>
                </div>

                <!-- Card 2: Cari Parameter & Tarif Uji -->
                <div class="lg:col-span-6 bg-surface-container-lowest p-space-lg rounded-2xl shadow-md transition-all hover:shadow-lg flex flex-col justify-between relative overflow-hidden border border-border-hairline">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sky-progress to-verified-green"></div>
                    <div>
                        <div class="flex items-center justify-between mb-space-sm">
                            <div class="flex items-center gap-space-xs text-sky-progress">
                                <span class="material-symbols-outlined text-[22px]">calculate</span>
                                <span class="font-label-md text-label-md font-bold uppercase tracking-wider">Katalog Tarif PNBP</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-slate-subtle font-medium">Permenkes &amp; PP 64/2019</span>
                        </div>
                        <h2 class="font-headline-md text-headline-md text-slate-body mb-space-xs font-bold">Cari Parameter &amp; Tarif Uji</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-md">
                            Simulasi biaya pengujian resmi mandiri, kebutuhan volume wadah steril, dan estimasi waktu penyelesaian (SLA).
                        </p>
                        <form wire:submit="searchParameter" class="space-y-space-xs">
                            <label class="block font-label-sm text-label-sm text-on-surface-variant font-semibold" for="param-search">Pencarian Cepat Parameter</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3 text-slate-subtle text-[18px]">search</span>
                                <input wire:model="parameterQuery" 
                                       class="w-full pl-10 pr-24 py-2.5 rounded-xl bg-surface font-body-md text-body-sm text-slate-body placeholder:text-outline-variant focus:outline-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest transition-all border border-border-hairline" 
                                       id="param-search" 
                                       placeholder="Ketik parameter: pH, Coliform, Boraks..." 
                                       type="text"/>
                                <button class="absolute right-1.5 px-space-md py-1.5 bg-sky-progress text-on-primary font-label-md text-label-sm rounded-lg hover:bg-sky-600 transition-colors flex items-center gap-1 shadow-sm" type="submit">
                                    <span>Cari</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div>
                        <div class="pt-space-md flex flex-wrap items-center gap-1.5 mb-space-sm">
                            <span class="font-label-sm text-slate-subtle mr-1 text-xs">Populer:</span>
                            <a href="{{ route('katalog', ['search' => 'Air Minum']) }}" class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-[11px] hover:bg-teal-surface hover:text-primary transition-colors">Air Minum</a>
                            <a href="{{ route('katalog', ['search' => 'Salmonella']) }}" class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-[11px] hover:bg-teal-surface hover:text-primary transition-colors">Salmonella</a>
                            <a href="{{ route('katalog', ['search' => 'Swab']) }}" class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-[11px] hover:bg-teal-surface hover:text-primary transition-colors">Swab Alat</a>
                            <a href="{{ route('katalog', ['search' => 'pH']) }}" class="px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-[11px] hover:bg-teal-surface hover:text-primary transition-colors">Uji pH</a>
                        </div>
                        <a href="{{ route('katalog') }}" class="w-full py-2 bg-surface-container-low text-primary font-label-md text-label-md rounded-xl hover:bg-teal-surface text-center flex items-center justify-center gap-1 transition-all border border-border-hairline font-semibold">
                            <span>Buka Seluruh 17 Katalog &amp; Simulasi Biaya</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_outward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Key Trust Metrics Strip -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-space-md max-w-5xl mx-auto pt-space-md border-t border-border-hairline/80">
                <div class="flex items-center gap-space-sm">
                    <div class="w-12 h-12 rounded-2xl bg-teal-surface text-primary flex items-center justify-center shrink-0 border border-teal-muted">
                        <span class="material-symbols-outlined text-[24px]">dataset</span>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md font-bold text-slate-body">17</div>
                        <div class="font-label-sm text-xs text-slate-subtle">Kelompok Layanan Uji</div>
                    </div>
                </div>
                <div class="flex items-center gap-space-sm">
                    <div class="w-12 h-12 rounded-2xl bg-sky-tint text-sky-progress flex items-center justify-center shrink-0 border border-sky-200">
                        <span class="material-symbols-outlined text-[24px]">science</span>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md font-bold text-slate-body">{{ $totalParameters }}+</div>
                        <div class="font-label-sm text-xs text-slate-subtle">Parameter Terakreditasi</div>
                    </div>
                </div>
                <div class="flex items-center gap-space-sm">
                    <div class="w-12 h-12 rounded-2xl bg-verified-tint text-verified-green flex items-center justify-center shrink-0 border border-green-200">
                        <span class="material-symbols-outlined text-[24px]">verified</span>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md font-bold text-slate-body">100%</div>
                        <div class="font-label-sm text-xs text-slate-subtle">LHU TTD Digital BSrE</div>
                    </div>
                </div>
                <div class="flex items-center gap-space-sm">
                    <div class="w-12 h-12 rounded-2xl bg-pending-tint text-pending-amber flex items-center justify-center shrink-0 border border-amber-200">
                        <span class="material-symbols-outlined text-[24px]">lock</span>
                    </div>
                    <div>
                        <div class="font-headline-md text-headline-md font-bold text-slate-body">Otentikasi</div>
                        <div class="font-label-sm text-xs text-slate-subtle">Keamanan Data Terjaga</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CORE SERVICES BENTO GRID -->
    <section class="max-w-7xl mx-auto px-gutter py-space-xl w-full">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md mb-space-xl">
            <div class="max-w-2xl space-y-space-xs">
                <div class="inline-flex items-center gap-1.5 text-primary bg-teal-surface px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[15px]">apps</span>
                    Cakupan Laboratorium
                </div>
                <h2 class="font-headline-xl text-headline-xl text-slate-body font-bold">17 Kelompok Layanan Pengujian Resmi</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Pemeriksaan terstandar baku mutu lingkungan dan kesehatan masyarakat dengan instrumen laboratorium mutakhir.
                </p>
            </div>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-1 text-primary hover:text-teal-hover font-semibold text-sm">
                <span>Lihat Seluruh Katalog Layanan</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @foreach($serviceGroups as $group)
                <div wire:key="group-{{ $group->id }}" class="bg-surface-container-lowest p-space-lg rounded-2xl border border-border-hairline shadow-sm hover:shadow-md hover:border-primary/40 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-space-sm">
                            <div class="w-12 h-12 rounded-xl bg-teal-surface text-primary flex items-center justify-center font-bold text-xl group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">
                                    @if(str_contains(strtolower($group->name), 'air')) water_drop
                                    @elseif(str_contains(strtolower($group->name), 'makanan')) restaurant
                                    @elseif(str_contains(strtolower($group->name), 'vektor')) pest_control
                                    @elseif(str_contains(strtolower($group->name), 'udara')) air
                                    @elseif(str_contains(strtolower($group->name), 'patologi') || str_contains(strtolower($group->name), 'mikrobiologi')) biometry
                                    @elseif(str_contains(strtolower($group->name), 'kalibrasi')) precision_manufacturing
                                    @else science
                                    @endif
                                </span>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-surface-container-low text-slate-subtle">
                                {{ $group->code }}
                            </span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-slate-body mb-space-xs font-bold group-hover:text-primary transition-colors">
                            {{ $group->name }}
                        </h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 mb-space-md">
                            {{ $group->description ?? 'Pengujian baku mutu spesifik terakreditasi KAN dengan jaminan validitas mutu hasil.' }}
                        </p>
                    </div>
                    <div class="pt-space-sm border-t border-border-hairline flex items-center justify-between">
                        <span class="text-xs text-slate-subtle">
                            {{ $group->testParameters()->count() }} Parameter Aktif
                        </span>
                        <a href="{{ route('katalog', ['category' => $group->code]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary group-hover:underline">
                            <span>Rincian &amp; Tarif</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4-STEP HOW IT WORKS INFOGRAPHIC -->
    <section class="w-full bg-surface-container-low/70 py-space-xl border-y border-border-hairline">
        <div class="max-w-7xl mx-auto px-gutter">
            <div class="text-center max-w-3xl mx-auto mb-space-xl">
                <div class="inline-flex items-center gap-1.5 text-primary bg-teal-surface px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-space-xs">
                    <span class="material-symbols-outlined text-[15px]">timeline</span>
                    Alur Pelayanan Publik
                </div>
                <h2 class="font-headline-xl text-headline-xl text-slate-body font-bold mb-space-xs">4 Langkah Mudah Pengujian Laboratorium</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Sistem pelayanan terintegrasi dari pendaftaran mandiri hingga penerbitan dokumen sertifikat LHU sah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter relative">
                <!-- Step 1 -->
                <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-border-hairline flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-primary text-on-primary font-bold flex items-center justify-center mb-space-sm text-sm shadow-sm">
                            1
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-slate-body mb-space-xs font-bold">Daftar FPPS Online</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Pilih kelompok uji, tentukan parameter atau paket, lengkapi profil pemohon, dan peroleh kode registrasi resmi FPPS.
                        </p>
                    </div>
                    <div class="mt-space-md pt-space-xs text-xs font-semibold text-primary">
                        Est. waktu: 3-5 Menit
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-border-hairline flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-sky-progress text-on-primary font-bold flex items-center justify-center mb-space-sm text-sm shadow-sm">
                            2
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-slate-body mb-space-xs font-bold">Kirim / Serahkan Sampel</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Serahkan sampel langsung ke loket penerimaan di Ambon atau kirim via ekspedisi rantai dingin sesuai syarat wadah steril.
                        </p>
                    </div>
                    <div class="mt-space-md pt-space-xs text-xs font-semibold text-sky-progress">
                        Pemeriksaan Fisik Loket
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-border-hairline flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-teal-deep text-on-primary font-bold flex items-center justify-center mb-space-sm text-sm shadow-sm">
                            3
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-slate-body mb-space-xs font-bold">Pengujian &amp; Validasi</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            Analis laboratorium mengerjakan pengujian sesuai metode baku SNI/APHA, dilanjutkan validasi oleh penanggung jawab teknis.
                        </p>
                    </div>
                    <div class="mt-space-md pt-space-xs text-xs font-semibold text-teal-deep">
                        Pantau Status Real-Time
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-border-hairline flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-verified-green text-on-primary font-bold flex items-center justify-center mb-space-sm text-sm shadow-sm">
                            4
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-slate-body mb-space-xs font-bold">Unduh LHU Digital Sah</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            LHU disahkan dengan Tanda Tangan Elektronik (TTE) BSrE. Unduh aman melalui Gerbang Otentikasi Ganda dengan Nomor HP.
                        </p>
                    </div>
                    <div class="mt-space-md pt-space-xs text-xs font-semibold text-verified-green">
                        Sertifikat PDF Resmi &amp; QR
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION BANNER -->
    <section class="max-w-7xl mx-auto px-gutter py-space-xl w-full">
        <div class="bg-gradient-to-r from-primary to-primary-container rounded-3xl p-space-xl text-on-primary shadow-xl flex flex-col md:flex-row items-center justify-between gap-space-lg relative overflow-hidden">
            <div class="absolute -right-24 -bottom-24 w-96 h-96 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="space-y-space-xs max-w-2xl relative z-10">
                <span class="font-label-sm text-teal-muted font-bold tracking-wider uppercase">Pelayanan Prima Masyarakat Maluku</span>
                <h2 class="font-headline-xl text-headline-xl text-white font-bold">Siap Mengajukan Pengujian Sampel?</h2>
                <p class="font-body-md text-teal-surface/90">
                    Daftarkan sampel Anda secara online sekarang untuk mempercepat proses di loket, atau hubungi petugas loket kami untuk konsultasi wadah steril.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-space-sm shrink-0 relative z-10 w-full sm:w-auto">
                <a href="{{ route('pendaftaran') }}" class="w-full sm:w-auto px-space-xl py-3.5 rounded-xl bg-surface-container-lowest text-primary font-bold text-center hover:bg-teal-surface transition-colors shadow-md">
                    Daftar FPPS Sekarang
                </a>
                <a href="https://wa.me/6281240405678" target="_blank" class="w-full sm:w-auto px-space-lg py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-center transition-colors border border-white/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    <span>Konsultasi Loket</span>
                </a>
            </div>
        </div>
    </section>
</div>