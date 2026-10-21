<div class="py-space-lg bg-surface min-h-[calc(100vh-5rem)]">
    <div class="max-w-7xl mx-auto px-gutter space-y-space-lg">
        
        <!-- HEADER PROFILE & WELCOME BANNER -->
        <div class="bg-gradient-to-r from-teal-deep via-teal-hover to-primary rounded-3xl p-6 sm:p-8 text-on-primary shadow-lg relative overflow-hidden">
            <!-- Decorative backdrop circles -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-20 -top-12 w-48 h-48 bg-teal-muted/10 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <img src="{{ asset('images/logo-labkesmas-ambon-dark.png') }}" alt="Balai Labkesmas Ambon" class="h-8 sm:h-9 w-auto object-contain">
                        <span class="hidden sm:inline-block text-white/40">|</span>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-teal-surface text-xs font-semibold backdrop-blur-md">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            <span>Portal Pelanggan &amp; Faskes Terakreditasi</span>
                        </div>
                    </div>
                    <h1 class="font-headline-md text-2xl sm:text-3xl font-bold tracking-tight text-white">
                        Selamat Datang, {{ $this->customer?->name }} 👋
                    </h1>
                    <p class="text-white/80 text-sm max-w-2xl leading-relaxed">
                        @if($this->customer?->organization_name)
                            <span class="font-semibold text-white">{{ $this->customer->organization_name }}</span> &bull;
                        @endif
                        Kelola seluruh pendaftaran uji sampel, pantau progres analisis laboratorium secara real-time, dan unduh Laporan Hasil Uji (LHU) resmi berstempel digital.
                    </p>
                </div>

                <!-- Quick Action Button -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('pendaftaran') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white text-primary font-bold text-sm shadow-md hover:bg-teal-surface transition-all transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        <span>Daftar Uji Baru</span>
                    </a>
                    <a href="{{ route('lacak') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur-md transition-all">
                        <span class="material-symbols-outlined text-[20px]">radar</span>
                        <span>Lacak FPPS Lain</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- NOTIFICATION ALERTS -->
        @if (session()->has('success'))
            <div class="p-4 rounded-2xl bg-teal-surface border border-teal-muted text-primary flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <span class="material-symbols-outlined text-[20px] text-teal-deep">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 rounded-2xl bg-rejection-tint border border-error/20 text-error flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- 4 BENTO KPI SUMMARY CARDS -->
        @php $stats = $this->summaryStats; @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Stat 1: Total Permohonan -->
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-hairline shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-label-md text-xs font-semibold text-slate-subtle uppercase tracking-wider">Total Permohonan</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-surface text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">assignment</span>
                    </div>
                </div>
                <div class="font-headline-sm text-2xl sm:text-3xl font-bold text-slate-body">{{ number_format($stats['total_requests']) }}</div>
                <p class="text-xs text-slate-subtle mt-1">Seluruh riwayat pendaftaran</p>
            </div>

            <!-- Stat 2: Sedang Diproses -->
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-hairline shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-label-md text-xs font-semibold text-slate-subtle uppercase tracking-wider">Sedang Diproses</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">biotech</span>
                    </div>
                </div>
                <div class="font-headline-sm text-2xl sm:text-3xl font-bold text-slate-body">{{ number_format($stats['active_tests']) }}</div>
                <p class="text-xs text-slate-subtle mt-1">Dalam alur uji &amp; validasi</p>
            </div>

            <!-- Stat 3: Tagihan Belum Dibayar -->
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-hairline shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-label-md text-xs font-semibold text-slate-subtle uppercase tracking-wider">Menunggu Bayar</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">payments</span>
                    </div>
                </div>
                <div class="font-headline-sm text-2xl sm:text-3xl font-bold text-slate-body">{{ number_format($stats['pending_payments']) }}</div>
                <p class="text-xs text-slate-subtle mt-1">Tagihan permohonan aktif</p>
            </div>

            <!-- Stat 4: LHU Siap Unduh -->
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-hairline shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="font-label-md text-xs font-semibold text-slate-subtle uppercase tracking-wider">LHU Siap Unduh</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">verified_user</span>
                    </div>
                </div>
                <div class="font-headline-sm text-2xl sm:text-3xl font-bold text-slate-body">{{ number_format($stats['ready_reports']) }}</div>
                <p class="text-xs text-slate-subtle mt-1">Dokumen LHU final berstempel</p>
            </div>
        </div>

        <!-- LATEST ACTIVE FPPS LIVE STEPPER -->
        @php $latest = $this->latestActiveRequest; @endphp
        @if($latest)
            <div class="bg-surface-container-lowest rounded-3xl border border-border-hairline p-6 sm:p-8 shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border-hairline">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-teal-surface text-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">hourglass_top</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-headline-sm text-lg font-bold text-slate-body">Progres Permohonan Terkini</h2>
                                <span class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-bold">{{ $latest->fpps_number }}</span>
                            </div>
                            <p class="text-xs text-slate-subtle">
                                Layanan: <strong class="text-slate-body">{{ $latest->serviceGroup?->name }}</strong> &bull;
                                Didaftarkan: {{ $latest->registered_at?->translatedFormat('d F Y, H:i') }} WIT
                            </p>
                        </div>
                    </div>

                    @if($latest->estimated_completion_date)
                        <div class="text-right sm:text-right">
                            <span class="text-xs text-slate-subtle block">Target Selesai:</span>
                            <span class="text-sm font-bold text-primary">{{ $latest->estimated_completion_date->translatedFormat('d F Y') }}</span>
                        </div>
                    @endif
                </div>

                <!-- 5-STEP VISUAL STEPPER -->
                @php
                    $status = $latest->status instanceof \App\Enums\TestRequestStatus ? $latest->status->value : (string) $latest->status;
                    $stepIndex = match($status) {
                        'draft', 'submitted' => 1,
                        'awaiting_payment', 'awaiting_sample' => 2,
                        'in_progress' => 3,
                        'validated' => 4,
                        'report_issued', 'completed' => 5,
                        default => 1,
                    };
                @endphp

                <div class="relative py-4">
                    <!-- Progress Bar Background -->
                    <div class="hidden sm:block absolute top-1/2 left-8 right-8 h-1.5 bg-slate-100 -translate-y-1/2 z-0 rounded-full"></div>
                    <div class="hidden sm:block absolute top-1/2 left-8 h-1.5 bg-primary -translate-y-1/2 z-0 rounded-full transition-all duration-500"
                         style="width: {{ (($stepIndex - 1) / 4) * 85 }}%;"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 relative z-10">
                        <!-- Step 1: Pendaftaran -->
                        <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-all {{ $stepIndex >= 1 ? 'bg-primary text-on-primary ring-4 ring-teal-surface' : 'bg-slate-100 text-slate-subtle' }}">
                                @if($stepIndex > 1) <span class="material-symbols-outlined text-[18px]">check</span> @else 1 @endif
                            </div>
                            <div>
                                <h4 class="font-label-md text-xs font-bold {{ $stepIndex >= 1 ? 'text-primary' : 'text-slate-subtle' }}">Pendaftaran</h4>
                                <p class="text-[11px] text-slate-subtle">Permohonan diterima</p>
                            </div>
                        </div>

                        <!-- Step 2: Sampel Diterima -->
                        <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-all {{ $stepIndex >= 2 ? 'bg-primary text-on-primary ring-4 ring-teal-surface' : 'bg-slate-100 text-slate-subtle' }}">
                                @if($stepIndex > 2) <span class="material-symbols-outlined text-[18px]">check</span> @else 2 @endif
                            </div>
                            <div>
                                <h4 class="font-label-md text-xs font-bold {{ $stepIndex >= 2 ? 'text-primary' : 'text-slate-subtle' }}">Penerimaan Sampel</h4>
                                <p class="text-[11px] text-slate-subtle">Cek kelaikan fisik</p>
                            </div>
                        </div>

                        <!-- Step 3: Pengujian Lab -->
                        <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-all {{ $stepIndex >= 3 ? 'bg-primary text-on-primary ring-4 ring-teal-surface' : 'bg-slate-100 text-slate-subtle' }}">
                                @if($stepIndex > 3) <span class="material-symbols-outlined text-[18px]">check</span> @else 3 @endif
                            </div>
                            <div>
                                <h4 class="font-label-md text-xs font-bold {{ $stepIndex >= 3 ? 'text-primary' : 'text-slate-subtle' }}">Pengujian Lab</h4>
                                <p class="text-[11px] text-slate-subtle">Analisis parameter</p>
                            </div>
                        </div>

                        <!-- Step 4: Validasi Hasil -->
                        <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-all {{ $stepIndex >= 4 ? 'bg-primary text-on-primary ring-4 ring-teal-surface' : 'bg-slate-100 text-slate-subtle' }}">
                                @if($stepIndex > 4) <span class="material-symbols-outlined text-[18px]">check</span> @else 4 @endif
                            </div>
                            <div>
                                <h4 class="font-label-md text-xs font-bold {{ $stepIndex >= 4 ? 'text-primary' : 'text-slate-subtle' }}">Validasi Berjenjang</h4>
                                <p class="text-[11px] text-slate-subtle">Manager Lab &amp; Medis</p>
                            </div>
                        </div>

                        <!-- Step 5: LHU Terbit -->
                        <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition-all {{ $stepIndex >= 5 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-slate-100 text-slate-subtle' }}">
                                @if($stepIndex >= 5) <span class="material-symbols-outlined text-[18px]">check</span> @else 5 @endif
                            </div>
                            <div>
                                <h4 class="font-label-md text-xs font-bold {{ $stepIndex >= 5 ? 'text-emerald-700' : 'text-slate-subtle' }}">LHU Final</h4>
                                <p class="text-[11px] text-slate-subtle">Siap diunduh / diambil</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info Stepper -->
                <div class="pt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-slate-subtle bg-surface-container-low/50 p-4 rounded-2xl">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary">info</span>
                        <span>
                            Status saat ini: <strong class="text-slate-body">{{ $latest->status instanceof \App\Enums\TestRequestStatus ? $latest->status->label() : ucfirst($status) }}</strong>
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $latestFinalReport = $latest->testReports->first(fn($r) => $r->status === \App\Enums\ReportStatus::Final || (string)$r->status?->value === 'final' || (string)$r->status === 'final');
                        @endphp
                        @if($latestFinalReport)
                            <a href="{{ route('customer.reports.print', $latestFinalReport->id) }}" target="_blank" class="text-emerald-700 font-bold hover:underline inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">description</span>
                                <span>Buka / Cetak LHU</span>
                            </a>
                            <span class="text-slate-300">•</span>
                        @endif
                        <a href="{{ route('lacak', ['fpps' => $latest->fpps_number]) }}" class="text-primary font-bold hover:underline inline-flex items-center gap-1">
                            <span>Lihat Rincian Riwayat</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- DATA MANAGEMENT SECTION WITH TABS -->
        <div class="bg-surface-container-lowest rounded-3xl border border-border-hairline shadow-xs overflow-hidden" x-data="{ currentTab: @entangle('activeTab') }">
            <!-- TAB BAR -->
            <div class="flex items-center gap-2 px-6 pt-5 border-b border-border-hairline bg-surface-container-low/30 overflow-x-auto">
                <button type="button" @click="currentTab = 'requests'" 
                        :class="currentTab === 'requests' ? 'border-primary text-primary font-bold' : 'border-transparent text-slate-subtle hover:text-slate-body'"
                        class="px-4 py-3 border-b-2 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">list_alt</span>
                    <span>Daftar Permohonan Uji &amp; Riwayat Pengujian Sampel Mandiri</span>
                </button>

                <button type="button" @click="currentTab = 'reports'" 
                        :class="currentTab === 'reports' ? 'border-primary text-primary font-bold' : 'border-transparent text-slate-subtle hover:text-slate-body'"
                        class="px-4 py-3 border-b-2 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    <span>Arsip Dokumen LHU Resmi</span>
                </button>
            </div>

            <!-- TAB 1: DAFTAR PERMOHONAN -->
            <div x-show="currentTab === 'requests'" class="p-6 space-y-4">
                <!-- Search & Filters -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <div class="relative flex-1 max-w-md">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-subtle text-[18px]">search</span>
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               placeholder="Cari Nomor FPPS atau No. Permohonan..." 
                               class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-border-hairline bg-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div class="flex items-center gap-2">
                        <select wire:model.live="statusFilter" class="px-3 py-2 text-sm rounded-xl border border-border-hairline bg-surface focus:outline-none focus:border-primary text-slate-body">
                            <option value="all">Semua Status</option>
                            <option value="active">Sedang Aktif</option>
                            <option value="unpaid">Menunggu Bayar</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </div>

                <!-- Requests Table -->
                @php $requests = $this->requests; @endphp
                @if($requests->isNotEmpty())
                    <div class="overflow-x-auto rounded-2xl border border-border-hairline">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-surface-container-low text-xs font-semibold text-slate-subtle uppercase border-b border-border-hairline">
                                <tr>
                                    <th class="px-5 py-3.5">Nomor FPPS</th>
                                    <th class="px-5 py-3.5">Kelompok Layanan</th>
                                    <th class="px-5 py-3.5">Tgl Daftar</th>
                                    <th class="px-5 py-3.5">Total Biaya</th>
                                    <th class="px-5 py-3.5">Status Pembayaran</th>
                                    <th class="px-5 py-3.5">Status Layanan</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-hairline text-slate-body">
                                @foreach($requests as $req)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="px-5 py-4 font-bold text-primary whitespace-nowrap">
                                            {{ $req->fpps_number }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="font-semibold">{{ $req->serviceGroup?->name }}</div>
                                            @if($req->servicePackage)
                                                <div class="text-xs text-slate-subtle">{{ $req->servicePackage->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-slate-subtle text-xs">
                                            {{ $req->registered_at?->format('d/m/Y') }}
                                        </td>
                                        <td class="px-5 py-4 font-semibold whitespace-nowrap">
                                            Rp {{ number_format($req->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($req->payment_status?->value === 'paid')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                                    <span class="material-symbols-outlined text-[14px]">check</span> Lunas
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                                    <span class="material-symbols-outlined text-[14px]">pending</span> Belum Bayar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-surface text-primary">
                                                {{ $req->status instanceof \App\Enums\TestRequestStatus ? $req->status->label() : ucfirst((string)$req->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center gap-2 justify-end">
                                                @php
                                                    $finalReport = $req->testReports->first(fn($r) => $r->status === \App\Enums\ReportStatus::Final || (string)$r->status?->value === 'final' || (string)$r->status === 'final');
                                                @endphp
                                                @if($finalReport)
                                                    <a href="{{ route('customer.reports.print', $finalReport->id) }}" 
                                                       target="_blank" 
                                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-surface text-primary font-bold text-xs hover:bg-primary hover:text-on-primary transition-colors border border-teal-muted"
                                                       title="Lihat / Cetak LHU Final">
                                                        <span class="material-symbols-outlined text-[14px]">description</span>
                                                        <span>LHU</span>
                                                    </a>
                                                @endif
                                                <a href="{{ route('customer.requests.receipt', $req->id) }}" 
                                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-surface-container-high text-slate-body font-semibold text-xs hover:bg-surface-container-highest transition-colors border border-border-hairline"
                                                   title="Unduh Tanda Terima FPPS">
                                                    <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                                                    <span>FPPS</span>
                                                </a>
                                                <a href="{{ route('lacak', ['fpps' => $req->fpps_number]) }}" class="text-primary hover:text-teal-hover font-semibold text-xs inline-flex items-center gap-1">
                                                    <span>Lacak</span>
                                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pt-2">
                        {{ $requests->links() }}
                    </div>
                @else
                    <div class="text-center py-12 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">folder_off</span>
                        </div>
                        <h4 class="font-bold text-slate-body text-sm">Belum Ada Permohonan Ditemukan</h4>
                        <p class="text-xs text-slate-subtle max-w-sm mx-auto">
                            Anda belum memiliki riwayat pengujian sampel atau tidak ada data yang cocok dengan kata kunci pencarian.
                        </p>
                        <a href="{{ route('pendaftaran') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-teal-hover transition-colors">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Ajukan Permohonan Uji Sekarang</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- TAB 2: ARSIP DOKUMEN LHU RESMI -->
            <div x-show="currentTab === 'reports'" class="p-6 space-y-4">
                @php $reports = $this->reports; @endphp
                @if($reports->isNotEmpty())
                    <div class="overflow-x-auto rounded-2xl border border-border-hairline">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-surface-container-low text-xs font-semibold text-slate-subtle uppercase border-b border-border-hairline">
                                <tr>
                                    <th class="px-5 py-3.5">Nomor LHU</th>
                                    <th class="px-5 py-3.5">Nomor FPPS</th>
                                    <th class="px-5 py-3.5">Layanan</th>
                                    <th class="px-5 py-3.5">Tanggal Terbit</th>
                                    <th class="px-5 py-3.5">Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi Unduh</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-hairline text-slate-body">
                                @foreach($reports as $rep)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="px-5 py-4 font-bold text-primary whitespace-nowrap">
                                            {{ $rep->report_number }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            {{ $rep->testRequest?->fpps_number }}
                                        </td>
                                        <td class="px-5 py-4">
                                            {{ $rep->testRequest?->serviceGroup?->name }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-subtle">
                                            {{ $rep->issued_at?->translatedFormat('d M Y') ?? '-' }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $rep->status?->value === 'final' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                <span class="material-symbols-outlined text-[14px]">verified</span>
                                                {{ $rep->status instanceof \App\Enums\ReportStatus ? $rep->status->label() : ucfirst((string)$rep->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            @php
                                                $isFinal = ($rep->status?->value === 'final' || $rep->status === \App\Enums\ReportStatus::Final || (string)$rep->status === 'final');
                                            @endphp
                                            @if($isFinal)
                                                <div class="inline-flex items-center gap-1.5 justify-end">
                                                    <a href="{{ route('customer.reports.print', $rep->id) }}" 
                                                       target="_blank" 
                                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-surface-container-high hover:bg-surface-container-highest text-slate-body font-semibold text-xs transition-colors border border-border-hairline"
                                                       title="Buka & Cetak PDF LHU di Tab Baru">
                                                        <span class="material-symbols-outlined text-[15px]">visibility</span>
                                                        <span>Lihat / Cetak</span>
                                                    </a>
                                                    <a href="{{ route('customer.reports.download', $rep->id) }}" 
                                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-teal-surface text-primary hover:bg-primary hover:text-on-primary font-bold text-xs transition-colors shadow-xs"
                                                       title="Unduh Berkas PDF LHU Resmi">
                                                        <span class="material-symbols-outlined text-[15px]">download</span>
                                                        <span>Unduh PDF</span>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-subtle italic">Dalam proses</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-2">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="text-center py-12 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">description</span>
                        </div>
                        <h4 class="font-bold text-slate-body text-sm">Belum Ada Dokumen LHU Final</h4>
                        <p class="text-xs text-slate-subtle max-w-sm mx-auto">
                            Laporan Hasil Uji (LHU) resmi akan otomatis tampil di sini setelah seluruh proses pengujian laboratorium dan validasi Kepala Instalasi selesai disahkan.
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>