<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Persediaan APD - {{ $k3->doc_number ?? '' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body { 
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif; 
            font-size: 9px; 
            color: #000; 
            background-color: #fff; 
            line-height: 1.2;
        }

        /* Header ISO Presisi - TABEL KECIL PADAT */
        .table-iso { 
            border: 1.5px solid #000; 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 12px; 
        }
        .table-iso td, .table-iso th { 
            border: 1px solid #000; 
            padding: 1px 4px; 
            vertical-align: middle; 
        }

        /* Checklist Table */
        .table-checklist { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            margin-bottom: 8px; 
        }
        .table-checklist th, .table-checklist td { 
            border: 1px solid #000; 
            padding: 6px 3px; 
            text-align: center; 
            vertical-align: middle; 
            font-size: 9px; 
        }
        .table-checklist th { 
            background-color: #ffffff; 
            font-size: 9.5px;
            font-weight: bold;
        }

        /* TTD Table */
        .sign-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        .sign-table td { 
            border: 1px solid #000; 
            padding: 6px 8px; 
            vertical-align: top; 
            width: 33.33%; 
        }

        /* DUKUNGAN SIMBOL CENTANG UNTUK DOMPDF */
        .check-symbol {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            font-weight: bold;
            color: #000;
        }

        /* Sub-Table Khusus Isian TTD */
        .inner-table {
            width: 100%;
            border-collapse: collapse !important;
            border: none !important;
        }
        .inner-table td {
            border: none !important;
            padding: 2px 0 !important;
            vertical-align: top !important;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen ISO -->
    <table class="table-iso">
        <tr>
            <!-- Logo PLN -->
            <td rowspan="6" style="width: 33%; text-align: center; padding: 2px;">
                <img src="{{ public_path('images/logo-pln.png') }}" style="width: 100%; max-height: 85px; object-fit: contain; display: block; margin: 0 auto;" alt="Logo PLN">
                <span style="font-size: 8px; display: block; font-weight: bold; margin-top: 2px;">PT INDONESIA COMNETS PLUS</span>
            </td>

            <!-- Judul Utama -->
            <td colspan="2" style="width: 49%; text-align: center; font-size: 11px; font-weight: bold; letter-spacing: 1px; padding: 3px; height: 16px;">
                Formulir Persediaan APD
            </td>

            <!-- Logo K3 -->
            <td rowspan="6" style="width: 25%; text-align: center; padding: 2px;">
                <img src="{{ public_path('images/logo-k3.png') }}" style="width: 85%; max-height: 80px; object-fit: contain; display: block; margin: 0 auto;" alt="Logo K3">
            </td>
        </tr>
        
        <tr style="height: 11px;">
            <td style="width: 22%; font-size: 9px; padding: 1px 4px;">No. Dokumen</td>
            <td style="width: 78%; font-size: 9px; font-weight: bold; padding: 1px 4px;">{{ $k3->doc_number ?? '-' }}</td>
        </tr>
        <tr style="height: 11px;">
            <td style="font-size: 9px; padding: 1px 4px;">Versi</td>
            <td style="font-size: 9px; padding: 1px 4px;">{{ $k3->versi ?? '3' }}</td>
        </tr>
        <tr style="height: 11px;">
            <td style="font-size: 9px; padding: 1px 4px;">Tanggal</td>
            <td style="font-size: 9px; padding: 1px 4px;">
                {{ !empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->translatedFormat('d F Y') : date('d F Y') }}
            </td>
        </tr>
        <tr style="height: 11px;">
            <td style="font-size: 9px; padding: 1px 4px;">Klasifikasi</td>
            <td style="font-size: 9px; padding: 1px 4px;">{{ $k3->klasifikasi ?? 'Internal' }}</td>
        </tr>
        <tr>
            <td style="font-size: 9px; padding: 1px 4px;">Halaman</td>
            <td style="font-size: 9px; padding: 1px 4px;">1 dari 1</td>
        </tr>
    </table>

    <!-- Keterangan Atas -->
    <div style="font-size: 10.5px; margin-bottom: 8px; line-height: 1.3;">
        <div style="font-weight: bold;">Daftar Persediaan APD</div>
        <div>SBU Bali Nusra</div>
        <div>Bidang Pembangunan dan Delivery Layanan Bali dan Nusra</div>
        <div style="margin-top: 1px;">Tahun : {{ $k3->tahun ?? '2026' }}</div>
    </div>

    @php
        $itemsList = is_array($k3->checklist_data ?? null) 
            ? $k3->checklist_data 
            : (json_decode($k3->checklist_data ?? '', true) ?? [
                ['nama' => 'Safety Helmet', 'warna' => 'Putih', 'jumlah' => 14],
                ['nama' => 'Full Body Harness', 'warna' => 'Standard', 'jumlah' => 3],
                ['nama' => 'Safety Vest', 'warna' => 'Biru', 'jumlah' => 14],
                ['nama' => 'Safety Vest', 'warna' => 'Merah', 'jumlah' => 3],
            ]);
    @endphp

    <!-- Tabel Checklist Material -->
    <table class="table-checklist">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No</th>
                <th rowspan="2" style="width: 26%;">Jenis Material</th>
                <th rowspan="2" style="width: 17%;">Warna/Merk</th>
                <th rowspan="2" style="width: 10%;">Jumlah</th>
                <th colspan="12">Tahun {{ $k3->tahun ?? '2026' }}</th>
            </tr>
            <tr>
                @for($i = 1; $i <= 12; $i++)
                    <th style="width: 3.5%;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($itemsList as $index => $it)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 8px;">{{ $it['nama'] ?? $it['jenis_material'] ?? '-' }}</td>
                    <td>{{ $it['warna'] ?? $it['warna_merk'] ?? '-' }}</td>
                    <td>{{ $it['jumlah'] ?? 0 }}</td>
                    @for($i = 1; $i <= 12; $i++)
                        <td>
                            @if(isset($it['bulan'][$i]) && $it['bulan'][$i])
                                <span class="check-symbol">&#8730;</span>
                            @else
                                <span></span>
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Keterangan Tambahan -->
    <div style="font-size: 10.5px; margin-top: 4px; margin-bottom: 12px;">
        <div style="font-weight: bold;">Keterangan :</div>
        <div><span class="check-symbol">&#8730;</span> = Done Pengecekan</div>
    </div>

    <!-- Tabel Tanda Tangan -->
    <table class="sign-table">
        <tr style="font-weight: bold; text-align: center; height: 24px;">
            <td style="vertical-align: middle;">Disiapkan Oleh</td>
            <td style="vertical-align: middle;">Diperiksa Oleh</td>
            <td style="vertical-align: middle;">Di setujui Oleh</td>
        </tr>
        <tr style="height: 140px;">
            <!-- TTD Engineer -->
            <td style="vertical-align: middle; text-align: center;">
                @if(!empty($engineerSign))
                    <img src="{{ $engineerSign }}" style="max-height: 120px; max-width: 170px; display: block; margin: 0 auto;">
                @elseif(!empty($k3->ttd_engineer))
                    <img src="{{ public_path('storage/' . $k3->ttd_engineer) }}" style="max-height: 120px; max-width: 170px; display: block; margin: 0 auto;">
                @else
                    <span style="font-style: italic; color: #777; font-size: 8.5px;">(Belum TTD)</span>
                @endif
            </td>
            <!-- TTD Team Leader -->
            <td style="vertical-align: middle; text-align: center;">
                @if(!empty($tlSign))
                    <img src="{{ $tlSign }}" style="max-height: 120px; max-width: 170px; display: block; margin: 0 auto;">
                @elseif(!empty($k3->ttd_tl))
                    <img src="{{ public_path('storage/' . $k3->ttd_tl) }}" style="max-height: 120px; max-width: 170px; display: block; margin: 0 auto;">
                @else
                    <span style="font-style: italic; color: #777; font-size: 8.5px;">(Menunggu TTD)</span>
                @endif
            </td>
            <!-- TTD Manager -->
            <td style="vertical-align: middle; text-align: center;">
                @if(!empty($managerSign))
                    <img src="{{ $managerSign }}" style="max-height: 120px; max-width: 170px; display: block; margin: 0 auto;">
                @elseif(!empty($k3->ttd_manager))
                    <img src="{{ public_path('storage/' . $k3->ttd_manager) }}" style="max-height: 120px; max-width: 170px; display: block; margin: 0 auto;">
                @else
                    <span style="font-style: italic; color: #777; font-size: 8.5px;">(Menunggu TTD)</span>
                @endif
            </td>
        </tr>
        <tr style="text-align: center; font-weight: bold; font-size: 8.5px;">
            <td style="padding: 6px 4px; vertical-align: middle;">Engineer Pembangunan</td>
            <td style="padding: 6px 4px; vertical-align: middle;">Team Leader Delivery Layanan</td>
            <td style="padding: 6px 4px; vertical-align: middle;">Manager Pembangunan & Delivery Layanan</td>
        </tr>
        <tr style="font-size: 8.5px;">
            <td style="padding: 4px 6px;">
                <table class="inner-table">
                    <tr>
                        <td style="width: 48px; font-weight: bold; text-align: left;">Nama</td>
                        <td style="width: 8px; text-align: center;">:</td>
                        <td style="font-weight: bold; text-align: left;">{{ $k3->nama_engineer ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 4px 6px;">
                <table class="inner-table">
                    <tr>
                        <td style="width: 48px; font-weight: bold; text-align: left;">Nama</td>
                        <td style="width: 8px; text-align: center;">:</td>
                        <td style="font-weight: bold; text-align: left;">{{ $k3->nama_tl ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 4px 6px;">
                <table class="inner-table">
                    <tr>
                        <td style="width: 48px; font-weight: bold; text-align: left;">Nama</td>
                        <td style="width: 8px; text-align: center;">:</td>
                        <td style="font-weight: bold; text-align: left;">{{ $k3->nama_manager ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="font-size: 8.5px;">
            <td style="padding: 4px 6px;">
                <table class="inner-table">
                    <tr>
                        <td style="width: 48px; font-weight: bold; text-align: left;">Tanggal</td>
                        <td style="width: 8px; text-align: center;">:</td>
                        <td style="text-align: left;">{{ !empty($k3->tgl_ttd_engineer) ? \Carbon\Carbon::parse($k3->tgl_ttd_engineer)->translatedFormat('d F Y') : (!empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->translatedFormat('d F Y') : '-') }}</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 4px 6px;">
                <table class="inner-table">
                    <tr>
                        <td style="width: 48px; font-weight: bold; text-align: left;">Tanggal</td>
                        <td style="width: 8px; text-align: center;">:</td>
                        <td style="text-align: left;">{{ !empty($k3->tgl_ttd_tl) ? \Carbon\Carbon::parse($k3->tgl_ttd_tl)->translatedFormat('d F Y') : (!empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->translatedFormat('d F Y') : '-') }}</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 4px 6px;">
                <table class="inner-table">
                    <tr>
                        <td style="width: 48px; font-weight: bold; text-align: left;">Tanggal</td>
                        <td style="width: 8px; text-align: center;">:</td>
                        <td style="text-align: left;">{{ !empty($k3->tgl_ttd_manager) ? \Carbon\Carbon::parse($k3->tgl_ttd_manager)->translatedFormat('d F Y') : (!empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->translatedFormat('d F Y') : '-') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- DOKUMENTASI FOTO BUKTI FISIK APD (HALAMAN 2) -->
    @if(!empty($fotoHelmet) || !empty($fotoHarness) || !empty($fotoVestBiru) || !empty($fotoVestMerah))
        <div style="page-break-before: always; margin-top: 10px;">
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 10px; border-bottom: 1.5px solid #000; padding-bottom: 4px;">
                Dokumentasi Foto Bukti Fisik APD
            </div>

            <table style="width: 100%; border-collapse: collapse; border: none;">
                <tr>
                    <td style="width: 50%; border: none; text-align: center; vertical-align: top; padding: 6px;">
                        @if(!empty($fotoHelmet))
                            <div style="border: 1px solid #000; padding: 5px; background: #fff;">
                                <img src="{{ $fotoHelmet }}" style="width: 100%; max-height: 240px; display: block; margin: 0 auto;">
                                <div style="font-weight: bold; font-size: 10px; margin-top: 6px;">Safety Helmet</div>
                            </div>
                        @endif
                    </td>
                    <td style="width: 50%; border: none; text-align: center; vertical-align: top; padding: 6px;">
                        @if(!empty($fotoHarness))
                            <div style="border: 1px solid #000; padding: 5px; background: #fff;">
                                <img src="{{ $fotoHarness }}" style="width: 100%; max-height: 240px; display: block; margin: 0 auto;">
                                <div style="font-weight: bold; font-size: 10px; margin-top: 6px;">Full Body Harness</div>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; border: none; text-align: center; vertical-align: top; padding: 6px;">
                        @if(!empty($fotoVestBiru))
                            <div style="border: 1px solid #000; padding: 5px; background: #fff;">
                                <img src="{{ $fotoVestBiru }}" style="width: 100%; max-height: 240px; display: block; margin: 0 auto;">
                                <div style="font-weight: bold; font-size: 10px; margin-top: 6px;">Safety Vest Biru</div>
                            </div>
                        @endif
                    </td>
                    <td style="width: 50%; border: none; text-align: center; vertical-align: top; padding: 6px;">
                        @if(!empty($fotoVestMerah))
                            <div style="border: 1px solid #000; padding: 5px; background: #fff;">
                                <img src="{{ $fotoVestMerah }}" style="width: 100%; max-height: 240px; display: block; margin: 0 auto;">
                                <div style="font-weight: bold; font-size: 10px; margin-top: 6px;">Safety Vest Merah</div>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endif
</body>
</html>