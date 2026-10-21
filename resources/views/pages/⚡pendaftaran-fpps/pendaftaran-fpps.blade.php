<div class="max-w-7xl mx-auto px-gutter py-space-lg lg:py-space-xl w-full">
    <!-- Header Meta & Title -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md mb-space-xl">
        <div class="space-y-space-xs max-w-2xl">
            <div class="flex items-center gap-3 mb-1">
                <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-8 w-auto object-contain">
            </div>
            <div class="inline-flex items-center gap-space-xs bg-teal-surface text-primary px-space-sm py-1 rounded-full border border-teal-muted">
                <span class="material-symbols-outlined text-[16px] text-primary" style="font-variation-settings: 'FILL' 1;">assignment_turned_in</span>
                <span class="font-label-sm text-xs uppercase tracking-wider font-semibold">Formulir Pendaftaran FPPS Online</span>
            </div>
            <h1 class="font-headline-xl text-headline-xl text-slate-body tracking-tight font-bold">
                Pengajuan Permohonan Pengujian Sampel
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Pusat layanan registrasi elektronik terstandar ISO/IEC 17025:2017 Balai Labkesmas Ambon. Lengkapi formulir bertahap di bawah ini untuk menerbitkan nomor registrasi FPPS resmi.
            </p>
        </div>

        <div class="flex items-center gap-space-sm bg-surface-container-lowest p-space-sm px-space-md rounded-2xl shadow-sm border border-border-hairline self-start md:self-auto">
            <div class="w-10 h-10 rounded-xl bg-teal-surface text-primary flex items-center justify-center shrink-0 border border-teal-muted">
                <span class="material-symbols-outlined text-[22px]">contact_support</span>
            </div>
            <div>
                <span class="block font-label-sm text-xs text-slate-subtle">Butuh Asistensi Loket?</span>
                <a href="https://wa.me/6281240405678" target="_blank" class="font-label-md text-sm text-primary hover:underline font-bold">WA Helpdesk 0812-4040-5678</a>
            </div>
        </div>
    </div>

    <!-- HORIZONTAL STEPPER BAR -->
    <div class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg shadow-sm border border-border-hairline mb-space-xl overflow-x-auto">
        <div class="min-w-[680px] flex items-center justify-between relative">
            <div class="absolute top-5 left-8 right-8 h-1 bg-surface-container -z-0"></div>
            <div class="absolute top-5 left-8 h-1 bg-primary -z-0 transition-all duration-500"
                 style="width: {{ match($currentStep) { 1 => '10%', 2 => '32%', 3 => '56%', 4 => '80%', 5 => '100%', default => '10%' } }};"></div>

            <!-- Step 1 -->
            <div class="flex flex-col items-center gap-space-xs relative z-10 text-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all {{ $currentStep > 1 ? 'bg-verified-green text-on-primary' : ($currentStep === 1 ? 'bg-primary text-on-primary ring-4 ring-teal-muted' : 'bg-surface-container-low text-slate-subtle') }}">
                    @if($currentStep > 1)
                        <span class="material-symbols-outlined text-[18px]">check</span>
                    @else
                        1
                    @endif
                </div>
                <span class="font-label-sm text-xs {{ $currentStep >= 1 ? 'text-primary font-bold' : 'text-slate-subtle' }}">1. Profil Pemohon</span>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center gap-space-xs relative z-10 text-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all {{ $currentStep > 2 ? 'bg-verified-green text-on-primary' : ($currentStep === 2 ? 'bg-primary text-on-primary ring-4 ring-teal-muted' : 'bg-surface-container-low text-slate-subtle') }}">
                    @if($currentStep > 2)
                        <span class="material-symbols-outlined text-[18px]">check</span>
                    @else
                        2
                    @endif
                </div>
                <span class="font-label-sm text-xs {{ $currentStep >= 2 ? 'text-primary font-bold' : 'text-slate-subtle' }}">2. Layanan &amp; Parameter</span>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center gap-space-xs relative z-10 text-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all {{ $currentStep > 3 ? 'bg-verified-green text-on-primary' : ($currentStep === 3 ? 'bg-primary text-on-primary ring-4 ring-teal-muted' : 'bg-surface-container-low text-slate-subtle') }}">
                    @if($currentStep > 3)
                        <span class="material-symbols-outlined text-[18px]">check</span>
                    @else
                        3
                    @endif
                </div>
                <span class="font-label-sm text-xs {{ $currentStep >= 3 ? 'text-primary font-bold' : 'text-slate-subtle' }}">3. Sampel Fisik</span>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col items-center gap-space-xs relative z-10 text-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all {{ $currentStep > 4 ? 'bg-verified-green text-on-primary' : ($currentStep === 4 ? 'bg-primary text-on-primary ring-4 ring-teal-muted' : 'bg-surface-container-low text-slate-subtle') }}">
                    @if($currentStep > 4)
                        <span class="material-symbols-outlined text-[18px]">check</span>
                    @else
                        4
                    @endif
                </div>
                <span class="font-label-sm text-xs {{ $currentStep >= 4 ? 'text-primary font-bold' : 'text-slate-subtle' }}">4. Penyerahan &amp; Syarat</span>
            </div>

            <!-- Step 5 -->
            <div class="flex flex-col items-center gap-space-xs relative z-10 text-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all {{ $currentStep === 5 ? 'bg-verified-green text-on-primary ring-4 ring-teal-muted' : 'bg-surface-container text-slate-subtle' }}">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                </div>
                <span class="font-label-sm text-xs {{ $currentStep === 5 ? 'text-verified-green font-bold' : 'text-slate-subtle' }}">5. Bukti FPPS</span>
            </div>
        </div>
    </div>

    <!-- MAIN FORM GRID -->
    @if($currentStep < 5)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter-lg items-start">
            <!-- LEFT 8 COLS: FORM STEP CONTENT -->
            <div class="lg:col-span-8 space-y-space-lg">
                <!-- STEP 1: PROFIL PEMOHON -->
                @if($currentStep === 1)
                    <div class="bg-surface-container-lowest rounded-2xl p-space-lg lg:p-space-xl shadow-sm border border-border-hairline space-y-space-md">
                        <div class="flex items-center justify-between pb-space-sm border-b border-border-hairline">
                            <div class="flex items-center gap-space-sm">
                                <div class="w-8 h-8 rounded-lg bg-teal-surface text-primary flex items-center justify-center font-bold">
                                    1
                                </div>
                                <div>
                                    <h2 class="font-headline-md text-headline-md text-slate-body font-bold">Identitas &amp; Profil Pemohon</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tentukan kategori pendaftar dan identitas penanggung jawab sampel.</p>
                                </div>
                            </div>
                            @if(auth('customer')->check())
                                <span class="text-xs bg-teal-surface text-primary px-2.5 py-1 rounded-full font-semibold border border-teal-muted">
                                    Akun Terverifikasi
                                </span>
                            @endif
                        </div>

                        <!-- Applicant Type -->
                        <div class="space-y-space-xs">
                            <label class="block font-label-md text-sm text-slate-body font-semibold">Jenis / Kategori Pemohon <span class="text-error">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm">
                                <label class="flex items-center gap-space-sm p-space-sm px-space-md rounded-xl border cursor-pointer transition-all {{ $applicant_type === 'individual' ? 'bg-teal-surface/50 border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                                    <input wire:model.live="applicant_type" type="radio" value="individual" class="accent-primary w-4 h-4">
                                    <div class="flex flex-col">
                                        <span class="font-label-md text-sm text-slate-body font-semibold">Perorangan / Mandiri</span>
                                        <span class="text-[11px] text-slate-subtle">Masyarakat, Peneliti Mandiri</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-space-sm p-space-sm px-space-md rounded-xl border cursor-pointer transition-all {{ $applicant_type === 'organization' ? 'bg-teal-surface/50 border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                                    <input wire:model.live="applicant_type" type="radio" value="organization" class="accent-primary w-4 h-4">
                                    <div class="flex flex-col">
                                        <span class="font-label-md text-sm text-slate-body font-semibold">Instansi / Swasta</span>
                                        <span class="text-[11px] text-slate-subtle">Hotel, Depot Air, Perusahaan</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-space-sm p-space-sm px-space-md rounded-xl border cursor-pointer transition-all {{ $applicant_type === 'health_facility' ? 'bg-teal-surface/50 border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                                    <input wire:model.live="applicant_type" type="radio" value="health_facility" class="accent-primary w-4 h-4">
                                    <div class="flex flex-col">
                                        <span class="font-label-md text-sm text-slate-body font-semibold">Fasilitas Kesehatan</span>
                                        <span class="text-[11px] text-slate-subtle">RSUD, Puskesmas, Klinik</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Organization Name (if not individual) -->
                        @if($applicant_type !== 'individual')
                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="org_name">
                                    Nama Instansi / Rumah Sakit / Puskesmas / Perusahaan <span class="text-error">*</span>
                                </label>
                                <input wire:model="organization_name" type="text" id="org_name" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="Contoh: RSUD dr. M. Haulussy Ambon / PT Maluku Prima">
                                @error('organization_name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Name and Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="cust_name">
                                    Nama Lengkap Penanggung Jawab <span class="text-error">*</span>
                                </label>
                                <input wire:model="customer_name" type="text" id="cust_name" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="Contoh: Budi Santoso, S.Si">
                                @error('customer_name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="cust_phone">
                                    Nomor Handphone / WhatsApp Aktif <span class="text-error">*</span>
                                </label>
                                <input wire:model="customer_phone" type="tel" id="cust_phone" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm font-mono" placeholder="Contoh: 081234567890">
                                <p class="text-[11px] text-slate-subtle">Nomor ini digunakan untuk verifikasi pencocokan saat mengunduh sertifikat LHU.</p>
                                @error('customer_phone') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Email and Address -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="cust_email">
                                    Alamat Email Resmi <span class="text-error">*</span>
                                </label>
                                <input wire:model="customer_email" type="email" id="cust_email" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="nama@instansi.go.id">
                                @error('customer_email') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="cust_addr">
                                    Alamat Lengkap Pemohon / Faskes <span class="text-error">*</span>
                                </label>
                                <textarea wire:model="customer_address" id="cust_addr" rows="2" class="w-full px-3.5 py-2 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="Jl. Pattimura No. 45, Kec. Sirimau, Kota Ambon"></textarea>
                                @error('customer_address') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- STEP 2: LAYANAN & PARAMETER -->
                @if($currentStep === 2)
                    <div class="bg-surface-container-lowest rounded-2xl p-space-lg lg:p-space-xl shadow-sm border border-border-hairline space-y-space-md">
                        <div class="flex items-center justify-between pb-space-sm border-b border-border-hairline">
                            <div class="flex items-center gap-space-sm">
                                <div class="w-8 h-8 rounded-lg bg-teal-surface text-primary flex items-center justify-center font-bold">
                                    2
                                </div>
                                <div>
                                    <h2 class="font-headline-md text-headline-md text-slate-body font-bold">Pemilihan Layanan &amp; Parameter</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Pilih kelompok pengujian dan pilih parameter atau paket yang ingin diuji.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Select Service Group -->
                        <div class="space-y-1">
                            <label class="block font-label-md text-sm text-slate-body font-semibold">Kelompok Layanan Pengujian <span class="text-error">*</span></label>
                            <select wire:model.live="service_group_id" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm font-semibold text-slate-body">
                                @foreach($serviceGroups as $grp)
                                    <option value="{{ $grp->id }}">{{ $grp->name }} ({{ $grp->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Packages in this group -->
                        @if($groupPackages->isNotEmpty())
                            <div class="space-y-space-xs pt-space-xs">
                                <label class="block font-label-sm text-xs text-slate-subtle font-bold uppercase tracking-wider">
                                    Pilihan Paket Uji Terpadu:
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
                                    @foreach($groupPackages as $pkg)
                                        <div wire:key="step2-pkg-{{ $pkg->id }}" 
                                             wire:click="selectPackage({{ $pkg->id }})" 
                                             class="p-space-sm px-space-md rounded-xl border cursor-pointer transition-all {{ $service_package_id === $pkg->id ? 'bg-teal-surface/60 border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                                            <div class="flex items-center justify-between">
                                                <span class="font-label-md text-sm text-slate-body font-bold">{{ $pkg->name }}</span>
                                                <span class="text-xs text-primary font-semibold">{{ $pkg->parameters->count() }} Param</span>
                                            </div>
                                            <p class="text-[11px] text-slate-subtle mt-0.5">Pilih paket untuk otomatis mencentang seluruh parameter di dalamnya.</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Parameters Checklist -->
                        <div class="space-y-space-xs pt-space-sm">
                            <div class="flex items-center justify-between">
                                <label class="block font-label-md text-sm text-slate-body font-semibold">
                                    Parameter Pengujian Aktif <span class="text-error">*</span>
                                </label>
                                <span class="text-xs text-slate-subtle">{{ count($selected_parameters) }} parameter dipilih</span>
                            </div>

                            @error('selected_parameters')
                                <div class="p-2.5 rounded-xl bg-rejection-tint text-rejection-red text-xs font-semibold">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-80 overflow-y-auto p-1 border border-border-hairline/60 rounded-xl bg-surface">
                                @forelse($groupParameters as $param)
                                    @php
                                        $isParamSelected = in_array($param->id, $selected_parameters, true);
                                        $tariff = $param->tariffs->first();
                                        $price = (float)($tariff?->amount ?? 0);
                                    @endphp
                                    <label wire:key="param-check-{{ $param->id }}" 
                                           class="flex items-center justify-between p-2.5 rounded-lg border cursor-pointer transition-all {{ $isParamSelected ? 'bg-teal-surface border-primary' : 'bg-surface-container-lowest border-border-hairline hover:bg-surface-container-low' }}">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" 
                                                   class="w-4 h-4 rounded text-primary focus:ring-primary accent-primary" 
                                                   @checked($isParamSelected) 
                                                   wire:click="toggleParameter({{ $param->id }})">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-semibold text-slate-body">{{ $param->name }}</span>
                                                <span class="text-[10px] text-slate-subtle">{{ $param->method ?? 'Metode Baku SNI' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-primary shrink-0">
                                            Rp {{ number_format($price, 0, ',', '.') }}
                                        </span>
                                    </label>
                                @empty
                                    <div class="col-span-full py-8 text-center text-xs text-slate-subtle">
                                        Tidak ada parameter yang tersedia pada kelompok ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                <!-- STEP 3: SAMPEL FISIK -->
                @if($currentStep === 3)
                    <div class="bg-surface-container-lowest rounded-2xl p-space-lg lg:p-space-xl shadow-sm border border-border-hairline space-y-space-md">
                        <div class="flex items-center justify-between pb-space-sm border-b border-border-hairline">
                            <div class="flex items-center gap-space-sm">
                                <div class="w-8 h-8 rounded-lg bg-teal-surface text-primary flex items-center justify-center font-bold">
                                    3
                                </div>
                                <div>
                                    <h2 class="font-headline-md text-headline-md text-slate-body font-bold">Informasi Sampel Fisik</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Rincian identitas fisik sampel, lokasi, dan tanggal sampling.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sample Name & Sample Type -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="sample_name">
                                    Nama / Kode Sampel Pemohon <span class="text-error">*</span>
                                </label>
                                <input wire:model="sample_name" type="text" id="sample_name" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="Contoh: Air Kran Ruang Rawat Inap / Air Baku Sumur">
                                @error('sample_name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold">Tipe / Matriks Sampel</label>
                                <select wire:model="sample_type_id" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                    <option value="">-- Pilih Matriks Sampel (Opsional) --</option>
                                    @foreach($sampleTypes as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }} (Min. {{ $st->min_volume }} ml/gram)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Location and Date -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-md">
                            <div class="sm:col-span-2 space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="samp_loc">
                                    Lokasi / Titik Pengambilan Sampel <span class="text-error">*</span>
                                </label>
                                <input wire:model="sampling_location" type="text" id="samp_loc" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="Contoh: Desa Suli, Kec. Salahutu, Maluku Tengah (Titik Koordinat)">
                                @error('sampling_location') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold" for="samp_date">
                                    Tanggal Sampling <span class="text-error">*</span>
                                </label>
                                <input wire:model="sampling_date" type="date" id="samp_date" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                                @error('sampling_date') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Quantity and Notes -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-md">
                            <div class="space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold">Jumlah Wadah / Volume</label>
                                <div class="flex items-center gap-2">
                                    <input wire:model="sample_quantity" type="number" step="0.5" min="1" class="w-24 px-3 py-2 rounded-xl bg-surface border border-border-hairline text-sm">
                                    <input wire:model="sample_unit" type="text" class="flex-1 px-3 py-2 rounded-xl bg-surface border border-border-hairline text-sm" placeholder="botol / liter / sampel">
                                </div>
                            </div>

                            <div class="sm:col-span-2 space-y-1">
                                <label class="block font-label-md text-sm text-slate-body font-semibold">Catatan Kondisi Fisik Sampel</label>
                                <input wire:model="sample_notes" type="text" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline focus:outline-none focus:ring-2 focus:ring-primary text-sm" placeholder="Contoh: Wadah tertutup rapat, kondisi dingin dalam coolbox 4°C">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- STEP 4: PENYERAHAN & PERSETUJUAN -->
                @if($currentStep === 4)
                    <div class="bg-surface-container-lowest rounded-2xl p-space-lg lg:p-space-xl shadow-sm border border-border-hairline space-y-space-md">
                        <div class="flex items-center justify-between pb-space-sm border-b border-border-hairline">
                            <div class="flex items-center gap-space-sm">
                                <div class="w-8 h-8 rounded-lg bg-teal-surface text-primary flex items-center justify-center font-bold">
                                    4
                                </div>
                                <div>
                                    <h2 class="font-headline-md text-headline-md text-slate-body font-bold">Metode Penyerahan &amp; Pernyataan Sah</h2>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tentukan metode serah terima sampel dan konfirmasi keabsahan permohonan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Method Options -->
                        <div class="space-y-space-xs">
                            <label class="block font-label-md text-sm text-slate-body font-semibold">Metode Penyerahan Sampel Fisik <span class="text-error">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                                <label class="p-space-md rounded-2xl border cursor-pointer transition-all {{ $delivery_method === 'direct' ? 'bg-teal-surface/50 border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                                    <div class="flex items-start gap-3">
                                        <input wire:model.live="delivery_method" type="radio" value="direct" class="accent-primary w-5 h-5 mt-0.5">
                                        <div>
                                            <div class="font-label-md text-sm text-slate-body font-bold">Diantar Langsung ke Loket Balai</div>
                                            <p class="text-xs text-slate-subtle mt-1 leading-relaxed">
                                                Penyerahan langsung ke loket penerimaan sampel Balai Labkesmas Ambon di Jl. Dr. Kayadoe, Kudamati.
                                            </p>
                                            <span class="inline-block mt-2 text-[11px] font-semibold text-verified-green bg-verified-tint px-2 py-0.5 rounded">
                                                Disarankan untuk sampel berwadah steril
                                            </span>
                                        </div>
                                    </div>
                                </label>

                                <label class="p-space-md rounded-2xl border cursor-pointer transition-all {{ $delivery_method === 'courier' ? 'bg-teal-surface/50 border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                                    <div class="flex items-start gap-3">
                                        <input wire:model.live="delivery_method" type="radio" value="courier" class="accent-primary w-5 h-5 mt-0.5">
                                        <div>
                                            <div class="font-label-md text-sm text-slate-body font-bold">Kirim via Ekspedisi / Kurir Rantai Dingin</div>
                                            <p class="text-xs text-slate-subtle mt-1 leading-relaxed">
                                                Khusus pengiriman dari luar Pulau Ambon / Kabupaten lain. Pastikan suhu coolbox terjaga &le; 6°C dengan ice gel.
                                            </p>
                                            <span class="inline-block mt-2 text-[11px] font-semibold text-pending-amber bg-pending-tint px-2 py-0.5 rounded">
                                                Wajib sertakan resi pengiriman
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Purpose Input -->
                        <div class="space-y-1 pt-space-xs">
                            <label class="block font-label-md text-sm text-slate-body font-semibold">Tujuan / Keperluan Pengujian</label>
                            <input wire:model="purpose" type="text" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm" placeholder="Contoh: Pemenuhan Standar Izin Operasional Klinik / Uji Mandiri Kualitas Air Minum">
                        </div>

                        <!-- Agreement Checkbox -->
                        <div class="pt-space-sm p-space-md rounded-xl bg-teal-surface/40 border border-teal-muted space-y-2">
                            <label class="flex items-start gap-space-sm cursor-pointer select-none">
                                <input wire:model="agree_terms" type="checkbox" class="w-5 h-5 rounded accent-primary text-primary mt-0.5">
                                <span class="text-xs text-slate-body leading-relaxed">
                                    Dengan ini saya menyatakan bahwa data yang saya masukkan adalah benar dan sampel yang diserahkan diambil sesuai dengan prosedur baku yang berlaku. Saya memahami bahwa dokumen LHU sah hanya dapat diunduh melalui pencocokan nomor telepon/WhatsApp yang terdaftar pada formulir ini.
                                </span>
                            </label>
                            @error('agree_terms') <span class="block text-xs text-error font-medium pl-8">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <!-- NAVIGATION BUTTONS -->
                <div class="flex items-center justify-between pt-space-sm">
                    @if($currentStep > 1)
                        <button wire:click="prevStep" type="button" class="px-space-lg py-2.5 rounded-xl border border-border-hairline bg-surface-container-lowest text-slate-body text-xs font-bold hover:bg-surface-container transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                            <span>Langkah Sebelumnya</span>
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < 4)
                        <button wire:click="nextStep" type="button" class="px-space-xl py-2.5 rounded-xl bg-primary-container text-on-primary text-xs font-bold hover:bg-teal-hover transition-colors shadow-sm flex items-center gap-1.5 cursor-pointer">
                            <span>Lanjutkan</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </button>
                    @elseif($currentStep === 4)
                        <button wire:click="submitRegistration" type="button" class="px-space-xl py-3 rounded-xl bg-verified-green text-on-primary text-xs font-bold hover:bg-green-700 transition-colors shadow-md flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            <span>Kirim Pendaftaran FPPS</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- RIGHT 4 COLS: STICKY LIVE SUMMARY CARD -->
            <div class="lg:col-span-4 sticky top-28 space-y-space-md">
                <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-sm border border-border-hairline space-y-space-md">
                    <div class="flex items-center justify-between pb-space-xs border-b border-border-hairline">
                        <span class="font-label-md text-sm text-slate-body font-bold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-primary">receipt</span>
                            Ringkasan Permohonan
                        </span>
                        <span class="text-xs text-slate-subtle">Tahap {{ $currentStep }}/4</span>
                    </div>

                    <!-- Category & Parameters Summary -->
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-border-hairline/40">
                            <span class="text-slate-subtle">Kelompok Uji:</span>
                            <span class="font-semibold text-slate-body text-right">{{ $currentGroup->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border-hairline/40">
                            <span class="text-slate-subtle">Parameter Terpilih:</span>
                            <span class="font-semibold text-primary">{{ count($selected_parameters) }} Parameter</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border-hairline/40">
                            <span class="text-slate-subtle">Pemohon:</span>
                            <span class="font-semibold text-slate-body text-right truncate max-w-[150px]">{{ $customer_name ?: 'Belum diisi' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-border-hairline/40">
                            <span class="text-slate-subtle">No. WhatsApp:</span>
                            <span class="font-mono text-slate-body font-semibold">{{ $customer_phone ?: '-' }}</span>
                        </div>
                    </div>

                    <!-- Selected parameters mini list -->
                    @if($selectedParamModels->isNotEmpty())
                        <div class="space-y-1">
                            <span class="text-[11px] font-bold text-slate-subtle uppercase tracking-wider">Rincian Tarif:</span>
                            <div class="max-h-40 overflow-y-auto space-y-1 no-scrollbar">
                                @foreach($selectedParamModels as $sp)
                                    <div class="flex justify-between text-[11px] text-on-surface-variant">
                                        <span class="truncate max-w-[150px]">{{ $sp->name }}</span>
                                        <span class="font-mono">Rp {{ number_format((float)($sp->tariffs->first()?->amount ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Total Live Estimation -->
                    <div class="pt-space-sm border-t border-border-hairline">
                        <span class="text-[11px] text-slate-subtle uppercase font-bold tracking-wider">Estimasi Biaya PNBP:</span>
                        <div class="text-2xl font-bold font-mono text-primary mt-0.5">
                            Rp {{ number_format($totalEstimatedCost, 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-slate-subtle mt-1">Sesuai Peraturan Pemerintah RI No. 64/2019 tentang Tarif PNBP Kemenkes.</p>
                    </div>
                </div>

                <!-- Assistance Card -->
                <div class="bg-teal-surface/60 p-space-md rounded-2xl border border-teal-muted flex items-start gap-3">
                    <span class="material-symbols-outlined text-[22px] text-teal-deep shrink-0 mt-0.5">security</span>
                    <p class="text-xs text-teal-deep leading-relaxed">
                        Data pemohon dan nomor WhatsApp terdaftar dilindungi sistem enkripsi SHA-256 untuk memastikan kerahasiaan sertifikat hasil uji.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- STEP 5: REGISTRATION SUCCESS SCREEN -->
        <div class="max-w-3xl mx-auto bg-surface-container-lowest rounded-3xl p-space-xl shadow-lg border border-border-hairline text-center space-y-space-lg">
            <div class="w-20 h-20 rounded-full bg-verified-tint text-verified-green mx-auto flex items-center justify-center shadow-md">
                <span class="material-symbols-outlined text-[48px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>

            <div class="space-y-space-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-verified-green bg-verified-tint px-3 py-1 rounded-full">
                    Pendaftaran Berhasil Diterbitkan
                </span>
                <h2 class="font-headline-xl text-headline-xl text-slate-body font-bold">Permohonan Pengujian Terdaftar</h2>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto">
                    Formulir Permintaan Pemeriksaan Sampel (FPPS) Anda telah berhasil tercatat dalam sistem informasi laboratorium Balai Labkesmas Ambon.
                </p>
            </div>

            <!-- FPPS Number Hero Card -->
            <div class="bg-teal-surface p-space-lg rounded-2xl border border-teal-muted max-w-lg mx-auto space-y-2">
                <span class="text-xs text-slate-subtle uppercase font-bold tracking-wider">Nomor Registrasi FPPS Resmi:</span>
                <div class="text-3xl font-extrabold font-mono text-primary tracking-wider selection:bg-teal-muted">
                    {{ $createdFppsNumber }}
                </div>
                <div class="text-xs text-slate-subtle pt-1">
                    Total Estimasi Biaya PNBP: <span class="font-mono font-bold text-slate-body text-sm">Rp {{ number_format($createdTotalAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Instructions Bento -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md text-left max-w-xl mx-auto">
                <div class="p-space-md rounded-2xl bg-surface-container-low border border-border-hairline">
                    <div class="flex items-center gap-2 mb-1 text-primary font-bold text-xs uppercase">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        Instruksi Pembayaran
                    </div>
                    <p class="text-xs text-slate-body leading-relaxed">
                        Pembayaran dapat dilakukan melalui loket kasir Balai Labkesmas Ambon atau transfer ke rekening PNBP Kemenkes RI setelah verifikasi fisik sampel.
                    </p>
                </div>
                <div class="p-space-md rounded-2xl bg-surface-container-low border border-border-hairline">
                    <div class="flex items-center gap-2 mb-1 text-primary font-bold text-xs uppercase">
                        <span class="material-symbols-outlined text-[18px]">inventory</span>
                        Penyerahan Sampel
                    </div>
                    <p class="text-xs text-slate-body leading-relaxed">
                        Tunjukkan nomor FPPS ini kepada petugas loket penerimaan sampel bersama sampel fisik berwadah steril untuk verifikasi penerimaan.
                    </p>
                </div>
            </div>

            <!-- Action CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-space-sm pt-space-md">
                <button onclick="window.print()" type="button" class="w-full sm:w-auto px-space-xl py-3 rounded-xl border border-border-hairline text-slate-body font-bold text-xs hover:bg-surface-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    <span>Cetak Lembar FPPS</span>
                </button>
                <a href="{{ route('lacak', ['fpps' => $createdFppsNumber]) }}" class="w-full sm:w-auto px-space-xl py-3 rounded-xl bg-primary-container text-on-primary font-bold text-xs hover:bg-teal-hover transition-colors shadow-md flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">radar</span>
                    <span>Lacak Progres Sampel Ini</span>
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-space-lg py-3 rounded-xl text-slate-subtle hover:text-primary font-semibold text-xs transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    @endif
</div>