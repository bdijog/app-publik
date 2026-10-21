<div class="flex flex-col w-full">
    <!-- Top Utility Context Bar -->
    <section class="w-full bg-surface-container-low border-b border-border-hairline/60">
        <div class="max-w-7xl mx-auto px-gutter py-space-sm flex flex-wrap items-center justify-between gap-space-sm text-slate-subtle">
            <div class="flex items-center gap-space-xs font-label-sm text-xs">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    Beranda
                </a>
                <span class="text-outline-variant">/</span>
                <span class="text-on-surface font-semibold">Pelacakan Status Sampel (FPPS)</span>
            </div>
            <div class="flex items-center gap-space-md">
                <div class="flex items-center gap-1.5 text-verified-green font-label-sm text-xs font-semibold">
                    <span class="inline-block w-2 h-2 rounded-full bg-verified-green animate-pulse"></span>
                    <span>Sistem LIMS Terintegrasi Real-Time (Server Maluku)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-gutter w-full py-space-lg space-y-space-xl">
        <!-- 1. Hero Search Bar -->
        <div class="w-full bg-surface-container-lowest rounded-2xl shadow-sm border border-border-hairline p-space-md sm:p-space-lg">
            <div class="max-w-4xl mx-auto space-y-space-md">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-xs">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-8 w-auto object-contain">
                        </div>
                        <span class="font-label-sm text-xs uppercase tracking-wider text-primary font-bold block mb-1">
                            Gerbang Pelacakan Mandiri Publik
                        </span>
                        <h1 class="font-headline-lg text-headline-lg text-slate-body font-bold">
                            Lacak Status Pemeriksaan Sampel
                        </h1>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-space-sm py-1 rounded-full bg-teal-surface text-primary font-label-sm text-xs border border-teal-muted font-semibold self-start sm:self-auto">
                        <span class="material-symbols-outlined text-[15px]">verified_user</span>
                        Akreditasi ISO/IEC 17025
                    </span>
                </div>

                <p class="font-body-sm text-sm text-slate-subtle">
                    Pantau seluruh tahapan penerimaan, persiapan uji, analisis laboratorium, hingga penerbitan Sertifikat Laporan Hasil Uji (LHU) dengan memasukkan nomor FPPS resmi.
                </p>

                <!-- Search Form -->
                <form wire:submit="track" class="flex flex-col sm:flex-row items-stretch gap-space-xs sm:gap-space-sm pt-space-xs">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-subtle">
                            <span class="material-symbols-outlined text-[20px] text-primary">qr_code_scanner</span>
                        </div>
                        <input wire:model="searchFpps" 
                               class="w-full pl-11 pr-24 py-3 bg-surface font-code-tracking text-base text-slate-body rounded-xl border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary shadow-inner" 
                               placeholder="Contoh: FPPS-YYYYMM-XXXX" 
                               type="text"/>
                        @if(!empty($searchFpps))
                            <button wire:click="$set('searchFpps', '')" type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-subtle hover:text-on-surface">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        @endif
                    </div>
                    <button class="px-space-xl py-3 bg-primary-container text-on-primary hover:bg-teal-hover transition-colors rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm shrink-0 cursor-pointer" type="submit">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span>Lacak Sampel</span>
                    </button>
                </form>

                <div class="flex flex-wrap items-center justify-between gap-space-xs pt-1 text-xs text-slate-subtle">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px] text-pending-amber">info</span>
                        Nomor FPPS tertera pada slip barcode pendaftaran atau bukti serah terima sampel.
                    </span>
                </div>
            </div>
        </div>

        @if($testRequest)
            @php
                $statusVal = $testRequest->status instanceof \App\Enums\TestRequestStatus ? $testRequest->status->value : (string)$testRequest->status;
                // Stage determination (1 to 5)
                $currentStage = match($statusVal) {
                    'draft', 'submitted' => 1,
                    'awaiting_payment', 'awaiting_sample' => 2,
                    'in_progress' => 3,
                    'validated' => 4,
                    'report_issued', 'completed' => 5,
                    'cancelled' => 0,
                    default => 1,
                };
            @endphp

            <!-- 2. Ringkasan Status Sampel (Banner Hero Card) -->
            <div class="w-full bg-surface-container-lowest rounded-2xl shadow-sm border border-border-hairline overflow-hidden">
                <!-- Status Banner Header -->
                <div class="bg-gradient-to-r from-teal-deep to-primary p-space-md sm:p-space-lg text-on-primary flex flex-col md:flex-row md:items-center justify-between gap-space-md">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-teal-muted font-label-sm text-xs uppercase tracking-wider font-semibold">Tanda Terima Aktif</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-muted"></span>
                            <span class="text-teal-surface font-label-sm text-xs">{{ $testRequest->serviceGroup->name ?? 'Laboratorium Terakreditasi' }}</span>
                        </div>
                        <div class="flex items-center gap-space-sm flex-wrap">
                            <h2 class="font-headline-xl text-2xl md:text-3xl font-extrabold text-on-primary tracking-tight font-mono">
                                {{ $testRequest->fpps_number }}
                            </h2>
                        </div>
                    </div>

                    <!-- Live State Badge -->
                    <div class="flex flex-col items-start md:items-end gap-1">
                        <div class="inline-flex items-center gap-2 px-space-md py-2 rounded-full bg-surface-container-lowest text-primary font-bold text-xs shadow-sm">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-progress opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-deep"></span>
                            </span>
                            <span>{{ $testRequest->status instanceof \App\Enums\TestRequestStatus ? $testRequest->status->label() : strtoupper($statusVal) }}</span>
                        </div>
                        <span class="text-xs text-teal-surface">
                            Registrasi: {{ $testRequest->registered_at ? $testRequest->registered_at->format('d M Y, H:i') : '-' }} WIT
                        </span>
                    </div>
                </div>

                <!-- 5-STAGE STEPPER TRACKING -->
                <div class="p-space-lg lg:p-space-xl border-b border-border-hairline overflow-x-auto">
                    <div class="min-w-[700px] flex items-center justify-between relative">
                        <div class="absolute top-5 left-10 right-10 h-1 bg-surface-container -z-0"></div>
                        <div class="absolute top-5 left-10 h-1 bg-primary -z-0 transition-all duration-500"
                             style="width: {{ match($currentStage) { 1 => '10%', 2 => '30%', 3 => '55%', 4 => '80%', 5 => '100%', default => '0%' } }};"></div>

                        <!-- Stage 1 -->
                        <div class="flex flex-col items-center gap-1.5 text-center flex-1 relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStage >= 1 ? 'bg-verified-green text-on-primary' : 'bg-surface-container text-slate-subtle' }}">
                                <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                            </div>
                            <span class="text-xs font-bold {{ $currentStage >= 1 ? 'text-primary' : 'text-slate-subtle' }}">1. Didaftarkan</span>
                            <span class="text-[11px] text-slate-subtle">Permohonan Sah</span>
                        </div>

                        <!-- Stage 2 -->
                        <div class="flex flex-col items-center gap-1.5 text-center flex-1 relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStage >= 2 ? 'bg-verified-green text-on-primary' : ($currentStage === 1 ? 'bg-primary ring-4 ring-teal-muted text-on-primary' : 'bg-surface-container text-slate-subtle') }}">
                                <span class="material-symbols-outlined text-[20px]">science</span>
                            </div>
                            <span class="text-xs font-bold {{ $currentStage >= 2 ? 'text-primary' : 'text-slate-subtle' }}">2. Penerimaan Sampel</span>
                            <span class="text-[11px] text-slate-subtle">Cek Fisik &amp; Wadah</span>
                        </div>

                        <!-- Stage 3 -->
                        <div class="flex flex-col items-center gap-1.5 text-center flex-1 relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStage >= 3 ? 'bg-verified-green text-on-primary' : ($currentStage === 2 ? 'bg-primary ring-4 ring-teal-muted text-on-primary' : 'bg-surface-container text-slate-subtle') }}">
                                <span class="material-symbols-outlined text-[20px]">biotech</span>
                            </div>
                            <span class="text-xs font-bold {{ $currentStage >= 3 ? 'text-primary' : 'text-slate-subtle' }}">3. Pengujian Lab</span>
                            <span class="text-[11px] text-slate-subtle">Analisis Parameter</span>
                        </div>

                        <!-- Stage 4 -->
                        <div class="flex flex-col items-center gap-1.5 text-center flex-1 relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStage >= 4 ? 'bg-verified-green text-on-primary' : ($currentStage === 3 ? 'bg-primary ring-4 ring-teal-muted text-on-primary' : 'bg-surface-container text-slate-subtle') }}">
                                <span class="material-symbols-outlined text-[20px]">fact_check</span>
                            </div>
                            <span class="text-xs font-bold {{ $currentStage >= 4 ? 'text-primary' : 'text-slate-subtle' }}">4. Validasi Teknis</span>
                            <span class="text-[11px] text-slate-subtle">Penelaahan Mutu</span>
                        </div>

                        <!-- Stage 5 -->
                        <div class="flex flex-col items-center gap-1.5 text-center flex-1 relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md {{ $currentStage >= 5 ? 'bg-verified-green text-on-primary ring-4 ring-teal-muted' : 'bg-surface-container text-slate-subtle' }}">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                            </div>
                            <span class="text-xs font-bold {{ $currentStage >= 5 ? 'text-verified-green' : 'text-slate-subtle' }}">5. LHU Terbit</span>
                            <span class="text-[11px] text-slate-subtle">Siap Diambil/Unduh</span>
                        </div>
                    </div>
                </div>

                <!-- BENTO GRID DETAILS -->
                <div class="p-space-lg lg:p-space-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter bg-surface-container-low/40">
                    <!-- Customer Info -->
                    <div class="p-space-md bg-surface-container-lowest rounded-xl border border-border-hairline">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-subtle block mb-1">Nama Pemohon / Instansi</span>
                        <div class="font-bold text-slate-body text-sm truncate">
                            {{ $testRequest->customer->organization_name ?? $testRequest->customer->name ?? 'Pemohon Mandiri' }}
                        </div>
                        <span class="text-xs text-slate-subtle">PIC: {{ $testRequest->customer->name ?? '-' }}</span>
                    </div>

                    <!-- Service Group -->
                    <div class="p-space-md bg-surface-container-lowest rounded-xl border border-border-hairline">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-subtle block mb-1">Kelompok Layanan Uji</span>
                        <div class="font-bold text-primary text-sm truncate">
                            {{ $testRequest->serviceGroup->name ?? '-' }}
                        </div>
                        <span class="text-xs text-slate-subtle">{{ $testRequest->parameters->count() }} Parameter Pengujian</span>
                    </div>

                    <!-- Payment Status -->
                    <div class="p-space-md bg-surface-container-lowest rounded-xl border border-border-hairline">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-subtle block mb-1">Status Pembayaran PNBP</span>
                        <div class="flex items-center gap-1.5">
                            @if(($testRequest->payment_status instanceof \App\Enums\PaymentStatus && $testRequest->payment_status === \App\Enums\PaymentStatus::Paid) || $testRequest->payment_status === 'paid')
                                <span class="px-2.5 py-0.5 rounded-full bg-verified-tint text-verified-green text-xs font-bold">
                                    Lunas
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-pending-tint text-pending-amber text-xs font-bold">
                                    Belum Bayar
                                </span>
                            @endif
                            <span class="font-mono font-bold text-xs text-slate-body">Rp {{ number_format((float)$testRequest->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Estimation Date -->
                    <div class="p-space-md bg-surface-container-lowest rounded-xl border border-border-hairline">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-subtle block mb-1">Estimasi Selesai (SLA)</span>
                        <div class="font-bold text-slate-body text-sm">
                            {{ $testRequest->estimated_completion_date ? $testRequest->estimated_completion_date->format('d M Y') : 'Dalam Proses Penjadwalan' }}
                        </div>
                        <span class="text-xs text-slate-subtle">Hari Kerja Pelayanan</span>
                    </div>
                </div>

                <!-- CLINICAL PRIVACY NOTICE -->
                <div class="p-space-md bg-teal-surface/50 border-t border-teal-muted flex items-center justify-between flex-wrap gap-space-sm px-space-lg">
                    <div class="flex items-center gap-2 text-xs text-teal-deep">
                        <span class="material-symbols-outlined text-[18px]">security</span>
                        <span>Sesuai standar privasi rekam kesehatan Kemenkes, angka hasil uji medis tidak dipublikasikan pada pelacakan publik terbuka.</span>
                    </div>

                    @if($currentStage >= 5)
                        <a href="{{ route('verifikasi-lhu', ['fpps' => $testRequest->fpps_number]) }}" class="px-space-md py-2 rounded-xl bg-verified-green text-on-primary font-bold text-xs hover:bg-green-700 transition-colors shadow-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">file_download</span>
                            <span>Ambil LHU di Gerbang Verifikasi</span>
                        </a>
                    @else
                        <span class="text-xs text-slate-subtle italic">Dokumen LHU akan terbit setelah pengujian &amp; validasi selesai.</span>
                    @endif
                </div>

                <!-- STATUS HISTORIES TIMELINE -->
                @if($testRequest->statusHistories->isNotEmpty())
                    <div class="p-space-lg lg:p-space-xl border-t border-border-hairline space-y-space-md">
                        <h3 class="font-headline-sm text-base font-bold text-slate-body flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">history</span>
                            Riwayat Catatan &amp; Tindak Lanjut Sistem
                        </h3>

                        <div class="space-y-space-sm pl-4 border-l-2 border-primary/20">
                            @foreach($testRequest->statusHistories as $hist)
                                <div wire:key="hist-{{ $hist->id }}" class="relative pl-4">
                                    <div class="absolute -left-[23px] top-1 w-3 h-3 rounded-full bg-primary border-2 border-surface"></div>
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-subtle mb-0.5">
                                        <span class="font-bold text-slate-body uppercase">{{ $hist->status }}</span>
                                        <span>{{ $hist->created_at->format('d M Y, H:i') }} WIT</span>
                                    </div>
                                    <p class="text-xs text-on-surface-variant">{{ $hist->note ?? 'Perubahan status permohonan pengujian.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @elseif($hasSearched)
            <div class="bg-surface-container-lowest rounded-2xl p-space-xl border border-dashed border-border-hairline text-center space-y-space-sm max-w-xl mx-auto">
                <span class="material-symbols-outlined text-[48px] text-pending-amber">search_off</span>
                <h3 class="font-headline-sm text-lg font-bold text-slate-body">Nomor FPPS Tidak Ditemukan</h3>
                <p class="text-xs text-slate-subtle max-w-md mx-auto">
                    Nomor pendaftaran "<span class="font-mono font-bold">{{ $fpps }}</span>" tidak terdaftar pada basis data kami. Mohon pastikan kembali format nomor pada bukti pendaftaran atau hubungi loket helpdesk.
                </p>
            </div>
        @endif
    </div>
</div>