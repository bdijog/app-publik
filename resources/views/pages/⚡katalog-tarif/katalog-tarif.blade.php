<div class="flex flex-col w-full pb-36">
    <!-- Top Context Header & Hero Title -->
    <section class="w-full bg-surface-container-lowest border-b border-border-hairline">
        <div class="max-w-7xl mx-auto px-gutter py-space-xl">
            <!-- Breadcrumb / Status Marker -->
            <div class="flex flex-wrap items-center gap-space-xs text-slate-subtle font-label-sm text-label-sm mb-space-sm">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1 text-primary font-semibold">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    Beranda
                </a>
                <span class="text-outline-variant">/</span>
                <span>Tarif PNBP Terstandar</span>
                <span class="text-outline-variant">/</span>
                <span class="text-on-surface font-semibold">Katalog &amp; Simulasi Biaya</span>
            </div>

            <!-- Institutional Headline & Description -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-lg">
                <div class="max-w-3xl space-y-space-sm">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-8 w-auto object-contain">
                    </div>
                    <h1 class="font-headline-xl text-headline-xl text-slate-body tracking-tight font-bold">
                        Katalog Layanan &amp; Transparansi Tarif Resmi
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        Sesuai Peraturan Tarif Resmi Pelayanan Kesehatan Kementerian Kesehatan RI (PP No. 64/2019). Transparan, terstandar, dan dapat disimulasikan secara mandiri sebelum pengajuan FPPS.
                    </p>
                </div>

                <!-- Metric Badges Group -->
                <div class="flex items-center gap-space-md shrink-0">
                    <div class="bg-teal-surface px-space-md py-space-sm rounded-2xl flex items-center gap-space-sm border border-teal-muted">
                        <span class="material-symbols-outlined text-teal-deep text-[24px]">policy</span>
                        <div class="flex flex-col">
                            <span class="font-label-sm text-[11px] text-teal-deep uppercase font-bold tracking-wider">Regulasi Baku</span>
                            <span class="font-label-md text-label-md text-slate-body font-bold">Permenkes &amp; PP 64/2019</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-low px-space-md py-space-sm rounded-2xl hidden sm:flex items-center gap-space-sm border border-border-hairline">
                        <span class="material-symbols-outlined text-verified-green text-[24px]">verified</span>
                        <div class="flex flex-col">
                            <span class="font-label-sm text-[11px] text-slate-subtle uppercase font-bold tracking-wider">Standar Mutu</span>
                            <span class="font-label-md text-label-md text-slate-body font-bold">KAN ISO/IEC 17025</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instant Search Bar -->
            <div class="mt-space-xl relative max-w-4xl">
                <div class="relative flex items-center bg-surface rounded-2xl shadow-sm border border-border-hairline transition-all focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20">
                    <span class="material-symbols-outlined text-slate-subtle ml-space-md text-[24px]">search</span>
                    <input wire:model.live.debounce.300ms="search" 
                           class="w-full py-3.5 pl-space-sm pr-space-xl bg-transparent font-body-md text-body-md text-on-surface placeholder:text-slate-subtle focus:outline-none" 
                           placeholder="Cari parameter: E. Coli, Timbal, pH, Boraks, Salmonella, Nitrat, Suhu..." 
                           type="text"/>
                    @if(!empty($search))
                        <button wire:click="$set('search', '')" class="absolute right-space-md text-slate-subtle hover:text-on-surface" type="button" title="Bersihkan">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-space-xs px-space-xs text-slate-subtle font-label-sm text-xs">
                    <span>Pencarian mencakup 17 kelompok pengujian dan parameter terakreditasi KAN.</span>
                    <span class="font-medium text-teal-deep">Ditemukan: {{ $parameters->total() }} Parameter</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 17 Service Categories Pill Filter -->
    <section class="w-full bg-surface-container-low/80 border-b border-border-hairline sticky top-20 z-30 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-gutter py-space-sm">
            <div class="flex items-center justify-between mb-space-xs">
                <span class="font-label-sm text-xs text-slate-subtle font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">tune</span>
                    17 Kelompok Layanan Pengujian:
                </span>
                <button wire:click="selectCategory('semua')" class="font-label-sm text-xs text-primary hover:underline font-semibold cursor-pointer">
                    Reset Filter Kategori
                </button>
            </div>

            <!-- Scrollable Category Pill List -->
            <div class="flex items-center gap-space-xs overflow-x-auto pb-space-xs pt-0.5 no-scrollbar">
                <button wire:click="selectCategory('semua')" 
                        class="whitespace-nowrap px-space-md py-1.5 rounded-full font-label-sm text-xs transition-all flex items-center gap-1 {{ $category === 'semua' ? 'bg-primary-container text-on-primary shadow-sm font-bold' : 'bg-surface-container-lowest text-slate-body border border-border-hairline hover:bg-surface-container-high' }}">
                    @if($category === 'semua')
                        <span class="material-symbols-outlined text-[15px]">check_circle</span>
                    @endif
                    Semua Layanan
                </button>
                @foreach($serviceGroups as $grp)
                    <button wire:key="pill-{{ $grp->id }}" 
                            wire:click="selectCategory('{{ $grp->code }}')" 
                            class="whitespace-nowrap px-space-md py-1.5 rounded-full font-label-sm text-xs transition-all flex items-center gap-1 {{ $category === $grp->code ? 'bg-primary-container text-on-primary shadow-sm font-bold' : 'bg-surface-container-lowest text-slate-body border border-border-hairline hover:bg-surface-container-high' }}">
                        @if($category === $grp->code)
                            <span class="material-symbols-outlined text-[15px]">check_circle</span>
                        @endif
                        {{ $grp->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT: PACKAGES & PARAMETERS -->
    <div class="max-w-7xl mx-auto px-gutter py-space-xl w-full space-y-space-xl">
        <!-- PAKET UJI POPULER (Jika ada) -->
        @if($packages->isNotEmpty())
            <div class="space-y-space-md">
                <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                        <div class="inline-flex items-center gap-1 text-primary text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                            Paket Hemat Rekomendasi
                        </div>
                        <h2 class="font-headline-md text-headline-md text-slate-body font-bold">Paket Pemeriksaan Terpadu</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                    @foreach($packages as $pkg)
                        @php
                            $pkgParamIds = $pkg->parameters->pluck('id')->toArray();
                            $pkgTotal = $pkg->parameters->sum(fn($p) => (float)($p->tariffs->first()?->amount ?? 0));
                            $isPkgFullySelected = count($pkgParamIds) > 0 && count(array_intersect($pkgParamIds, $selectedParameters)) === count($pkgParamIds);
                        @endphp
                        <div wire:key="pkg-{{ $pkg->id }}" class="bg-surface-container-lowest rounded-2xl p-space-lg border border-border-hairline shadow-sm hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-deep to-sky-progress"></div>
                            <div>
                                <div class="flex items-center justify-between mb-space-xs">
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-teal-surface text-primary border border-teal-muted">
                                        {{ $pkg->serviceGroup->name ?? 'Paket Uji' }}
                                    </span>
                                    <span class="text-xs text-slate-subtle">{{ $pkg->parameters->count() }} Parameter</span>
                                </div>
                                <h3 class="font-headline-sm text-headline-sm text-slate-body font-bold mb-space-xs mt-1">
                                    {{ $pkg->name }}
                                </h3>
                                <p class="text-xs text-slate-subtle mb-space-md">
                                    Paket pengujian lengkap parameter baku mutu kesehatan dan lingkungan standar Kemenkes.
                                </p>
                                <div class="space-y-1 mb-space-md max-h-32 overflow-y-auto no-scrollbar">
                                    @foreach($pkg->parameters->take(5) as $param)
                                        <div class="flex items-center justify-between text-xs py-0.5 border-b border-border-hairline/40 text-on-surface-variant">
                                            <span>{{ $param->name }}</span>
                                            <span class="font-mono text-slate-body">Rp {{ number_format((float)($param->tariffs->first()?->amount ?? 0), 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    @if($pkg->parameters->count() > 5)
                                        <div class="text-[11px] text-slate-subtle italic text-center pt-1">
                                            + {{ $pkg->parameters->count() - 5 }} parameter lainnya
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-space-sm border-t border-border-hairline flex items-center justify-between">
                                <div>
                                    <div class="text-[11px] text-slate-subtle">Estimasi Biaya Paket</div>
                                    <div class="text-lg font-bold text-primary font-mono">
                                        Rp {{ number_format($pkgTotal, 0, ',', '.') }}
                                    </div>
                                </div>
                                <button wire:click="togglePackage({{ $pkg->id }})" 
                                        type="button" 
                                        class="px-space-md py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1 {{ $isPkgFullySelected ? 'bg-verified-green text-on-primary shadow-xs' : 'bg-primary-container text-on-primary hover:bg-teal-hover' }}">
                                    <span class="material-symbols-outlined text-[16px]">{{ $isPkgFullySelected ? 'check' : 'add' }}</span>
                                    <span>{{ $isPkgFullySelected ? 'Terpilih' : 'Pilih Paket' }}</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PARAMETER SATUAN LIST / GRID -->
        <div class="space-y-space-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm">
                <div>
                    <h2 class="font-headline-md text-headline-md text-slate-body font-bold">Katalog Parameter Pengujian Satuan</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        Centang parameter yang Anda butuhkan untuk kalkulasi estimasi total biaya pengujian laboratorium.
                    </p>
                </div>
                <div class="flex items-center gap-space-xs text-xs text-slate-subtle">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-verified-green"></span>
                    <span>Terakreditasi KAN ISO 17025</span>
                </div>
            </div>

            <!-- Parameters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @forelse($parameters as $param)
                    @php
                        $isSelected = in_array($param->id, $selectedParameters, true);
                        $tariff = $param->tariffs->first();
                        $tariffAmount = (float)($tariff?->amount ?? 0);
                    @endphp
                    <div wire:key="param-card-{{ $param->id }}" 
                         class="bg-surface-container-lowest p-space-md rounded-2xl border transition-all flex flex-col justify-between cursor-pointer {{ $isSelected ? 'border-primary ring-2 ring-primary/20 shadow-md bg-teal-surface/20' : 'border-border-hairline hover:border-primary/40 hover:shadow-sm' }}"
                         wire:click="toggleParameter({{ $param->id }})">
                        <div>
                            <div class="flex items-start justify-between gap-space-xs mb-space-xs">
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-surface-container-low text-slate-subtle">
                                    {{ $param->serviceGroup->name ?? 'Pengujian' }}
                                </span>
                                <input type="checkbox" 
                                       class="w-5 h-5 rounded text-primary focus:ring-primary accent-primary cursor-pointer" 
                                       @checked($isSelected)
                                       wire:click.stop="toggleParameter({{ $param->id }})">
                            </div>
                            <h3 class="font-label-md text-base font-bold text-slate-body mb-1">
                                {{ $param->name }}
                            </h3>
                            <div class="space-y-1 text-xs text-on-surface-variant mb-space-sm">
                                <div class="flex items-center gap-1">
                                    <span class="text-slate-subtle">Metode:</span>
                                    <span class="font-mono text-slate-body">{{ $param->method ?? 'Standar Uji Baku SNI/APHA' }}</span>
                                </div>
                                @if($param->unit)
                                    <div class="flex items-center gap-1">
                                        <span class="text-slate-subtle">Satuan:</span>
                                        <span class="font-mono text-slate-body">{{ $param->unit }}</span>
                                    </div>
                                @endif
                                @if($param->threshold_min || $param->threshold_max)
                                    <div class="flex items-center gap-1">
                                        <span class="text-slate-subtle">Baku Mutu:</span>
                                        <span class="font-mono text-slate-body">
                                            {{ $param->threshold_min ? $param->threshold_min.' - ' : '' }}{{ $param->threshold_max ?? '' }} {{ $param->unit }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-space-sm border-t border-border-hairline flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase text-slate-subtle font-bold tracking-wider">Tarif PNBP</span>
                                <div class="font-mono font-bold text-primary text-base">
                                    Rp {{ number_format($tariffAmount, 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $isSelected ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant' }}">
                                {{ $isSelected ? 'Terpilih' : '+ Tambah' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-surface-container-lowest rounded-2xl border border-dashed border-border-hairline">
                        <span class="material-symbols-outlined text-[48px] text-slate-subtle mb-2">search_off</span>
                        <h4 class="font-headline-sm text-slate-body font-bold mb-1">Tidak Ditemukan Parameter</h4>
                        <p class="text-sm text-slate-subtle max-w-md mx-auto mb-4">
                            Parameter "{{ $search }}" tidak ditemukan pada kategori terpilih. Silakan coba kata kunci lain atau reset filter.
                        </p>
                        <button wire:click="selectCategory('semua')" class="px-space-md py-2 rounded-xl bg-primary text-on-primary text-xs font-bold">
                            Tampilkan Semua Parameter
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pt-space-md">
                {{ $parameters->links() }}
            </div>
        </div>
    </div>

    <!-- STICKY BOTTOM COST ESTIMATION BAR -->
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-surface-container-lowest/95 backdrop-blur-xl border-t border-border-hairline shadow-[0_-4px_16px_rgba(0,0,0,0.06)] py-space-sm px-gutter">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-space-sm">
            <div class="flex items-center gap-space-md w-full sm:w-auto justify-between sm:justify-start">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-surface text-primary flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[22px]">calculate</span>
                    </div>
                    <div>
                        <div class="text-xs text-slate-subtle">
                            <span class="font-bold text-slate-body">{{ $selectedCount }}</span> Parameter Terpilih
                        </div>
                        <div class="text-xl font-bold font-mono text-primary">
                            Rp {{ number_format($totalEstimatedCost, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if($selectedCount > 0)
                    <button wire:click="resetSelection" type="button" class="text-xs text-slate-subtle hover:text-error underline cursor-pointer">
                        Reset Pilihan
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-space-sm w-full sm:w-auto">
                <a href="{{ route('pendaftaran') }}" class="flex-1 sm:flex-none text-center px-space-md py-2.5 rounded-xl border border-border-hairline text-slate-body font-semibold text-xs hover:bg-surface-container transition-colors">
                    Daftar Langsung
                </a>
                <button wire:click="proceedToRegistration" 
                        type="button" 
                        class="flex-1 sm:flex-none px-space-xl py-2.5 rounded-xl bg-primary-container text-on-primary font-bold text-xs hover:bg-teal-hover transition-colors shadow-md flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>Lanjut ke Formulir FPPS</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </button>
            </div>
        </div>
    </div>
</div>