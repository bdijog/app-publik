<div class="relative w-full overflow-hidden bg-surface py-space-xl">
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[340px] bg-gradient-to-b from-teal-muted/40 via-sky-tint/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-6xl mx-auto px-gutter">
        <!-- Top Shield Header & Institutional Context -->
        <div class="text-center max-w-3xl mx-auto space-y-space-md mb-space-xl">
            <div class="flex items-center justify-center mb-1">
                <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-10 w-auto object-contain">
            </div>
            <div class="inline-flex items-center gap-space-xs bg-teal-surface text-primary px-space-md py-space-xs rounded-full shadow-sm border border-teal-muted">
                <span class="material-symbols-outlined text-[18px] text-primary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                <span class="font-label-sm text-xs tracking-wider uppercase font-semibold">Sistem Otentikasi Ganda Terproteksi • Kemenkes RI</span>
            </div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface font-bold">
                Gerbang Pengambilan Laporan Hasil Uji (LHU) Resmi
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                Untuk melindungi kerahasiaan hasil pengujian dan data rekam kesehatan masyarakat, akses unduh dokumen LHU mewajibkan pencocokan Nomor FPPS dan Nomor Telepon/WhatsApp pemohon yang telah terdaftar resmi pada sistem laboratorium.
            </p>

            <!-- Compliance Mini Pills -->
            <div class="flex flex-wrap items-center justify-center gap-space-sm pt-space-xs text-slate-subtle">
                <div class="flex items-center gap-space-xs text-xs bg-surface-container-low px-space-sm py-1 rounded-lg border border-border-hairline">
                    <span class="material-symbols-outlined text-[16px] text-teal-deep">lock</span>
                    <span>Enkripsi SHA-256 Hash Matching</span>
                </div>
                <div class="flex items-center gap-space-xs text-xs bg-surface-container-low px-space-sm py-1 rounded-lg border border-border-hairline">
                    <span class="material-symbols-outlined text-[16px] text-sky-progress">fingerprint</span>
                    <span>Tanda Tangan Elektronik Bersertifikasi BSrE</span>
                </div>
                <div class="flex items-center gap-space-xs text-xs bg-surface-container-low px-space-sm py-1 rounded-lg border border-border-hairline">
                    <span class="material-symbols-outlined text-[16px] text-verified-green">policy</span>
                    <span>Standar KAN ISO/IEC 17025:2017</span>
                </div>
            </div>
        </div>

        <!-- Main Layout: Form + Result Card -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter-lg items-start">
            <!-- Left 5 cols: Verification Form Card -->
            <div class="lg:col-span-5 bg-card-bg rounded-2xl shadow-sm border border-border-hairline p-space-lg relative overflow-hidden">
                <div class="flex items-center justify-between pb-space-sm border-b border-border-hairline mb-space-md">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-teal-deep text-[24px]">key</span>
                        <h2 class="font-headline-sm text-headline-sm text-slate-body font-bold">Otentikasi Pemohon</h2>
                    </div>
                    <span class="text-xs bg-teal-surface text-teal-deep px-2.5 py-0.5 rounded-full font-bold border border-teal-muted">
                        Validasi 2-Faktor
                    </span>
                </div>

                <p class="font-body-sm text-xs text-slate-subtle pb-space-md">
                    Masukkan data otentik yang Anda gunakan saat proses pendaftaran awal FPPS di loket atau portal online.
                </p>

                @if($errorMessage)
                    <div class="mb-space-md p-space-sm rounded-xl bg-rejection-tint text-rejection-red text-xs font-semibold flex items-start gap-2 border border-red-200">
                        <span class="material-symbols-outlined text-[18px] shrink-0">error</span>
                        <span>{{ $errorMessage }}</span>
                    </div>
                @endif

                <form wire:submit="verify" class="space-y-space-md">
                    <!-- FPPS Input -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-xs text-slate-body font-bold" for="fpps">
                                Nomor FPPS Resmi <span class="text-error">*</span>
                            </label>
                            <span class="text-[11px] text-teal-deep font-mono">FPPS-YYYYMM-XXXX</span>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-subtle text-[18px]">assignment</span>
                            <input wire:model="fpps" 
                                   id="fpps" 
                                   type="text" 
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm font-mono text-slate-body focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" 
                                   placeholder="Contoh: FPPS-202610-0001">
                        </div>
                        @error('fpps') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone Input -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="font-label-md text-xs text-slate-body font-bold" for="phone">
                                Nomor Handphone / WhatsApp Pemohon <span class="text-error">*</span>
                            </label>
                            <span class="text-[11px] text-slate-subtle">Pencocokan SHA-256</span>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-subtle text-[18px]">phone_android</span>
                            <input wire:model="phone" 
                                   id="phone" 
                                   type="tel" 
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm font-mono text-slate-body focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" 
                                   placeholder="Contoh: 081234567890">
                        </div>
                        @error('phone') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                        <p class="text-[11px] text-slate-subtle">
                            Sistem melakukan pencocokan hash matematis <span class="font-mono text-slate-body font-bold">SHA-256</span> dengan nomor telepon pemohon saat registrasi awal.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 rounded-xl bg-primary-container text-on-primary font-bold text-sm hover:bg-teal-hover transition-colors shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">lock_open</span>
                        <span>Verifikasi &amp; Buka Akses LHU</span>
                    </button>
                </form>

                <div class="mt-space-md pt-space-md border-t border-border-hairline/60 text-center">
                    <span class="text-xs text-slate-subtle">
                        Belum tahu nomor FPPS Anda? 
                        <a href="{{ route('lacak') }}" class="text-primary hover:underline font-semibold">Lacak Progres Sampel</a>
                    </span>
                </div>
            </div>

            <!-- Right 7 cols: Verification Result Area -->
            <div class="lg:col-span-7">
                @if($verified && $testRequest)
                    <div class="bg-card-bg rounded-2xl shadow-md border border-border-hairline overflow-hidden space-y-0">
                        <!-- Header Status -->
                        <div class="bg-gradient-to-r from-verified-green to-teal-deep p-space-md text-on-primary flex items-center justify-between gap-space-sm">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[24px]">verified</span>
                                <span class="font-bold text-sm uppercase tracking-wide">Otentikasi Berhasil • Akses Resmi Diberikan</span>
                            </div>
                            <span class="text-xs bg-white/20 px-2.5 py-0.5 rounded-full backdrop-blur-sm font-mono">
                                SHA-256 Match OK
                            </span>
                        </div>

                        <!-- Certificate Preview Body -->
                        <div class="p-space-lg space-y-space-md">
                            @if($testReport)
                                <!-- LHU Official Metadata Grid -->
                                <div class="bg-teal-surface p-space-md rounded-2xl border border-teal-muted flex flex-col md:flex-row md:items-center justify-between gap-space-sm">
                                    <div>
                                        <span class="text-[10px] text-slate-subtle uppercase font-bold tracking-wider">Nomor Sertifikat LHU Resmi</span>
                                        <div class="font-mono text-xl font-extrabold text-primary tracking-wide">
                                            {{ $testReport->report_number }}
                                        </div>
                                        <div class="text-xs text-teal-deep mt-0.5 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">fingerprint</span>
                                            Tersertifikasi Balai Labkesmas Ambon (v{{ $testReport->version }})
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-slate-subtle uppercase font-bold tracking-wider">Tanggal Pengesahan</span>
                                        <div class="text-sm font-bold text-slate-body">
                                            {{ $testReport->validated_on ? $testReport->validated_on->format('d M Y') : ($testReport->issued_at ? $testReport->issued_at->format('d M Y') : date('d M Y')) }}
                                        </div>
                                        <span class="text-xs text-verified-green font-semibold">Status: LHU Sah Terbit</span>
                                    </div>
                                </div>

                                <!-- Metadata Bento 4 boxes -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm text-xs">
                                    <div class="p-space-sm bg-surface-container-low rounded-xl border border-border-hairline">
                                        <span class="text-slate-subtle block text-[11px] font-semibold">Nama Pemohon:</span>
                                        <span class="font-bold text-slate-body">{{ $testRequest->customer->organization_name ?? $testRequest->customer->name }}</span>
                                    </div>
                                    <div class="p-space-sm bg-surface-container-low rounded-xl border border-border-hairline">
                                        <span class="text-slate-subtle block text-[11px] font-semibold">Nomor Registrasi FPPS:</span>
                                        <span class="font-mono font-bold text-slate-body">{{ $testRequest->fpps_number }}</span>
                                    </div>
                                    <div class="p-space-sm bg-surface-container-low rounded-xl border border-border-hairline">
                                        <span class="text-slate-subtle block text-[11px] font-semibold">Kelompok Pengujian:</span>
                                        <span class="font-bold text-primary">{{ $testRequest->serviceGroup->name ?? '-' }}</span>
                                    </div>
                                    <div class="p-space-sm bg-surface-container-low rounded-xl border border-border-hairline">
                                        <span class="text-slate-subtle block text-[11px] font-semibold">Keabsahan Tanda Tangan:</span>
                                        <span class="font-bold text-verified-green flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px]">verified</span>
                                            TTE Bersertifikat BSrE
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="pt-space-sm border-t border-border-hairline flex flex-col sm:flex-row items-center justify-between gap-space-sm">
                                    <button wire:click="downloadReport" 
                                            onclick="alert('Mengunduh Laporan Hasil Uji (LHU) Resmi format PDF berstempel digital BSrE.');"
                                            class="w-full sm:w-auto px-space-xl py-3 rounded-xl bg-verified-green text-on-primary font-bold text-xs hover:bg-green-700 transition-colors shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                        <span class="material-symbols-outlined text-[18px]">download</span>
                                        <span>Unduh Dokumen LHU Sah (PDF)</span>
                                    </button>

                                    <a href="{{ route('scan-lhu', ['number' => $testReport->report_number]) }}" class="text-xs text-primary hover:underline font-semibold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">qr_code</span>
                                        <span>Verifikasi QR Dokumen Publik</span>
                                    </a>
                                </div>
                            @else
                                <!-- Request exists but Report is not issued yet -->
                                <div class="py-8 text-center space-y-space-sm">
                                    <div class="w-16 h-16 rounded-full bg-pending-tint text-pending-amber mx-auto flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[32px]">pending_actions</span>
                                    </div>
                                    <h3 class="font-headline-sm text-base font-bold text-slate-body">LHU Masih Dalam Proses Pengujian / Validasi</h3>
                                    <p class="text-xs text-slate-subtle max-w-md mx-auto">
                                        Permohonan pengujian Anda dengan nomor FPPS <span class="font-mono font-bold text-slate-body">{{ $testRequest->fpps_number }}</span> telah terotentikasi, namun sertifikat LHU saat ini berstatus <span class="font-bold text-primary">{{ $testRequest->status instanceof \App\Enums\TestRequestStatus ? $testRequest->status->label() : $testRequest->status }}</span>. Dokumen LHU dapat diunduh segera setelah proses validasi selesai.
                                    </p>
                                    <div class="pt-2">
                                        <a href="{{ route('lacak', ['fpps' => $testRequest->fpps_number]) }}" class="inline-flex items-center gap-1.5 px-space-md py-2 rounded-xl bg-teal-surface text-primary font-bold text-xs hover:bg-teal-muted/40 transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">radar</span>
                                            <span>Pantau Detail Progres Pengujian</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Default Initial State Card -->
                    <div class="bg-surface-container-lowest rounded-2xl p-space-xl border border-dashed border-border-hairline text-center space-y-space-sm">
                        <div class="w-16 h-16 rounded-2xl bg-teal-surface text-primary mx-auto flex items-center justify-center border border-teal-muted">
                            <span class="material-symbols-outlined text-[32px]">verified_user</span>
                        </div>
                        <h3 class="font-headline-sm text-base font-bold text-slate-body">Menunggu Otentikasi Ganda</h3>
                        <p class="text-xs text-slate-subtle max-w-md mx-auto leading-relaxed">
                            Silakan masukkan Nomor FPPS resmi dan Nomor Handphone/WhatsApp yang terdaftar pada form di sebelah kiri untuk melihat dan mengunduh sertifikat Laporan Hasil Uji (LHU) sah.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>