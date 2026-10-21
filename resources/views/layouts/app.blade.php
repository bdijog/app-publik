<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-surface">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Balai Labkesmas Ambon - Kementerian Kesehatan RI' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,500;0,6..72,600;1,6..72,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/logo-labkesmas-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-body-md text-on-surface antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
    <!-- STICKY NAVBAR -->
    <header class="fixed top-0 w-full z-50 bg-surface-container-lowest/95 backdrop-blur-xl border-b border-border-hairline shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="h-20 max-w-7xl mx-auto px-gutter flex items-center justify-between gap-gutter">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-space-sm group">
                <img src="{{ asset('images/logo-labkesmas-ambon.png') }}" 
                     alt="Balai Labkesmas Ambon - Kementerian Kesehatan RI" 
                     class="h-9 sm:h-10 w-auto object-contain group-hover:opacity-90 transition-opacity">
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" 
                   class="px-space-md py-2 rounded-xl font-label-md text-label-md transition-all {{ request()->routeIs('home') ? 'bg-teal-surface text-primary font-bold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                    Beranda
                </a>
                <a href="{{ route('katalog') }}" 
                   class="px-space-md py-2 rounded-xl font-label-md text-label-md transition-all {{ request()->routeIs('katalog') ? 'bg-teal-surface text-primary font-bold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                    Katalog &amp; Tarif
                </a>
                <a href="{{ route('pendaftaran') }}" 
                   class="px-space-md py-2 rounded-xl font-label-md text-label-md transition-all {{ request()->routeIs('pendaftaran') ? 'bg-teal-surface text-primary font-bold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                    Pendaftaran FPPS
                </a>
                <a href="{{ route('lacak') }}" 
                   class="px-space-md py-2 rounded-xl font-label-md text-label-md transition-all {{ request()->routeIs('lacak') ? 'bg-teal-surface text-primary font-bold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                    Lacak Sampel
                </a>
                <a href="{{ route('verifikasi-lhu') }}" 
                   class="px-space-md py-2 rounded-xl font-label-md text-label-md transition-all {{ request()->routeIs('verifikasi-lhu') ? 'bg-teal-surface text-primary font-bold shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                    Verifikasi LHU
                </a>
            </nav>

            <!-- Actions Desktop -->
            <div class="hidden sm:flex items-center gap-space-xs">
                <a href="{{ route('lacak') }}" class="inline-flex items-center gap-1.5 bg-primary-container text-on-primary hover:bg-teal-hover px-space-md py-2 rounded-xl font-label-md text-label-md transition-colors shadow-xs">
                    <span class="material-symbols-outlined text-[18px]">radar</span>
                    <span>Lacak FPPS</span>
                </a>

                @if(auth('customer')->check())
                    <!-- Customer Logged In Menu -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 px-space-sm py-1.5 rounded-xl bg-teal-surface hover:bg-teal-muted/40 transition-colors border border-teal-muted text-primary">
                            <div class="w-7 h-7 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr(auth('customer')->user()->name, 0, 1)) }}
                            </div>
                            <span class="font-label-md text-label-sm font-semibold max-w-[120px] truncate">{{ auth('customer')->user()->name }}</span>
                            <span class="material-symbols-outlined text-[18px]">expand_more</span>
                        </button>

                        <div x-show="open" x-cloak 
                             x-transition:enter="transition ease-out duration-100" 
                             x-transition:enter-start="transform opacity-0 scale-95" 
                             x-transition:enter-end="transform opacity-100 scale-100" 
                             x-transition:leave="transition ease-in duration-75" 
                             x-transition:leave-start="transform opacity-100 scale-100" 
                             x-transition:leave-end="transform opacity-0 scale-95" 
                             class="absolute right-0 mt-2 w-56 bg-surface-container-lowest rounded-xl shadow-lg border border-border-hairline py-1 z-50">
                            <div class="px-4 py-2 border-b border-border-hairline">
                                <p class="text-xs text-slate-subtle">Masuk sebagai:</p>
                                <p class="text-sm font-semibold text-slate-body truncate">{{ auth('customer')->user()->name }}</p>
                                <p class="text-xs text-slate-subtle truncate">{{ auth('customer')->user()->email }}</p>
                            </div>
                            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-body hover:bg-surface-container-low transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-primary">dashboard</span>
                                <span>Dashboard Pengujian</span>
                            </a>
                            <a href="{{ route('pendaftaran') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-body hover:bg-surface-container-low transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-primary">add_task</span>
                                <span>Daftar Uji Baru</span>
                            </a>
                            <div class="border-t border-border-hairline my-1"></div>
                            <form action="{{ route('customer.logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-error hover:bg-rejection-tint transition-colors text-left">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    <span>Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Actions -->
                    <a href="{{ route('customer.login') }}" class="px-space-md py-2 rounded-xl font-label-md text-label-md text-primary hover:bg-teal-surface transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('customer.register') }}" class="px-space-md py-2 rounded-xl font-label-md text-label-md bg-surface-container text-on-surface hover:bg-surface-container-high transition-colors">
                        Daftar Akun
                    </a>
                @endif
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    type="button" 
                    class="lg:hidden p-2 rounded-xl text-on-surface hover:bg-surface-container transition-colors"
                    aria-label="Buka Menu">
                <span class="material-symbols-outlined text-[26px]" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t border-border-hairline bg-surface-container-lowest px-gutter py-space-md space-y-space-xs shadow-xl">
            <a href="{{ route('home') }}" class="block px-space-md py-2.5 rounded-xl font-label-md text-label-md {{ request()->routeIs('home') ? 'bg-teal-surface text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                Beranda
            </a>
            <a href="{{ route('katalog') }}" class="block px-space-md py-2.5 rounded-xl font-label-md text-label-md {{ request()->routeIs('katalog') ? 'bg-teal-surface text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                Katalog &amp; Tarif
            </a>
            <a href="{{ route('pendaftaran') }}" class="block px-space-md py-2.5 rounded-xl font-label-md text-label-md {{ request()->routeIs('pendaftaran') ? 'bg-teal-surface text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                Pendaftaran FPPS
            </a>
            <a href="{{ route('lacak') }}" class="block px-space-md py-2.5 rounded-xl font-label-md text-label-md {{ request()->routeIs('lacak') ? 'bg-teal-surface text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                Lacak Sampel
            </a>
            <a href="{{ route('verifikasi-lhu') }}" class="block px-space-md py-2.5 rounded-xl font-label-md text-label-md {{ request()->routeIs('verifikasi-lhu') ? 'bg-teal-surface text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                Verifikasi LHU
            </a>

            <div class="pt-space-sm border-t border-border-hairline space-y-space-xs">
                @if(auth('customer')->check())
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center justify-between px-space-md py-2.5 rounded-xl bg-teal-surface text-primary font-bold">
                        <span>Dashboard Pelanggan ({{ auth('customer')->user()->name }})</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <form action="{{ route('customer.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-space-md py-2.5 rounded-xl text-error font-label-md text-label-md hover:bg-rejection-tint">
                            Keluar Akun
                        </button>
                    </form>
                @else
                    <a href="{{ route('customer.login') }}" class="block w-full text-center py-2.5 rounded-xl font-label-md text-label-md border border-border-hairline text-primary">
                        Masuk Akun
                    </a>
                    <a href="{{ route('customer.register') }}" class="block w-full text-center py-2.5 rounded-xl font-label-md text-label-md bg-primary-container text-on-primary">
                        Daftar Akun Pelanggan
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- MAIN BODY -->
    <main class="flex-1 w-full pt-20">
        {{ $slot }}
    </main>

    <!-- OFFICIAL FOOTER -->
    <footer class="w-full bg-slate-body text-surface pt-space-xl pb-space-lg border-t border-slate-subtle/20 mt-space-xl">
        <div class="max-w-7xl mx-auto px-gutter grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-gutter-lg pb-space-xl border-b border-slate-subtle/20">
            <!-- Brand & Gov Info (5 cols) -->
            <div class="lg:col-span-5 space-y-space-md">
                <div class="flex items-center gap-space-sm">
                    <img src="{{ asset('images/logo-labkesmas-ambon-dark.png') }}" 
                         alt="Balai Labkesmas Ambon - Kementerian Kesehatan RI" 
                         class="h-11 w-auto object-contain">
                </div>
                <p class="font-body-sm text-body-sm text-slate-subtle leading-relaxed max-w-md">
                    Unit Pelaksana Teknis di lingkungan Kementerian Kesehatan yang menyelenggarakan pelayanan laboratorium kesehatan masyarakat, pengujian kualitas lingkungan, vektor penyakit, makanan &amp; minuman, serta kalibrasi peralatan kesehatan di Maluku.
                </p>
                <div class="flex flex-wrap items-center gap-space-xs text-xs">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-teal-deep text-teal-muted font-semibold">
                        <span class="material-symbols-outlined text-[14px]">verified</span>
                        ISO/IEC 17025:2017 (LP-892-IDN)
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high/10 text-surface font-semibold">
                        Kemenkes RI
                    </span>
                </div>
            </div>

            <!-- Layanan & Akses Cepat (3 cols) -->
            <div class="lg:col-span-3 space-y-space-sm">
                <h3 class="font-label-md text-label-md text-surface font-bold uppercase tracking-wider">Akses Pelayanan</h3>
                <ul class="space-y-2 text-sm text-slate-subtle">
                    <li><a href="{{ route('katalog') }}" class="hover:text-teal-muted transition-colors">Katalog 17 Kelompok Uji</a></li>
                    <li><a href="{{ route('pendaftaran') }}" class="hover:text-teal-muted transition-colors">Pendaftaran FPPS Online</a></li>
                    <li><a href="{{ route('lacak') }}" class="hover:text-teal-muted transition-colors">Lacak Progres Sampel</a></li>
                    <li><a href="{{ route('verifikasi-lhu') }}" class="hover:text-teal-muted transition-colors">Gerbang Verifikasi LHU</a></li>
                    <li><a href="{{ route('customer.dashboard') }}" class="hover:text-teal-muted transition-colors">Portal Pelanggan &amp; Faskes</a></li>
                </ul>
            </div>

            <!-- Alamat & Kontak Loket (4 cols) -->
            <div class="lg:col-span-4 space-y-space-sm">
                <h3 class="font-label-md text-label-md text-surface font-bold uppercase tracking-wider">Loket &amp; Kontak Pelayanan</h3>
                <div class="space-y-2.5 text-sm text-slate-subtle">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[18px] text-teal-muted shrink-0 mt-0.5">location_on</span>
                        <span>Jl. Dr. Kayadoe, Kudamati, Kec. Nusaniwe, Kota Ambon, Maluku 97116</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-teal-muted shrink-0">schedule</span>
                        <span>Senin - Kamis: 08.00 - 16.00 WIT | Jumat: 08.00 - 16.30 WIT</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-teal-muted shrink-0">chat</span>
                        <span>WhatsApp Helpdesk: 0812-4040-5678</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-teal-muted shrink-0">mail</span>
                        <span>labkesmas.ambon@kemkes.go.id</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="max-w-7xl mx-auto px-gutter pt-space-md flex flex-col sm:flex-row items-center justify-between gap-space-sm text-xs text-slate-subtle">
            <p>&copy; 2026 Balai Laboratorium Kesehatan Masyarakat Ambon. Kementerian Kesehatan RI.</p>
            <p class="flex items-center gap-2">
                <span>Hak Cipta Dilindungi Undang-Undang</span>
                <span>•</span>
                <span>Sistem Informasi Terpadu Pelayanan Publik</span>
            </p>
        </div>
    </footer>
</body>
</html>
