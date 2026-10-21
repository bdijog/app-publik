<div class="relative w-full min-h-[calc(100vh-5rem)] flex items-center justify-center py-space-xl px-gutter bg-gradient-to-b from-teal-surface/60 via-surface to-surface-container-low/50">
    <div class="w-full max-w-md bg-surface-container-lowest rounded-3xl p-space-lg sm:p-space-xl shadow-lg border border-border-hairline relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary to-sky-progress"></div>

        <div class="text-center space-y-2 mb-space-lg">
            <a href="{{ route('home') }}" class="inline-block mx-auto mb-1 group">
                <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" alt="Balai Labkesmas Ambon" class="h-10 mx-auto object-contain group-hover:opacity-90 transition-opacity">
            </a>
            <h1 class="font-headline-xl text-2xl font-bold text-slate-body">Portal Pelanggan</h1>
            <p class="text-xs text-slate-subtle">
                Masuk untuk mengakses riwayat pengujian sampel, invoice pembayaran, dan unduh dokumen LHU sah.
            </p>
        </div>

        @if($errorMessage)
            <div class="mb-space-md p-space-sm rounded-xl bg-rejection-tint text-rejection-red text-xs font-semibold flex items-center gap-2 border border-red-200">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <form wire:submit="login" class="space-y-space-md">
            <!-- Email -->
            <div class="space-y-1">
                <label class="block font-label-md text-xs text-slate-body font-bold" for="email">
                    Alamat Email Terdaftar <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-subtle text-[18px]">mail</span>
                    <input wire:model="email" 
                           id="email" 
                           type="email" 
                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" 
                           placeholder="nama@instansi.go.id">
                </div>
                @error('email') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label class="font-label-md text-xs text-slate-body font-bold" for="password">
                        Kata Sandi <span class="text-error">*</span>
                    </label>
                    <span class="text-[11px] text-slate-subtle">Minimal 8 karakter</span>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-subtle text-[18px]">key</span>
                    <input wire:model="password" 
                           id="password" 
                           type="password" 
                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-surface border border-border-hairline text-sm focus:outline-none focus:ring-2 focus:ring-primary shadow-sm" 
                           placeholder="••••••••">
                </div>
                @error('password') <span class="text-xs text-error font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Remember me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded text-primary focus:ring-primary accent-primary">
                    <span class="text-slate-body font-medium">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 rounded-xl bg-primary-container text-on-primary font-bold text-sm hover:bg-teal-hover transition-colors shadow-md flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">login</span>
                <span>Masuk ke Dashboard</span>
            </button>
        </form>

        <!-- Quick Demo Fill -->
        <div class="mt-space-md p-space-sm rounded-xl bg-teal-surface/50 border border-teal-muted flex items-center justify-between text-xs">
            <span class="text-teal-deep">Ingin mencoba akun demo pelanggan?</span>
            <button wire:click="fillDemo" type="button" class="text-primary font-bold hover:underline cursor-pointer">
                Isi Akun Demo
            </button>
        </div>

        <div class="mt-space-lg pt-space-md border-t border-border-hairline text-center">
            <span class="text-xs text-slate-subtle">
                Belum memiliki akun pelanggan? 
                <a href="{{ route('customer.register') }}" class="text-primary hover:underline font-bold">
                    Daftar Akun Baru
                </a>
            </span>
        </div>
    </div>
</div>