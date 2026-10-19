<x-filament-panels::page>
    @php
        $summary = $this->getSummaryStats();
        $serviceStats = $this->getServiceGroupStats();
        $sla = $this->getSlaComplianceStats();
        $sampleStats = $this->getSampleReceiptStats();
        $pipeline = $this->getPipelineStats();
        $channels = $this->getChannelStats();
        [$startDateCarbon, $endDateCarbon] = $this->getParsedDateRange();
    @endphp

    {{-- Bulletproof Self-Contained Styles for Executive Dashboard --}}
    <style>
        .op-dashboard-wrap {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
            color: #1f2937;
        }

        .dark .op-dashboard-wrap {
            color: #f3f4f6;
        }

        /* SVG Sizing Lock - Prevents icons from expanding */
        .op-dashboard-wrap svg {
            display: inline-block;
            vertical-align: middle;
            flex-shrink: 0;
        }

        .op-ic-xs { width: 14px; height: 14px; min-width: 14px; min-height: 14px; }
        .op-ic-sm { width: 18px; height: 18px; min-width: 18px; min-height: 18px; }
        .op-ic-md { width: 22px; height: 22px; min-width: 22px; min-height: 22px; }
        .op-ic-lg { width: 28px; height: 28px; min-width: 28px; min-height: 28px; }

        /* Print Header - Strictly hidden on screen */
        .op-print-header {
            display: none !important;
        }

        @media print {
            .op-no-print {
                display: none !important;
            }
            .op-print-header {
                display: block !important;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 2px solid #000;
                text-align: center;
                color: #000 !important;
            }
            .op-card, .op-hero-banner {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                break-inside: avoid;
            }
        }

        /* Hero Control Banner */
        .op-hero-banner {
            position: relative;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .dark .op-hero-banner {
            background: #111827;
            border-color: #1f2937;
        }

        .op-glow-teal {
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.15) 0%, rgba(13, 148, 136, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .op-header-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .op-header-title {
            font-size: 1.375rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin: 0.25rem 0 0.125rem 0;
            color: #111827;
        }

        .dark .op-header-title {
            color: #ffffff;
        }

        .op-header-subtitle {
            font-size: 0.8125rem;
            color: #6b7280;
            margin: 0;
        }

        .dark .op-header-subtitle {
            color: #9ca3af;
        }

        .op-tag-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
            background: #f0fdfa;
            color: #0f766e;
            border: 1px solid #ccfbf1;
            letter-spacing: 0.03em;
        }

        .dark .op-tag-badge {
            background: rgba(13, 148, 136, 0.15);
            color: #2dd4bf;
            border-color: rgba(13, 148, 136, 0.3);
        }

        .op-pulse-dot {
            width: 6px;
            height: 6px;
            background: #0d9488;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(13, 148, 136, 0.7);
            animation: op-pulse 2s infinite;
        }

        @keyframes op-pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(13, 148, 136, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(13, 148, 136, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(13, 148, 136, 0); }
        }

        .op-action-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .op-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.875rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 0.625rem;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .op-btn-white {
            background: #ffffff;
            color: #374151;
            border-color: #d1d5db;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .op-btn-white:hover {
            background: #f9fafb;
            color: #111827;
        }

        .dark .op-btn-white {
            background: #1f2937;
            color: #e5e7eb;
            border-color: #374151;
        }

        .dark .op-btn-white:hover {
            background: #374151;
        }

        .op-btn-teal {
            background: #f0fdfa;
            color: #0f766e;
            border-color: #ccfbf1;
        }

        .op-btn-teal:hover {
            background: #ccfbf1;
        }

        .dark .op-btn-teal {
            background: rgba(13, 148, 136, 0.2);
            color: #2dd4bf;
            border-color: rgba(13, 148, 136, 0.3);
        }

        .dark .op-btn-teal:hover {
            background: rgba(13, 148, 136, 0.3);
        }

        /* Filter Controls */
        .op-filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .dark .op-filter-row {
            border-top-color: #1f2937;
        }

        .op-presets-list {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.375rem;
        }

        .op-preset-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .op-preset-btn:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .op-preset-btn.active {
            background: #0d9488;
            color: #ffffff;
            font-weight: 700;
            border-color: #0d9488;
            box-shadow: 0 1px 3px rgba(13, 148, 136, 0.3);
        }

        .dark .op-preset-btn {
            background: #1f2937;
            border-color: #374151;
            color: #9ca3af;
        }

        .dark .op-preset-btn:hover {
            background: #374151;
            color: #f3f4f6;
        }

        .dark .op-preset-btn.active {
            background: #0d9488;
            border-color: #0d9488;
            color: #ffffff;
        }

        .op-date-box {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.625rem;
            padding: 0.375rem 0.625rem;
        }

        .dark .op-date-box {
            background: #1f2937;
            border-color: #374151;
        }

        .op-date-input {
            border: none;
            background: transparent;
            font-size: 0.75rem;
            font-weight: 600;
            color: #111827;
            padding: 0;
            outline: none;
            cursor: pointer;
        }

        .dark .op-date-input {
            color: #f9fafb;
            color-scheme: dark;
        }

        /* Metric KPI Cards Grid */
        .op-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1rem;
        }

        .op-stat-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .op-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .dark .op-stat-card {
            background: #111827;
            border-color: #1f2937;
        }

        .op-card-accent-teal { border-top: 3px solid #0d9488; }
        .op-card-accent-emerald { border-top: 3px solid #059669; }
        .op-card-accent-blue { border-top: 3px solid #2563eb; }
        .op-card-accent-purple { border-top: 3px solid #7c3aed; }

        .op-stat-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .op-stat-label {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin: 0;
        }

        .dark .op-stat-label {
            color: #9ca3af;
        }

        .op-stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin: 0.25rem 0 0 0;
            color: #111827;
        }

        .dark .op-stat-value {
            color: #ffffff;
        }

        .op-icon-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
        }

        .op-icb-teal { background: #f0fdfa; color: #0d9488; border: 1px solid #ccfbf1; }
        .op-icb-emerald { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .op-icb-blue { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .op-icb-purple { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }

        .dark .op-icb-teal { background: rgba(13, 148, 136, 0.2); color: #2dd4bf; border-color: rgba(13, 148, 136, 0.3); }
        .dark .op-icb-emerald { background: rgba(5, 150, 105, 0.2); color: #34d399; border-color: rgba(5, 150, 105, 0.3); }
        .dark .op-icb-blue { background: rgba(37, 99, 235, 0.2); color: #60a5fa; border-color: rgba(37, 99, 235, 0.3); }
        .dark .op-icb-purple { background: rgba(124, 58, 237, 0.2); color: #a78bfa; border-color: rgba(124, 58, 237, 0.3); }

        .op-stat-footer {
            margin-top: 0.875rem;
            padding-top: 0.625rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
        }

        .dark .op-stat-footer {
            border-top-color: #1f2937;
        }

        /* 2-Column Row Layout */
        .op-two-col-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.25rem;
        }

        @media (max-width: 1024px) {
            .op-two-col-grid {
                grid-template-columns: 1fr;
            }
        }

        .op-equal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        @media (max-width: 1024px) {
            .op-equal-grid {
                grid-template-columns: 1fr;
            }
        }

        .op-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .dark .op-card {
            background: #111827;
            border-color: #1f2937;
        }

        .op-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .op-card-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .dark .op-card-title {
            color: #ffffff;
        }

        .op-card-desc {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        .dark .op-card-desc {
            color: #9ca3af;
        }

        /* Pipeline Steps */
        .op-pipeline-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .op-pipeline-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.875rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dark .op-pipeline-item {
            background: #1f2937;
            border-color: #374151;
        }

        .op-pipe-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: #6b7280;
            margin: 0 0 0.375rem 0;
        }

        .dark .op-pipe-title {
            color: #9ca3af;
        }

        .op-pipe-count {
            font-size: 1.375rem;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        .dark .op-pipe-count {
            color: #ffffff;
        }

        /* Channel Progress */
        .op-channel-item {
            margin-bottom: 1rem;
        }

        .op-channel-item:last-child {
            margin-bottom: 0;
        }

        .op-channel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.375rem;
        }

        .op-progress-bar-bg {
            width: 100%;
            height: 8px;
            background: #f3f4f6;
            border-radius: 9999px;
            overflow: hidden;
        }

        .dark .op-progress-bar-bg {
            background: #374151;
        }

        .op-progress-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.3s ease;
        }

        /* Modern Table */
        .op-table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
        }

        .dark .op-table-wrap {
            border-color: #1f2937;
        }

        .op-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            text-align: left;
        }

        .op-table th {
            background: #f9fafb;
            color: #4b5563;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .dark .op-table th {
            background: #1f2937;
            color: #9ca3af;
            border-bottom-color: #374151;
        }

        .op-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        .dark .op-table td {
            border-bottom-color: #1f2937;
            color: #d1d5db;
        }

        .op-table tr:last-child td {
            border-bottom: none;
        }

        .op-table tr:hover td {
            background: #f9fafb;
        }

        .dark .op-table tr:hover td {
            background: rgba(31, 41, 55, 0.5);
        }

        .op-badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 600;
        }

        /* SLA & Sample Highlight Box */
        .op-score-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .op-score-box-teal {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
        }

        .dark .op-score-box-teal {
            background: rgba(13, 148, 136, 0.15);
            border-color: rgba(13, 148, 136, 0.3);
        }

        .op-score-box-blue {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .dark .op-score-box-blue {
            background: rgba(37, 99, 235, 0.15);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .op-score-number {
            font-size: 2.25rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }

        .op-subgrid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .op-subgrid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .op-mini-box {
            text-align: center;
            padding: 0.75rem 0.5rem;
            border-radius: 0.625rem;
            border: 1px solid transparent;
        }

        .op-rejection-card {
            background: rgba(254, 242, 242, 0.6);
            border: 1px solid #fecaca;
            border-radius: 0.625rem;
            padding: 0.625rem 0.75rem;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .dark .op-rejection-card {
            background: rgba(159, 18, 57, 0.15);
            border-color: rgba(244, 63, 94, 0.25);
        }
    </style>

    {{-- Official Print Letterhead (Only visible during window.print()) --}}
    <div class="op-print-header">
        <h2 style="font-size: 14pt; font-weight: 700; text-transform: uppercase; margin: 0;">Kementerian Kesehatan Republik Indonesia</h2>
        <h1 style="font-size: 16pt; font-weight: 900; text-transform: uppercase; margin: 4px 0;">Balai Laboratorium Kesehatan Masyarakat Ambon</h1>
        <p style="font-size: 9pt; color: #444; margin: 0 0 10px 0;">Jl. dr. Kayadoe, Kudamati, Kec. Nusaniwe, Kota Ambon, Maluku | Akreditasi KAN ISO/IEC 17025:2017</p>
        <div style="border-top: 1px solid #000; padding-top: 8px; margin-top: 6px;">
            <h3 style="font-size: 11pt; font-weight: 700; text-transform: uppercase; margin: 0;">Laporan Eksekutif Rekapitulasi Operasional Layanan Laboratorium</h3>
            <p style="font-size: 9pt; margin: 4px 0 0 0;">
                Periode: <strong>{{ $startDateCarbon->translatedFormat('d F Y') }}</strong> s/d <strong>{{ $endDateCarbon->translatedFormat('d F Y') }}</strong>
                | Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIT
            </p>
        </div>
    </div>

    <div class="op-dashboard-wrap">

        {{-- Executive Hero Banner & Filter Controls --}}
        <div class="op-hero-banner op-no-print">
            <div class="op-glow-teal"></div>

            {{-- Top Header Row --}}
            <div class="op-header-row">
                <div>
                    <div class="op-tag-badge">
                        <span class="op-pulse-dot"></span>
                        <span>Balai Labkesmas Ambon • Real-Time Analytics</span>
                    </div>
                    <h2 class="op-header-title">Rekapitulasi Operasional & Kinerja Layanan</h2>
                    <p class="op-header-subtitle">Monitoring berkas permohonan, penyerapan PNBP, kepatuhan SLA SPM Kemenkes, serta penjaminan mutu sampel lab.</p>
                </div>

                <div class="op-action-group">
                    <button type="button" onclick="window.print()" class="op-btn op-btn-white" title="Cetak atau simpan sebagai dokumen PDF">
                        <svg class="op-ic-sm text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak / PDF</span>
                    </button>

                    <button type="button" wire:click="setPreset('this_month')" class="op-btn op-btn-teal" title="Kembali ke rentang bulan berjalan">
                        <svg class="op-ic-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Reset Bulan Ini</span>
                    </button>
                </div>
            </div>

            {{-- Date Presets & Picker Row --}}
            <div class="op-filter-row">
                <div class="op-presets-list">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-right: 0.25rem;">Pintas Rentang:</span>
                    @php
                        $presetList = [
                            'today' => 'Hari Ini',
                            '7_days' => '7 Hari',
                            '30_days' => '30 Hari',
                            'this_month' => 'Bulan Ini',
                            'last_month' => 'Bulan Lalu',
                            'this_year' => 'Tahun Ini',
                        ];
                    @endphp

                    @foreach($presetList as $key => $label)
                        <button
                            type="button"
                            wire:click="setPreset('{{ $key }}')"
                            class="op-preset-btn {{ $this->activePreset === $key ? 'active' : '' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="op-date-box">
                        <svg class="op-ic-xs text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span style="font-size: 0.75rem; color: #6b7280; font-weight: 500;">Dari:</span>
                        <input type="date" wire:model.live="startDate" class="op-date-input">
                    </div>

                    <span style="color: #9ca3af; font-size: 0.75rem; font-weight: 700;">s/d</span>

                    <div class="op-date-box">
                        <svg class="op-ic-xs text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span style="font-size: 0.75rem; color: #6b7280; font-weight: 500;">Sampai:</span>
                        <input type="date" wire:model.live="endDate" class="op-date-input">
                    </div>
                </div>
            </div>
        </div>

        {{-- 4 Primary Metric Cards --}}
        <div class="op-stats-grid">
            {{-- Card 1: Total Permohonan --}}
            <div class="op-stat-card op-card-accent-teal">
                <div>
                    <div class="op-stat-head">
                        <div>
                            <p class="op-stat-label">Total Permohonan (FPPS)</p>
                            <p class="op-stat-value">{{ number_format($summary['total_requests']) }}</p>
                        </div>
                        <div class="op-icon-badge op-icb-teal">
                            <svg class="op-ic-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="op-stat-footer">
                    <span style="color: #0d9488; font-weight: 600;">{{ $summary['total_customers'] }} Pelanggan Unik</span>
                    <span style="color: #6b7280;">{{ $summary['paid_requests'] }} Terbayar</span>
                </div>
            </div>

            {{-- Card 2: Realisasi PNBP --}}
            <div class="op-stat-card op-card-accent-emerald">
                <div>
                    <div class="op-stat-head">
                        <div>
                            <p class="op-stat-label">Penerimaan PNBP Kas Masuk</p>
                            <p class="op-stat-value" style="color: #059669; font-size: 1.5rem;">
                                Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="op-icon-badge op-icb-emerald">
                            <svg class="op-ic-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="op-stat-footer">
                    <span style="color: #059669; font-weight: 600;">{{ $summary['paid_rate'] }}% Lunas Terbuku</span>
                    <span style="color: #6b7280;">SIMPONI / Kasir</span>
                </div>
            </div>

            {{-- Card 3: Sampel Masuk Lab --}}
            <div class="op-stat-card op-card-accent-blue">
                <div>
                    <div class="op-stat-head">
                        <div>
                            <p class="op-stat-label">Sampel Uji Diterima</p>
                            <p class="op-stat-value" style="color: #2563eb;">{{ number_format($summary['total_samples']) }}</p>
                        </div>
                        <div class="op-icon-badge op-icb-blue">
                            <svg class="op-ic-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="op-stat-footer">
                    <span style="color: #2563eb; font-weight: 600;">{{ $sampleStats['accepted'] }} Memenuhi Syarat</span>
                    <span style="color: #e11d48; font-weight: 600;">{{ $sampleStats['rejected'] }} Ditolak</span>
                </div>
            </div>

            {{-- Card 4: LHU Terbit Sah --}}
            <div class="op-stat-card op-card-accent-purple">
                <div>
                    <div class="op-stat-head">
                        <div>
                            <p class="op-stat-label">LHU Resmi Terbit</p>
                            <p class="op-stat-value" style="color: #7c3aed;">{{ number_format($summary['total_reports_issued']) }}</p>
                        </div>
                        <div class="op-icon-badge op-icb-purple">
                            <svg class="op-ic-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="op-stat-footer">
                    <span style="color: #7c3aed; font-weight: 600;">Ber-QR Code Sah</span>
                    <span style="color: #6b7280;">Status Final</span>
                </div>
            </div>
        </div>

        {{-- Row 2: Pipeline Tahapan & Saluran Pendaftaran --}}
        <div class="op-two-col-grid">
            {{-- Pipeline Pelayanan --}}
            <div class="op-card">
                <div class="op-card-head">
                    <div>
                        <h3 class="op-card-title">
                            <svg class="op-ic-sm text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                            <span>Pipeline Tahapan Pelayanan Pengujian</span>
                        </h3>
                        <p class="op-card-desc">Distribusi berkas aktif berdasarkan tahapan siklus pengerjaan laboratorium.</p>
                    </div>
                    @if($pipeline['cancelled'] > 0)
                        <span class="op-badge-pill" style="background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                            {{ $pipeline['cancelled'] }} Dibatalkan
                        </span>
                    @endif
                </div>

                <div class="op-pipeline-grid">
                    @foreach($pipeline['stages'] as $stg)
                        <div class="op-pipeline-item">
                            <p class="op-pipe-title">{{ $stg['name'] }}</p>
                            <p class="op-pipe-count">{{ $stg['count'] }} <span style="font-size: 0.75rem; font-weight: normal; color: #9ca3af;">berkas</span></p>
                            <p style="font-size: 0.6875rem; color: #6b7280; margin: 0.375rem 0 0 0;">{{ $stg['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Saluran Akses Pendaftaran --}}
            <div class="op-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="op-card-head">
                        <div>
                            <h3 class="op-card-title">
                                <svg class="op-ic-sm text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <span>Saluran Akses Registrasi</span>
                            </h3>
                            <p class="op-card-desc">Pilihan kanal masuk permohonan pelanggan.</p>
                        </div>
                    </div>

                    <div style="margin-top: 1rem;">
                        @foreach($channels['channels'] as $chn)
                            <div class="op-channel-item">
                                <div class="op-channel-head">
                                    <span style="color: #374151;">{{ $chn['label'] }}</span>
                                    <span style="font-weight: 700;">{{ $chn['count'] }} berkas ({{ $chn['percent'] }}%)</span>
                                </div>
                                <div class="op-progress-bar-bg">
                                    <div class="op-progress-fill" style="width: {{ $chn['percent'] }}%; background: {{ $chn['color'] === 'teal' ? '#0d9488' : ($chn['color'] === 'blue' ? '#2563eb' : '#059669') }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #f3f4f6; font-size: 0.75rem; color: #6b7280; text-align: center;">
                    Total Berkas Masuk Saluran: <strong>{{ $channels['total'] }}</strong>
                </div>
            </div>
        </div>

        {{-- Row 3: Distribusi Kelompok Layanan (Katalog Labkesmas) --}}
        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h3 class="op-card-title">
                        <svg class="op-ic-sm text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Distribusi Permohonan & PNBP per Kelompok Layanan (Katalog Labkesmas)</span>
                    </h3>
                    <p class="op-card-desc">Beban pengujian laboratorium dan kontribusi PNBP berdasarkan kelompok layanan pembina.</p>
                </div>
                <span class="op-badge-pill" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1;">
                    {{ $serviceStats->count() }} Kelompok Terdaftar
                </span>
            </div>

            <div class="op-table-wrap">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th>Kode & Nama Kelompok Layanan</th>
                            <th>Instalasi Laboratorium</th>
                            <th style="text-align: center;">Paket Aktif</th>
                            <th style="min-width: 180px;">Volume Permohonan & Pangsa</th>
                            <th style="text-align: right;">Estimasi PNBP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceStats as $group)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span class="op-badge-pill" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; font-family: monospace;">
                                            {{ $group->code }}
                                        </span>
                                        <strong>{{ $group->name }}</strong>
                                    </div>
                                    @if($group->description)
                                        <div style="font-size: 0.6875rem; color: #9ca3af; margin-top: 2px;">{{ $group->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="op-badge-pill" style="background: #f3f4f6; color: #4b5563;">
                                        {{ $group->laboratoryUnit?->name ?? 'Non-Instalasi' }}
                                    </span>
                                </td>
                                <td style="text-align: center; font-weight: 500;">
                                    {{ $group->servicePackages->count() }} Paket
                                </td>
                                <td>
                                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 600; margin-bottom: 4px;">
                                        <span>{{ $group->test_requests_count }} berkas</span>
                                        <span style="color: #6b7280;">{{ $group->share_percent }}%</span>
                                    </div>
                                    <div class="op-progress-bar-bg" style="height: 6px;">
                                        <div class="op-progress-fill" style="width: {{ $group->share_percent }}%; background: #0d9488;"></div>
                                    </div>
                                </td>
                                <td style="text-align: right; font-family: monospace; font-weight: 700; color: #059669;">
                                    Rp {{ number_format($group->revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #9ca3af;">
                                    Belum ada data kelompok layanan untuk rentang waktu ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Row 4: Kepatuhan SLA & Evaluasi Fisik Sampel --}}
        <div class="op-equal-grid">
            {{-- Kepatuhan SLA --}}
            <div class="op-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="op-card-head">
                        <div>
                            <h3 class="op-card-title">
                                <svg class="op-ic-sm text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Kepatuhan Standar Waktu Pengujian (SLA)</span>
                            </h3>
                            <p class="op-card-desc">Ketepatan penyelesaian LHU terhadap target estimasi SLA (Standar SPM Kemenkes ≥ 90%).</p>
                        </div>
                    </div>

                    <div class="op-score-box op-score-box-teal">
                        <div>
                            <span style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; color: #6b7280;">Tingkat Kepatuhan SLA</span>
                            <div class="op-score-number" style="color: {{ $sla['compliance_rate'] >= 90 ? '#059669' : ($sla['compliance_rate'] >= 75 ? '#d97706' : '#e11d48') }};">
                                {{ $sla['compliance_rate'] }}%
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: {{ $sla['compliance_rate'] >= 90 ? '#059669' : '#d97706' }};">
                                {{ $sla['compliance_rate'] >= 90 ? '✓ Memenuhi Target SPM (≥ 90%)' : '⚠ Perlu Akselerasi Pengerjaan' }}
                            </span>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 0.75rem; color: #6b7280; display: block;">Total Berkas Bersyarat SLA</span>
                            <span style="font-size: 1.5rem; font-weight: 800; color: #111827;">{{ $sla['total_with_sla'] }}</span>
                            <span style="font-size: 0.75rem; color: #6b7280; display: block;">Berkas</span>
                        </div>
                    </div>

                    <div class="op-subgrid-3">
                        <div class="op-mini-box" style="background: #ecfdf5; border-color: #a7f3d0;">
                            <span style="font-size: 0.6875rem; font-weight: 600; color: #047857;">Tepat Waktu</span>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #047857; margin-top: 2px;">{{ $sla['completed_on_time'] }}</div>
                            <span style="font-size: 0.625rem; color: #059669;">Selesai ≤ SLA</span>
                        </div>
                        <div class="op-mini-box" style="background: #eff6ff; border-color: #bfdbfe;">
                            <span style="font-size: 0.6875rem; font-weight: 600; color: #1d4ed8;">Dalam Pengerjaan</span>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #1d4ed8; margin-top: 2px;">{{ $sla['in_progress'] }}</div>
                            <span style="font-size: 0.625rem; color: #2563eb;">Sedang Uji Lab</span>
                        </div>
                        <div class="op-mini-box" style="background: #fff1f2; border-color: #fecdd3;">
                            <span style="font-size: 0.6875rem; font-weight: 600; color: #be123c;">Overdue / Terlambat</span>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #be123c; margin-top: 2px;">{{ $sla['overdue'] }}</div>
                            <span style="font-size: 0.625rem; color: #e11d48;">Lewat Batas</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #f3f4f6; font-size: 0.6875rem; color: #9ca3af; text-align: center;">
                    Perhitungan waktu mengacu pada saat penerimaan sampel fisik hingga pengesahan LHU final.
                </div>
            </div>

            {{-- Evaluasi Verifikasi Sampel --}}
            <div class="op-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="op-card-head">
                        <div>
                            <h3 class="op-card-title">
                                <svg class="op-ic-sm text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span>Verifikasi Fisik Sampel (ISO/IEC 17025)</span>
                            </h3>
                            <p class="op-card-desc">Integritas kondisi fisik, kecukupan volume, dan kelayakan spesimen saat diterima.</p>
                        </div>
                    </div>

                    <div class="op-score-box op-score-box-blue">
                        <div>
                            <span style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; color: #6b7280;">Total Sampel Diverifikasi</span>
                            <div class="op-score-number" style="color: #111827;">{{ $sampleStats['total'] }}</div>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #059669;">
                                ✓ {{ $sampleStats['total'] > 0 ? (100 - $sampleStats['rejection_rate']) : 100 }}% Layak Diuji
                            </span>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 0.75rem; color: #6b7280; display: block;">Rasio Ditolak (Reject Rate)</span>
                            <span style="font-size: 1.5rem; font-weight: 800; color: #e11d48;">{{ $sampleStats['rejection_rate'] }}%</span>
                            <span style="font-size: 0.6875rem; color: #6b7280; display: block;">Target ISO: &lt; 3%</span>
                        </div>
                    </div>

                    <div class="op-subgrid-2" style="margin-bottom: 0.75rem;">
                        <div class="op-mini-box" style="background: #ecfdf5; border-color: #a7f3d0;">
                            <span style="font-size: 0.6875rem; font-weight: 600; color: #047857;">Diterima (MS)</span>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #047857; margin-top: 2px;">{{ $sampleStats['accepted'] }}</div>
                        </div>
                        <div class="op-mini-box" style="background: #fff1f2; border-color: #fecdd3;">
                            <span style="font-size: 0.6875rem; font-weight: 600; color: #be123c;">Ditolak (TMS)</span>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #be123c; margin-top: 2px;">{{ $sampleStats['rejected'] }}</div>
                        </div>
                    </div>

                    @if($sampleStats['rejections_list']->count() > 0)
                        <div>
                            <span style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; color: #6b7280; display: block; margin-bottom: 0.375rem;">
                                Sampel Ditolak Terkini:
                            </span>
                            <div style="max-height: 140px; overflow-y: auto;">
                                @foreach($sampleStats['rejections_list'] as $rej)
                                    <div class="op-rejection-card">
                                        <div style="display: flex; justify-content: space-between; font-weight: 700;">
                                            <span style="font-family: monospace; color: #0f766e;">{{ $rej->sample?->sample_code ?? 'SMP-N/A' }}</span>
                                            <span style="color: #9ca3af; font-size: 0.6875rem;">{{ $rej->received_at?->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div style="color: #be123c; font-weight: 600; margin-top: 2px;">
                                            Alasan: {{ $rej->rejection_reason ?? 'Kondisi fisik tidak memenuhi kriteria' }}
                                        </div>
                                        <div style="font-size: 0.6875rem; color: #6b7280; margin-top: 2px;">
                                            Petugas: <strong>{{ $rej->receivedBy?->name ?? '-' }}</strong> | Tindak lanjut: {{ $rej->follow_up ?? '-' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div style="text-align: center; padding: 0.75rem; background: #f9fafb; border-radius: 0.5rem; font-size: 0.75rem; color: #6b7280;">
                            Integritas sampel prima (100% spesimen memenuhi syarat uji).
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
