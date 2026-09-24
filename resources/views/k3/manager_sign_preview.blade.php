<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi Tanda Tangan Manager - {{ $k3->doc_number ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }
        .form-container { 
            max-width: 900px; 
            margin: 30px auto; 
            background: #fff; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.1); 
        }

        /* Header ISO Presisi */
        .table-iso { 
            border: 1.5px solid #000; 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 12px; 
        }
        .table-iso td, .table-iso th { 
            border: 1px solid #000; 
            padding: 4px 6px; 
            vertical-align: middle; 
        }

        /* Checklist Table */
        .table-checklist { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 8px; 
            margin-bottom: 15px; 
        }
        .table-checklist th, .table-checklist td { 
            border: 1px solid #000; 
            padding: 4px 2px; 
            text-align: center; 
            vertical-align: middle; 
            font-size: 10px; 
        }
        .table-checklist th { 
            background-color: #f2f2f2; 
        }

        /* TTD Table */
        .sign-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        .sign-table td { 
            border: 1px solid #000; 
            padding: 6px; 
            vertical-align: top; 
            text-align: center; 
            width: 33.33%; 
        }

        .signature-box {
            border: 2px dashed #0d6efd;
            height: 130px;
            background-color: #fff;
            position: relative;
            cursor: crosshair;
        }
        canvas {
            width: 100%;
            height: 100%;
            touch-action: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Tombol Navigasi Atas -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">← Kembali ke Dashboard</a>
            </div>
            <span class="badge bg-success fs-6">Mode Otorisasi Manager</span>
        </div>

        <!-- Header Dokumen ISO -->
        <table class="table-iso">
            <tr>
                <!-- Logo PLN -->
                <td rowspan="6" style="width: 20%; text-align: center; padding: 6px;">
                    <img src="{{ asset('images/logo-pln.png') }}" style="max-height: 77px; width: auto; display: block; margin: 0 auto 3px auto;" alt="Logo PLN">
                </td>

                <!-- Judul Utama -->
                <td colspan="2" style="width: 63%; text-align: center; font-size: 13px; font-weight: bold; height: 28px;">
                    Formulir Persediaan APD
                </td>

                <!-- Logo K3 -->
                <td rowspan="6" style="width: 17%; text-align: center; padding: 6px;">
                    <img src="{{ asset('images/logo-k3.png') }}" style="max-height: 85px; width: auto; display: block; margin: 0 auto;" alt="Logo K3">
                </td>
            </tr>
            <tr>
                <td style="width: 30%; font-size: 10px;">No. Dokumen</td>
                <td style="width: 70%; font-size: 10px; font-weight: bold;">{{ $k3->doc_number ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Versi</td>
                <td style="font-size: 10px;">{{ $k3->versi ?? '3' }}</td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Tanggal</td>
                <td style="font-size: 10px;">
                    {{ !empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->format('d/m/Y') : date('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Klasifikasi</td>
                <td style="font-size: 10px;">{{ $k3->klasifikasi ?? 'Internal' }}</td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Halaman</td>
                <td style="font-size: 10px;">1 dari 1</td>
            </tr>
        </table>

        <!-- Keterangan Atas -->
        <div style="font-size: 11px; margin-bottom: 10px;">
            <div style="font-weight: bold;">Daftar Persediaan APD</div>
            <div style="color: #555;">SBU Bali Nusra - Bidang Pembangunan dan Delivery Layanan Bali dan Nusra</div>
            <div style="font-weight: bold; margin-top: 3px;">Tahun: {{ $k3->tahun ?? '2026' }}</div>
        </div>

        @php
            $itemsList = is_array($k3->checklist_data) ? $k3->checklist_data : json_decode($k3->checklist_data, true) ?? [];
        @endphp

        <!-- Tabel Checklist Material -->
        <table class="table-checklist">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;">No</th>
                    <th rowspan="2" style="width: 27%;">Jenis Material</th>
                    <th rowspan="2" style="width: 18%;">Warna/Merk</th>
                    <th rowspan="2" style="width: 8%;">Jumlah</th>
                    <th colspan="12">Tahun {{ $k3->tahun ?? '2026' }}</th>
                </tr>
                <tr>
                    @for($i = 1; $i <= 12; $i++)
                        <th style="width: 3.5%;">{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse($itemsList as $index => $it)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left; padding-left: 6px; font-weight: bold;">{{ $it['nama'] ?? $it['jenis_material'] ?? '-' }}</td>
                        <td>{{ $it['warna'] ?? $it['warna_merk'] ?? '-' }}</td>
                        <td style="font-weight: bold;">{{ $it['jumlah'] ?? 0 }}</td>
                        @for($i = 1; $i <= 12; $i++)
                            <td>
                                @if(isset($it['bulan'][$i]) && $it['bulan'][$i])
                                    <span style="color: #0d6efd; font-weight: bold;">&#10003;</span>
                                @else
                                    <span style="color: #ccc;">-</span>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="text-center text-muted">Tidak ada data item checklist.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bagian Tanda Tangan -->
        <form action="{{ route('k3.sign.process', $k3->id) }}" method="POST" id="managerSignForm">
            @csrf
            <input type="hidden" name="sign_as" value="manager">
            <input type="hidden" name="signature_data" id="signature_data">

            <table class="sign-table">
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td>Disiapkan Oleh</td>
                    <td>Diperiksa Oleh</td>
                    <td class="bg-success text-white">Disetujui Oleh (Manager)</td>
                </tr>
                <tr style="font-size: 10px; color: #444;">
                    <td>Engineer Pembangunan</td>
                    <td>Team Leader Delivery Layanan</td>
                    <td>Manager Pembangunan & Delivery</td>
                </tr>
                <tr style="height: 130px;">
                    <!-- TTD Engineer -->
                    <td style="vertical-align: middle; text-align: center;">
                        @if(!empty($k3->ttd_engineer))
                            <img src="{{ asset('storage/' . $k3->ttd_engineer) }}" style="max-height: 110px; max-width: 100%; display: block; margin: 0 auto;">
                        @else
                            <span style="font-style: italic; color: #777;">(Menunggu TTD)</span>
                        @endif
                    </td>
                    <!-- TTD Team Leader -->
                    <td style="vertical-align: middle; text-align: center;">
                        @if(!empty($k3->ttd_tl))
                            <img src="{{ asset('storage/' . $k3->ttd_tl) }}" style="max-height: 110px; max-width: 100%; display: block; margin: 0 auto;">
                        @else
                            <span style="font-style: italic; color: #777;">(Menunggu TTD)</span>
                        @endif
                    </td>
                    <!-- TTD Manager (Canvas / Gambar) -->
                    <td style="vertical-align: middle; text-align: center; padding: 4px;">
                        @if(!empty($k3->ttd_manager))
                            <img src="{{ asset('storage/' . $k3->ttd_manager) }}" style="max-height: 110px; max-width: 100%; display: block; margin: 0 auto;">
                        @else
                            <div class="signature-box p-1">
                                <canvas id="signature-pad"></canvas>
                            </div>
                            <div class="mt-2 d-flex justify-content-between px-1">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="clear" style="font-size: 10px; padding: 2px 6px;">Ulangi TTD</button>
                                <span class="text-muted small align-self-center" style="font-size: 9px;">*Bubuhkan Tanda Tangan</span>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <!-- Nama & Tanggal Engineer -->
                    <td style="font-weight: bold; border-top: 1px solid #000; padding-top: 6px;">
                        {{ $k3->nama_engineer ?? '-' }}
                        <div style="font-weight: normal; font-size: 9px; color: #555; margin-top: 2px;">
                            {{ !empty($k3->tgl_ttd_engineer) ? \Carbon\Carbon::parse($k3->tgl_ttd_engineer)->format('d/m/Y') : (!empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->format('d/m/Y') : '-') }}
                        </div>
                    </td>
                    <!-- Nama & Tanggal TL -->
                    <td style="font-weight: bold; border-top: 1px solid #000; padding-top: 6px;">
                        {{ $k3->nama_tl ?? '-' }}
                        <div style="font-weight: normal; font-size: 9px; color: #555; margin-top: 2px;">
                            {{ !empty($k3->tgl_ttd_tl) ? \Carbon\Carbon::parse($k3->tgl_ttd_tl)->format('d/m/Y') : (!empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->format('d/m/Y') : '-') }}
                        </div>
                    </td>
                    <!-- Nama & Tanggal Manager -->
                    <td style="font-weight: bold; border-top: 1px solid #000; padding-top: 6px;">
                        {{ $k3->nama_manager ?? '-' }}
                        <div style="font-weight: normal; font-size: 9px; color: #555; margin-top: 2px;">
                            {{ !empty($k3->tgl_ttd_manager) ? \Carbon\Carbon::parse($k3->tgl_ttd_manager)->format('d/m/Y') : (!empty($k3->tgl_dokumen) ? \Carbon\Carbon::parse($k3->tgl_dokumen)->format('d/m/Y') : '-') }}
                        </div>
                    </td>
                </tr>
            </table>

            @if(empty($k3->ttd_manager))
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold">Simpan Tanda Tangan Manager</button>
            </div>
            @endif
        </form>
    </div>

    <!-- Library Signature Pad JS -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        if (canvas) {
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            const signaturePad = new SignaturePad(canvas);

            document.getElementById('clear').addEventListener('click', function () {
                signaturePad.clear();
            });

            const form = document.getElementById('managerSignForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (signaturePad.isEmpty()) {
                        alert("Silakan buat tanda tangan terlebih dahulu pada kotak yang disediakan!");
                        e.preventDefault();
                    } else {
                        document.getElementById('signature_data').value = signaturePad.toDataURL('image/png');
                    }
                });
            }
        }
    </script>
</body>
</html>