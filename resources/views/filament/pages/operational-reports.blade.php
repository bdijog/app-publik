<x-filament-panels::page>
    @php
        $summary = $this->getSummaryStats();
        $serviceStats = $this->getServiceGroupStats();
        $sla = $this->getSlaComplianceStats();
        $sampleStats = $this->getSampleReceiptStats();
    @endphp

    {{-- Filter Rentang Tanggal --}}
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">Filter Rentang Waktu Rekapitulasi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pilih rentang tanggal permohonan dan aktivitas operasional laboratorium.</p>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Mulai Tanggal</label>
                    <input type="date" wire:model.live="startDate" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                    <input type="date" wire:model.live="endDate" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Indikator Utama --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Permohonan</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($summary['total_requests']) }}</p>
            <span class="mt-2 inline-flex items-center text-xs text-primary-600 dark:text-primary-400 font-medium">Berkas FPPS Terdaftar</span>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total PNBP Masuk</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
            <span class="mt-2 inline-flex items-center text-xs text-gray-500 dark:text-gray-400 font-medium">Berdasarkan Pembayaran Lunas</span>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Sampel Uji Diterima</p>
            <p class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($summary['total_samples']) }}</p>
            <span class="mt-2 inline-flex items-center text-xs text-gray-500 dark:text-gray-400 font-medium">Sampel Fisik Masuk Lab</span>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">LHU Resmi Terbit</p>
            <p class="mt-2 text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($summary['total_reports_issued']) }}</p>
            <span class="mt-2 inline-flex items-center text-xs text-gray-500 dark:text-gray-400 font-medium">Dokumen Berstatus Final</span>
        </div>
    </div>

    {{-- Bagian 1: Rekapitulasi Berdasarkan Kelompok Layanan --}}
    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rekapitulasi Permohonan per Kelompok Layanan (Katalog Layanan Labkesmas)</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Distribusi beban uji dan permohonan pelanggan berdasarkan bidang pengujian.</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                {{ $serviceStats->count() }} Kelompok Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Kelompok Layanan</th>
                        <th class="px-4 py-3">Total Paket Layanan</th>
                        <th class="px-4 py-3 text-right">Permohonan Uji</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($serviceStats as $group)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 font-mono font-medium text-primary-600 dark:text-primary-400">{{ $group->code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $group->name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $group->servicePackages->count() }} paket</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $group->test_requests_count > 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $group->test_requests_count }} berkas
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data kelompok layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bagian 2: Kepatuhan Standar Waktu Pengujian (SLA) & Evaluasi Sampel --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Kepatuhan SLA --}}
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kepatuhan Standar Waktu Pengujian (SLA)</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Tingkat ketepatan waktu penyelesaian pengujian dibandingkan target SLA estimasi.</p>

            <div class="flex items-center justify-between p-4 mb-4 rounded-xl bg-gray-50 dark:bg-gray-800">
                <div>
                    <span class="text-xs uppercase font-medium text-gray-500 dark:text-gray-400">Tingkat Kepatuhan SLA</span>
                    <div class="text-3xl font-extrabold text-primary-600 dark:text-primary-400">{{ $sla['compliance_rate'] }}%</div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Berkas dengan SLA:</span>
                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $sla['total_with_sla'] }} Berkas</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="p-3 text-center border border-emerald-200 rounded-lg bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-800">
                    <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Tepat Waktu</span>
                    <div class="text-xl font-bold text-emerald-700 dark:text-emerald-300">{{ $sla['completed_on_time'] }}</div>
                </div>
                <div class="p-3 text-center border border-blue-200 rounded-lg bg-blue-50/50 dark:bg-blue-950/20 dark:border-blue-800">
                    <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Dalam Proses</span>
                    <div class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $sla['in_progress'] }}</div>
                </div>
                <div class="p-3 text-center border border-rose-200 rounded-lg bg-rose-50/50 dark:bg-rose-950/20 dark:border-rose-800">
                    <span class="text-xs font-medium text-rose-700 dark:text-rose-400">Terlambat (Overdue)</span>
                    <div class="text-xl font-bold text-rose-700 dark:text-rose-300">{{ $sla['overdue'] }}</div>
                </div>
            </div>
        </div>

        {{-- Evaluasi Penerimaan & Penolakan Sampel --}}
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Evaluasi Verifikasi Fisik Sampel</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Pemeriksaan integritas dan kelayakan kondisi fisik sampel saat diterima laboratorium.</p>

            <div class="flex items-center justify-between p-4 mb-4 rounded-xl bg-gray-50 dark:bg-gray-800">
                <div>
                    <span class="text-xs uppercase font-medium text-gray-500 dark:text-gray-400">Total Sampel Diverifikasi</span>
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $sampleStats['total'] }}</div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Rasio Sampel Ditolak (Reject):</span>
                    <div class="text-lg font-bold text-rose-600 dark:text-rose-400">{{ $sampleStats['rejection_rate'] }}%</div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="p-3 text-center border border-emerald-200 rounded-lg bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-800">
                    <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Diterima Memenuhi Syarat</span>
                    <div class="text-xl font-bold text-emerald-700 dark:text-emerald-300">{{ $sampleStats['accepted'] }}</div>
                </div>
                <div class="p-3 text-center border border-rose-200 rounded-lg bg-rose-50/50 dark:bg-rose-950/20 dark:border-rose-800">
                    <span class="text-xs font-medium text-rose-700 dark:text-rose-400">Ditolak Tidak Layak Uji</span>
                    <div class="text-xl font-bold text-rose-700 dark:text-rose-300">{{ $sampleStats['rejected'] }}</div>
                </div>
            </div>

            @if($sampleStats['rejections_list']->count() > 0)
                <div class="mt-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Sampel Ditolak Terbaru & Justifikasi:</h4>
                    <div class="space-y-2">
                        @foreach($sampleStats['rejections_list'] as $rej)
                            <div class="p-2.5 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 text-xs">
                                <div class="flex items-center justify-between font-semibold text-gray-800 dark:text-gray-200">
                                    <span>{{ $rej->sample?->sample_code ?? 'Kode Sampel N/A' }}</span>
                                    <span class="text-gray-500">{{ $rej->received_at?->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-rose-600 dark:text-rose-400 mt-1 font-medium">Alasan: {{ $rej->rejection_reason ?? '-' }}</p>
                                <p class="text-gray-500 mt-0.5">Petugas: {{ $rej->receivedBy?->name ?? '-' }} | Tindak lanjut: {{ $rej->follow_up ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
