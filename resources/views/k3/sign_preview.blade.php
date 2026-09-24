<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi TTD Team Leader - {{ $k3->doc_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; font-size: 13px; }
        .form-container { max-width: 1050px; margin: 20px auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        /* HEADER TABLE STYLES */
        .table-header-box { border: 2px solid #000; width: 100%; border-collapse: collapse; }
        .table-header-box td { border: 1px solid #000; vertical-align: middle; }
        .table-header-box .meta-table { width: 100%; border-collapse: collapse; }
        .table-header-box .meta-table td { border: none; border-bottom: 1px solid #000; padding: 3px 8px; font-size: 11px; }
        .table-header-box .meta-table tr:last-child td { border-bottom: none; }

        /* CHECKLIST TABLE STYLES */
        .table-checklist { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-checklist th, .table-checklist td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        .table-checklist th { background-color: #f2f2f2; font-weight: bold; }
        
        /* SIGNATURE PAD STYLES */
        canvas { touch-action: none; width: 100%; height: 100%; cursor: crosshair; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('k3.list') }}" class="btn btn-secondary btn-sm fw-bold">← Kembali ke Daftar Dokumen</a>
        <a href="{{ route('k3.pdf', $k3->id) }}" target="_blank" class="btn btn-danger btn-sm fw-bold">🖨️ Cetak / Simpan PDF</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $tlSign = $k3->ttd_tl ?? $k3->signature_tl ?? $k3->tl_signature_path ?? null;
        $isSigned = $k3->is_tl_signed || !empty($tlSign);
    @endphp

    <form action="{{ route('k3.sign.process', $k3->id) }}" method="POST" id="k3SignForm">
        @csrf
        <input type="hidden" name="sign_as" value="tl">
        <input type="hidden" name="signature_data" id="signature_data">

        <!-- HEADER DOKUMEN -->
        <table class="table-header-box mb-3">
            <tr>
                <td rowspan="6" style="width: 20%; vertical-align: middle;" class="text-center bg-white p-2">
                            <img src="{{ asset('images/logo-pln.png') }}" alt="Logo PLN Icon Plus" style="max-height: 130px; width: auto; display: block; margin: 0 auto 4px auto;">
                </td>
                <td style="width: 60%; padding: 0;">
                    <div class="text-center fw-bold py-2 fs-6 border-bottom border-dark" style="letter-spacing: 2px;">
                        Formulir Persediaan APD
                    </div>
                    <table class="meta-table">
                        <tr>
                            <td style="width: 35%;">No. Dokumen</td>
                            <td class="fw-bold">: {{ $k3->doc_number ?? 'FR-K3L/ICON+/17-01' }}</td>
                        </tr>
                        <tr>
                            <td>Versi</td>
                            <td class="fw-bold">: {{ $k3->versi ?? '3' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td class="fw-bold">: {{ $k3->tgl_dokumen ?? date('d Agustus Y') }}</td>
                        </tr>
                        <tr>
                            <td>Klasifikasi</td>
                            <td class="fw-bold">: {{ $k3->klasifikasi ?? 'Internal' }}</td>
                        </tr>
                        <tr>
                            <td>Halaman</td>
                            <td class="fw-bold">: {{ $k3->halaman ?? '1 dari 1' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="text-center p-2" style="width: 15%;">
                    <svg width="65" height="65" viewBox="0 0 100 100" fill="#008000">
                        <path d="M50,15 A35,35 0 1,0 50,85 A35,35 0 1,0 50,15 M50,5 A45,45 0 1,1 50,95 A45,45 0 1,1 50,5 M42,30 H58 V42 H70 V58 H58 V70 H42 V58 H30 V42 H42 Z" />
                        <path d="M46,10 H54 V0 H46 Z M46,100 H54 V90 H46 Z M0,46 H10 V54 H0 Z M100,46 H90 V54 H100 Z M15,15 L22,22 L15,29 L8,22 Z M78,78 L85,85 L78,92 L71,85 Z M85,15 L92,22 L85,29 L78,22 Z M15,85 L22,78 L29,85 L22,92 Z" />
                    </svg>
                </td>
            </tr>
        </table>

        <div class="fw-bold mb-2">
            <div>Daftar Persediaan APD</div>
            <div>SBU Bali Nusra - Bidang Pembangunan dan Delivery Layanan Bali dan Nusra</div>
            <div class="mt-1">
                <span>Tahun: <strong>{{ $k3->tahun ?? '2026' }}</strong></span>
            </div>
        </div>

        @php
            $items = json_decode($k3->checklist_data ?? $k3->items_data, true) ?? [
                ['nama' => 'Safety Helmet', 'warna' => 'Putih', 'jumlah' => 14],
                ['nama' => 'Full Body Harness', 'warna' => 'Standard', 'jumlah' => 1],
                ['nama' => 'Safety Vest', 'warna' => 'Biru', 'jumlah' => 14],
                ['nama' => 'Safety Vest', 'warna' => 'Merah', 'jumlah' => 3],
            ];
        @endphp

        <table class="table-checklist">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 4%;">No</th>
                    <th rowspan="2" style="width: 22%;">Jenis Material</th>
                    <th rowspan="2" style="width: 18%;">Warna/Merk</th>
                    <th rowspan="2" style="width: 8%;">Jumlah</th>
                    <th colspan="12">Tahun {{ $k3->tahun ?? '2026' }}</th>
                </tr>
                <tr>
                    @for($i = 1; $i <= 12; $i++)
                        <th style="width: 4%;">{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start ps-2 fw-semibold">{{ $item['nama'] ?? '-' }}</td>
                        <td>{{ $item['warna'] ?? '-' }}</td>
                        <td class="fw-bold">{{ $item['jumlah'] ?? 0 }}</td>
                        @for($i = 1; $i <= 12; $i++)
                            <td>
                                <input type="checkbox" {{ isset($item['bulan'][$i]) || $i <= 8 ? 'checked' : '' }} disabled>
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- DESAIN CARD TANDA TANGAN (SAMA DENGAN PIC) -->
        <div class="row mt-4">
            <!-- Disiapkan Oleh (PIC / Engineer) -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm border-0 bg-light">
                    <div class="card-header bg-white text-center fw-bold border-bottom-0 pt-3">
                        Disiapkan Oleh<br>
                        <span class="text-muted fw-normal" style="font-size: 12px;">Engineer Pembangunan</span>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="bg-white w-100 border d-flex align-items-center justify-content-center mb-2 rounded" style="height: 140px;">
                            @php $picSign = $k3->ttd_engineer ?? $k3->engineer_signature_path ?? $k3->signature_pic ?? null; @endphp
                            @if(!empty($picSign))
                                <div class="text-center pt-3">
                                    <img src="{{ asset('storage/' . str_replace('storage/', '', $picSign)) }}" alt="TTD Engineer" style="max-height: 100px; max-width: 100%;">
                                    <div class="small text-success fw-bold mt-1">✓ Sudah TTD</div>
                                </div>
                            @else
                                <span class="text-muted" style="font-style: italic; font-size: 13px;">Belum TTD</span>
                            @endif
                        </div>
                        <div class="mb-3" style="height: 31px;"></div>

                        <div class="form-control text-center fw-bold mb-2 bg-white">{{ $k3->nama_engineer ?? 'Engineer' }}</div>
                        <div class="form-control form-control-sm text-center text-muted bg-white">{{ !empty($k3->tgl_ttd_engineer) ? \Carbon\Carbon::parse($k3->tgl_ttd_engineer)->format('Y-m-d') : '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Diperiksa Oleh (Team Leader / TL - AKTIF KANVAS) -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm border-0 bg-light">
                    <div class="card-header bg-white text-center fw-bold border-bottom-0 pt-3">
                        Diperiksa Oleh<br>
                        <span class="text-muted fw-normal" style="font-size: 12px;">Team Leader Delivery Layanan</span>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="border border-dark w-100 rounded mb-2 bg-white" style="height: 140px; border-style: dashed !important; position: relative;">
                            @if($isSigned)
                                <div class="text-center pt-3">
                                    <img src="{{ asset('storage/' . str_replace('storage/', '', $tlSign)) }}" alt="TTD TL" style="max-height: 100px; max-width: 100%;">
                                    <div class="small text-success fw-bold mt-1">✓ Sudah TTD</div>
                                </div>
                            @else
                                <canvas id="signature-pad"></canvas>
                            @endif
                        </div>
                        
                        @if(!$isSigned)
                            <button type="button" id="clear" class="btn btn-outline-danger btn-sm mb-3">Reset TTD</button>
                        @else
                            <div class="mb-3" style="height: 31px;"></div>
                        @endif

                        <div class="form-control text-center fw-bold mb-2 bg-white">{{ Auth::user()->name ?? $k3->nama_tl ?? 'Team Leader' }}</div>
                        <div class="form-control form-control-sm text-center text-muted bg-white">{{ $isSigned && $k3->tgl_tl ? \Carbon\Carbon::parse($k3->tgl_tl)->format('Y-m-d') : date('Y-m-d') }}</div>
                    </div>
                </div>
            </div>

            <!-- Disetujui Oleh (Manager) -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm border-0 bg-light">
                    <div class="card-header bg-white text-center fw-bold border-bottom-0 pt-3">
                        Disetujui Oleh<br>
                        <span class="text-muted fw-normal" style="font-size: 12px;">Manager Pembangunan & Delivery</span>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <div class="bg-white w-100 border d-flex align-items-center justify-content-center mb-2 rounded" style="height: 140px;">
                            @if(!empty($k3->ttd_manager))
                                <div class="text-center pt-3">
                                    <img src="{{ asset('storage/' . str_replace('storage/', '', $k3->ttd_manager)) }}" alt="TTD Manager" style="max-height: 100px; max-width: 100%;">
                                    <div class="small text-success fw-bold mt-1">✓ Sudah TTD</div>
                                </div>
                            @else
                                <span class="text-muted" style="font-style: italic; font-size: 13px;">Menunggu TTD Manager</span>
                            @endif
                        </div>
                        <div class="mb-3" style="height: 31px;"></div>

                        <div class="form-control text-center fw-bold mb-2 bg-white">{{ $k3->nama_manager ?? 'Manager' }}</div>
                        <div class="form-control form-control-sm text-center text-muted bg-white">{{ !empty($k3->tgl_ttd_manager) ? \Carbon\Carbon::parse($k3->tgl_ttd_manager)->format('Y-m-d') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if(!$isSigned)
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm">
                    <i class="bi bi-pen-fill me-1"></i> Simpan Tanda Tangan Team Leader
                </button>
            </div>
        @endif
    </form>
</div>

<!-- SCRIPT SIGNATURE PAD SAMA SEPERTI HALAMAN PIC -->
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

        document.getElementById('k3SignForm').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                alert("Silakan buat tanda tangan terlebih dahulu pada kotak yang disediakan!");
                e.preventDefault();
            } else {
                document.getElementById('signature_data').value = signaturePad.toDataURL('image/png');
            }
        });
    }
</script>
</body>
</html>