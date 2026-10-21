<div class="relative w-full min-h-[calc(100vh-5rem)] flex items-center justify-center py-space-xl px-gutter bg-gradient-to-b from-teal-surface/60 via-surface to-surface-container-low/50">
    <div class="w-full max-w-2xl bg-surface-container-lowest rounded-3xl p-space-lg sm:p-space-xl shadow-lg border border-border-hairline relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary via-teal-deep to-sky-progress"></div>

        <div class="text-center max-w-md mx-auto space-y-2 mb-space-lg">
            <a href="{{ route('home') }}" class="inline-block mx-auto mb-1 group">
                <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-10 mx-auto object-contain group-hover:opacity-90 transition-opacity">
            </a>
            <h1 class="font-headline-xl text-2xl font-bold text-slate-body">Daftar Akun Pelanggan</h1>
            <p class="text-xs text-slate-subtle">
                Buat akun mandiri untuk mempermudah pendaftaran pengujian sampel rutin, pemantauan riwayat FPPS, dan akses arsip LHU resmi.
            </p>
        </div>

        <form wire:submit="register" class="space-y-space-md">
            <!-- Customer Type Toggle -->
            <div class="space-y-1">
                <label class="block font-label-md text-xs text-slate-body font-bold">Kategori Pemohon <span class="text-error">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-xs">
                    <label class="p-space-xs px-space-sm rounded-xl border cursor-pointer transition-all flex items-center gap-2 {{ $customer_type === 'individual' ? 'bg-teal-surface border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                        <input wire:model.live="customer_type" type="radio" value="individual" class="accent-primary w-4 h-4">
                        <span class="text-xs font-semibold text-slate-body">Perorangan</span>
                    </label>
                    <label class="p-space-xs px-space-sm rounded-xl border cursor-pointer transition-all flex items-center gap-2 {{ $customer_type === 'organization' ? 'bg-teal-surface border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                        <input wire:model.live="customer_type" type="radio" value="organization" class="accent-primary w-4 h-4">
                        <span class="text-xs font-semibold text-slate-body">Instansi / Swasta</span>
                    </label>
                    <label class="p-space-xs px-space-sm rounded-xl border cursor-pointer transition-all flex items-center gap-2 {{ $customer_type === 'health_facility' ? 'bg-teal-surface border-primary ring-1 ring-primary' : 'bg-surface-container-low border-border-hairline hover:bg-surface-container' }}">
                        <input wire:model.live="customer_type" type="radio" value="health_facility" class="accent-primary w-4 h-4">
                        <span class="text-xs font-semibold text-slate-body">Faskes / RSUD</span>
                    </label>
                </div>
            </div>

            <!-- Organization Name if not individual -->
            @if($customer_type !== 'individual')
                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="org_name">
                        Nama Instansi / Rumah Sakit / Puskesmas / Perusahaan <span class="text-error">*</span>
                    </label>
                    <input wire:model="organization_name" type="text" id="org_name" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="Contoh: RSUD dr. M. Haulussy Ambon / PT Maluku Prima">
                    @error('organization_name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Full Name & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="name">
                        Nama Lengkap Penanggung Jawab <span class="text-error">*</span>
                    </label>
                    <input wire:model="name" type="text" id="name" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="Contoh: dr. Johanis Pattikawa">
                    @error('name') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="phone">
                        Nomor WhatsApp / Telepon <span class="text-error">*</span>
                    </label>
                    <input wire:model="phone" type="tel" id="phone" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm font-mono focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="Contoh: 081234567890">
                    @error('phone') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Email & Address -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="email">
                        Alamat Email Resmi <span class="text-error">*</span>
                    </label>
                    <input wire:model="email" type="email" id="email" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="nama@instansi.go.id">
                    @error('email') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="address">
                        Alamat Lengkap Domisili / Kantor <span class="text-error">*</span>
                    </label>
                    <input wire:model="address" type="text" id="address" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="Jl. Pattimura No. 45, Kota Ambon">
                    @error('address') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Password & Confirmation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="password">
                        Kata Sandi <span class="text-error">*</span>
                    </label>
                    <input wire:model="password" type="password" id="password" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="Minimal 8 karakter">
                    @error('password') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block font-label-md text-xs text-slate-body font-bold" for="password_confirmation">
                        Konfirmasi Kata Sandi <span class="text-error">*</span>
                    </label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" class="w-full px-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" placeholder="Ulangi kata sandi">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 rounded-xl bg-primary-container text-on-primary font-bold text-sm hover:bg-teal-hover transition-colors shadow-md flex items-center justify-center gap-2 cursor-pointer pt-space-xs">
                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                <span>Daftar Akun Pelanggan</span>
            </button>
        </form>

        <div class="mt-space-lg pt-space-md border-t border-border-hairline text-center">
            <span class="text-xs text-slate-subtle">
                Sudah memiliki akun pelanggan? 
                <a href="{{ route('customer.login') }}" class="text-primary hover:underline font-bold">
                    Masuk ke Portal
                </a>
            </span>
        </div>
    </div>
</div>