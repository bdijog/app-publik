<div class="relative w-full overflow-hidden bg-surface py-space-xl">
    <!-- Subtle Ambient Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[640px] h-96 bg-verified-tint/40 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-24 left-1/4 w-72 h-72 bg-teal-muted/30 rounded-full blur-2xl pointer-events-none -z-10"></div>

    <div class="max-w-4xl mx-auto px-gutter flex flex-col items-center">
        <a href="{{ route('home') }}" class="mb-space-sm">
            <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-10 w-auto object-contain">
        </a>
        <!-- Overline Institutional Label -->
        <div class="inline-flex items-center gap-space-xs px-space-sm py-1 bg-surface-container-lowest shadow-sm rounded-full mb-space-md border border-border-hairline">
            <span class="w-2 h-2 rounded-full bg-verified-green animate-pulse"></span>
            <span class="font-label-sm text-xs text-primary tracking-wider uppercase font-semibold">Validasi QR Code Real-Time • Kemenkes RI</span>
        </div>

        @if($report)
            <!-- Section 1: Status Hero Verifikasi -->
            <div class="flex flex-col items-center text-center max-w-2xl">
                <!-- Glowing Green Seal Icon -->
                <div class="relative flex items-center justify-center w-24 h-24 mb-space-md">
                    <div class="absolute inset-0 rounded-full bg-verified-green/20 animate-ping"></div>
                    <div class="absolute inset-1 rounded-full bg-verified-tint flex items-center justify-center shadow-md">
                        <div class="w-16 h-16 rounded-full bg-verified-green flex items-center justify-center shadow-lg shadow-verified-green/30">
                            <span class="material-symbols-outlined text-on-primary text-[38px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                        </div>
                    </div>
                </div>

                <h1 class="font-headline-xl text-headline-xl md:text-3xl text-slate-body tracking-tight font-extrabold mb-space-xs">
                    DOKUMEN RESMI TERVERIFIKASI
                </h1>
                <p class="font-body-md text-sm text-slate-subtle max-w-lg leading-relaxed">
                    Sistem Verifikasi Digital Dokumen Resmi Balai Laboratorium Kesehatan Masyarakat Ambon, Kementerian Kesehatan Republik Indonesia.
                </p>
            </div>

            <!-- Section 2: Official Certificate Card -->
            <div class="w-full mt-space-lg bg-card-bg rounded-2xl shadow-md border border-border-hairline overflow-hidden">
                <!-- Integrity Status Header Bar -->
                <div class="bg-verified-green px-gutter py-space-md flex flex-wrap items-center justify-between gap-space-sm text-on-primary">
                    <div class="flex items-center gap-space-sm">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                        <span class="font-label-md text-xs sm:text-sm font-bold tracking-wide">
                            ASLI &amp; SESUAI DENGAN ARSIP DIGITAL BALAI LABKESMAS AMBON
                        </span>
                    </div>
                    <span class="font-label-sm text-xs bg-white/20 px-space-sm py-0.5 rounded-full backdrop-blur-sm font-mono">
                        ID Validasi: #AUTH-{{ $report->id }}-AMB
                    </span>
                </div>

                <!-- Certificate Body -->
                <div class="p-gutter lg:p-space-xl flex flex-col gap-space-lg">
                    <!-- Key Meta Hero Grid (LHU Number & FPPS Registration) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md bg-teal-surface p-space-md rounded-2xl border border-teal-muted">
                        <div class="flex flex-col">
                            <span class="font-label-sm text-xs text-slate-subtle uppercase tracking-wider font-semibold">Nomor Dokumen LHU</span>
                            <span class="font-headline-sm text-xl text-primary font-bold tracking-tight mt-0.5 font-mono">
                                {{ $report->report_number }}
                            </span>
                            <span class="font-body-sm text-xs text-teal-deep mt-1 flex items-center gap-1 font-medium">
                                <span class="material-symbols-outlined text-[16px]">fingerprint</span> Arsip Sertifikasi Resmi Balai
                            </span>
                        </div>
                        <div class="flex flex-col md:border-l md:border-teal-deep/15 md:pl-space-md">
                            <span class="font-label-sm text-xs text-slate-subtle uppercase tracking-wider font-semibold">Nomor Registrasi FPPS</span>
                            <span class="font-headline-sm text-xl text-slate-body font-bold tracking-tight mt-0.5 font-mono">
                                {{ $report->testRequest->fpps_number ?? '-' }}
                            </span>
                            <span class="font-body-sm text-xs text-slate-subtle mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">receipt_long</span> Registrasi Pengujian Terpadu
                            </span>
                        </div>
                    </div>

                    <!-- Structured Metadata Bento Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-md">
                        <!-- Tanggal Diterbitkan -->
                        <div class="bg-surface-container-low p-space-md rounded-xl flex flex-col justify-between border border-border-hairline">
                            <div class="flex items-center gap-space-xs text-slate-subtle mb-space-xs">
                                <span class="material-symbols-outlined text-primary text-[18px]">calendar_today</span>
                                <span class="font-label-sm text-xs uppercase font-semibold">Tanggal Diterbitkan</span>
                            </div>
                            <span class="font-headline-sm text-base font-bold text-slate-body">
                                {{ $report->issued_at ? $report->issued_at->format('d F Y') : ($report->validated_on ? $report->validated_on->format('d F Y') : date('d F Y')) }}
                            </span>
                            <span class="text-xs text-slate-subtle mt-1">Disahkan WIT</span>
                        </div>

                        <!-- Nama Pemohon -->
                        <div class="bg-surface-container-low p-space-md rounded-xl flex flex-col justify-between sm:col-span-1 lg:col-span-2 border border-border-hairline">
                            <div class="flex items-center gap-space-xs text-slate-subtle mb-space-xs">
                                <span class="material-symbols-outlined text-primary text-[18px]">domain</span>
                                <span class="font-label-sm text-xs uppercase font-semibold">Nama Pelanggan / Pemohon</span>
                            </div>
                            <span class="font-headline-sm text-base font-bold text-slate-body">
                                {{ $report->testRequest->customer->organization_name ?? $report->testRequest->customer->name ?? 'Pemohon Terdaftar' }}
                            </span>
                            <span class="text-xs text-slate-subtle mt-1">
                                Kategori: {{ $report->testRequest->customer->customer_type instanceof \App\Enums\CustomerType ? $report->testRequest->customer->customer_type->label() : 'Pengujian Terstandar' }}
                            </span>
                        </div>

                        <!-- Sampel yang Diuji -->
                        <div class="bg-surface-container-low p-space-md rounded-xl flex flex-col justify-between border border-border-hairline">
                            <div class="flex items-center gap-space-xs text-slate-subtle mb-space-xs">
                                <span class="material-symbols-outlined text-primary text-[18px]">science</span>
                                <span class="font-label-sm text-xs uppercase font-semibold">Sampel yang Diuji</span>
                            </div>
                            <span class="font-headline-sm text-base font-bold text-teal-deep">
                                {{ $report->testRequest->samples->first()?->sample_name ?? $report->testRequest->serviceGroup->name ?? 'Sampel Baku' }}
                            </span>
                            <span class="text-xs text-slate-subtle mt-1">
                                {{ $report->testRequest->serviceGroup->name ?? '-' }}
                            </span>
                        </div>

                        <!-- Legalitas Pengesah -->
                        <div class="bg-surface-container-low p-space-md rounded-xl flex flex-col justify-between sm:col-span-1 lg:col-span-2 border border-border-hairline">
                            <div class="flex items-center gap-space-xs text-slate-subtle mb-space-xs">
                                <span class="material-symbols-outlined text-verified-green text-[18px]">verified_user</span>
                                <span class="font-label-sm text-xs uppercase font-semibold">Otoritas Validasi &amp; TTE</span>
                            </div>
                            <span class="font-headline-sm text-base font-bold text-slate-body">
                                Validator Laboratorium Balai Labkesmas Ambon
                            </span>
                            <span class="text-xs text-verified-green font-semibold mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">security</span>
                                Tanda Tangan Elektronik Tersertifikasi BSrE Badan Siber dan Sandi Negara
                            </span>
                        </div>
                    </div>

                    <!-- Considerations note -->
                    @if($report->considerations)
                        <div class="p-space-md bg-teal-surface/40 rounded-xl border border-teal-muted text-xs text-slate-body">
                            <span class="font-bold text-primary block mb-1">Catatan Pertimbangan Hasil Uji:</span>
                            <p class="leading-relaxed">{{ $report->considerations }}</p>
                        </div>
                    @endif

                    <!-- Non-disclosure notice -->
                    <div class="p-space-sm bg-surface-container-low rounded-xl text-center text-xs text-slate-subtle border border-border-hairline">
                        <span class="flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-pending-amber">lock</span>
                            Sesuai standar kerahasiaan medis &amp; kesehatan masyarakat, detail angka rekam hasil uji hanya dapat diakses oleh pemohon sah melalui otentikasi ganda.
                        </span>
                    </div>

                    <!-- Bottom CTA -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-space-sm pt-space-xs">
                        <a href="{{ route('verifikasi-lhu', ['fpps' => $report->testRequest->fpps_number ?? '']) }}" class="w-full sm:w-auto px-space-xl py-2.5 rounded-xl bg-primary-container text-on-primary font-bold text-xs hover:bg-teal-hover transition-colors shadow-sm flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">lock_open</span>
                            <span>Buka Dokumen Lengkap Pemohon</span>
                        </a>
                        <a href="{{ route('home') }}" class="w-full sm:w-auto px-space-lg py-2.5 rounded-xl border border-border-hairline text-slate-body font-semibold text-xs hover:bg-surface-container transition-colors text-center">
                            Kembali ke Portal Publik
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Report Not Found State -->
            <div class="mt-space-xl bg-card-bg rounded-2xl shadow-sm border border-border-hairline p-space-xl text-center max-w-lg space-y-space-sm">
                <span class="material-symbols-outlined text-[48px] text-rejection-red">gpp_bad</span>
                <h2 class="font-headline-sm text-lg font-bold text-slate-body">Sertifikat LHU Tidak Ditemukan</h2>
                <p class="text-xs text-slate-subtle leading-relaxed">
                    Kode verifikasi QR atau nomor dokumen "<span class="font-mono font-bold">{{ $number }}</span>" tidak terdaftar pada basis data resmi Balai Labkesmas Ambon.
                </p>
                <div class="pt-space-sm">
                    <a href="{{ route('home') }}" class="px-space-md py-2 rounded-xl bg-primary text-on-primary text-xs font-bold">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>