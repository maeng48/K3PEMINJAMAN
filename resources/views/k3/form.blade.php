<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($k3) ? 'Edit' : 'Buat' }} Formulir K3 - {{ $docNumber }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .form-container { max-width: 1000px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        
        /* CSS Khusus Header ISO Presisi */
        .table-iso { border: 1.5px solid #000 !important; border-collapse: collapse !important; width: 100%; margin-bottom: 1.5rem; }
        .table-iso td, .table-iso th { border: 1px solid #000 !important; color: #000; padding: 0 !important; }
        
        .table-checklist th, .table-checklist td { border: 1px solid #000 !important; text-align: center; vertical-align: middle; font-size: 11px; }
        .table-checklist th { background-color: #f1f5f9; }
        canvas { touch-action: none; width: 100%; height: 100%; cursor: crosshair; }
    </style>
</head>
<body>

    <div class="container form-container">
        <!-- Navigasi Atas -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                <a href="{{ route('k3.list') }}" class="btn btn-secondary btn-sm fw-bold">← Kembali ke Daftar Dokumen</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm fw-bold">
                    <i class="bi bi-house me-1"></i> Dashboard
                </a>
            </div>
            <span class="badge bg-primary fs-6">Formulir Persediaan APD</span>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Utama -->
        <form action="{{ isset($k3) ? route('k3.update', $k3->id) : route('k3.save') }}" method="POST" enctype="multipart/form-data" id="k3Form">
            @csrf
            @if(isset($k3))
                @method('PUT')
            @endif

            <!-- HEADER DOKUMEN ISO (Proporsi Disesuaikan Presisi) -->
            <table class="table table-iso">
                <tbody>
                    <tr>
                        <!-- Kolom 1: Logo PLN Icon Plus -->
                        <td rowspan="6" style="width: 20%; vertical-align: middle;" class="text-center bg-white p-2">
                            <img src="{{ asset('images/logo-pln.png') }}" alt="Logo PLN Icon Plus" style="max-height: 85px; width: auto; display: block; margin: 0 auto 4px auto;">
                        </td>

                        <!-- Kolom 2 Baris 1: Judul Utama -->
                        <td colspan="2" style="width: 66%; height: 32px; vertical-align: middle;" class="text-center bg-white">
                            <span class="fw-bold" style="font-size: 13px; letter-spacing: 2px; font-family: Arial, sans-serif;">Formulir Persediaan APD</span>
                        </td>                                                                                                                         

                        <!-- Kolom 3: Logo K3 Hijau -->
                        <td rowspan="6" style="width: 14%; vertical-align: middle;" class="text-center bg-white p-2">
                            <img src="{{ asset('images/logo-k3.png') }}" alt="Logo K3" style="max-height: 65px; width: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <!-- Baris No. Dokumen -->
                    <tr>
                        <td style="width: 32%; vertical-align: middle; padding: 2px 8px !important; font-size: 11px;" class="text-start bg-white">
                            No. Dokumen
                        </td>
                        <td style="width: 68%; vertical-align: middle; padding: 2px 8px !important;" class="text-start bg-white">
                            <input type="text" name="doc_number" class="form-control form-control-sm border-0 bg-transparent fw-bold p-0 shadow-none" style="font-size: 11px; height: auto;" value="{{ old('doc_number', $k3->doc_number ?? $docNumber) }}" required>
                        </td>
                    </tr>

                    <!-- Baris Versi -->
                    <tr>
                        <td style="vertical-align: middle; padding: 2px 8px !important; font-size: 11px;" class="text-start bg-white">
                            Versi
                        </td>
                        <td style="vertical-align: middle; padding: 2px 8px !important;" class="text-start bg-white">
                            <input type="text" name="versi" class="form-control form-control-sm border-0 bg-transparent p-0 shadow-none" style="font-size: 11px; height: auto;" value="{{ old('versi', $k3->versi ?? '3') }}" required>
                        </td>
                    </tr>

                    <!-- Baris Tanggal (Diubah ke Datepicker Klik Langsung) -->
                    <tr>
                        <td style="vertical-align: middle; padding: 2px 8px !important; font-size: 11px;" class="text-start bg-white">
                            Tanggal
                        </td>
                        <td style="vertical-align: middle; padding: 2px 8px !important;" class="text-start bg-white">
                            <input type="date" name="tgl_dokumen" onclick="this.showPicker()" class="form-control form-control-sm border-0 bg-transparent p-0 shadow-none" style="font-size: 11px; height: auto; cursor: pointer;" value="{{ old('tgl_dokumen', $k3->tgl_dokumen ?? date('Y-m-d')) }}" required>
                        </td>
                    </tr>

                    <!-- Baris Klasifikasi -->
                    <tr>
                        <td style="vertical-align: middle; padding: 2px 8px !important; font-size: 11px;" class="text-start bg-white">
                            Klasifikasi
                        </td>
                        <td style="vertical-align: middle; padding: 2px 8px !important;" class="text-start bg-white">
                            <input type="text" name="klasifikasi" class="form-control form-control-sm border-0 bg-transparent p-0 shadow-none" style="font-size: 11px; height: auto;" value="{{ old('klasifikasi', $k3->klasifikasi ?? 'Internal') }}" required>
                        </td>
                    </tr>

                    <!-- Baris Halaman -->
                    <tr>
                        <td style="vertical-align: middle; padding: 2px 8px !important; font-size: 11px;" class="text-start bg-white">
                            Halaman
                        </td>
                        <td style="vertical-align: middle; padding: 2px 8px !important; font-size: 11px;" class="text-start bg-white">
                            1 dari 1
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Sub Header Keterangan -->
            <div class="mb-3">
                <p class="mb-1 fw-bold text-dark" style="font-size: 14px;">Daftar Persediaan APD</p>
                <p class="text-muted small mb-2">SBU Bali Nusra - Bidang Pembangunan dan Delivery Layanan Bali dan Nusra</p>
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-bold small text-dark">Tahun:</label>
                    <input type="text" name="tahun" class="form-control form-control-sm fw-bold text-center" style="width: 100px;" value="{{ old('tahun', $k3->tahun ?? '2026') }}" required>
                </div>
            </div>

            @php
                $checklistData = isset($k3) && $k3->checklist_data ? (is_array($k3->checklist_data) ? $k3->checklist_data : json_decode($k3->checklist_data, true)) : [
                    ['nama' => 'Safety Helmet', 'warna' => 'Putih', 'jumlah' => 0, 'bulan' => array_fill(1, 12, true)],
                    ['nama' => 'Full Body Harness', 'warna' => 'Standard', 'jumlah' => 0, 'bulan' => array_fill(1, 12, true)],
                    ['nama' => 'Safety Vest', 'warna' => 'Biru', 'jumlah' => 0, 'bulan' => array_fill(1, 12, true)],
                    ['nama' => 'Safety Vest', 'warna' => 'Merah', 'jumlah' => 0, 'bulan' => array_fill(1, 12, true)],
                ];
            @endphp

            <!-- Tabel Checklist Material -->
            <div class="table-responsive mb-4">
                <table class="table table-checklist table-sm">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 5%;">No</th>
                            <th rowspan="2" style="width: 25%;">Jenis Material</th>
                            <th rowspan="2" style="width: 18%;">Warna/Merk</th>
                            <th rowspan="2" style="width: 8%;">Jumlah</th>
                            <th colspan="12">Tahun {{ $k3->tahun ?? '2026' }}</th>
                        </tr>
                        <tr>
                            @for($i = 1; $i <= 12; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklistData as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <input type="text" name="items[{{ $index }}][nama]" class="form-control form-control-sm text-center border-0 bg-transparent p-0" value="{{ $item['nama'] ?? $item['jenis_material'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][warna]" class="form-control form-control-sm text-center border-0 bg-transparent p-0" value="{{ $item['warna'] ?? $item['warna_merk'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][jumlah]" class="form-control form-control-sm fw-bold text-center border-0 bg-transparent p-0" value="{{ $item['jumlah'] ?? 0 }}" required>
                            </td>
                            @for($i = 1; $i <= 12; $i++)
                                <td>
                                    <input type="checkbox" name="items[{{ $index }}][bulan][{{ $i }}]" value="1" {{ (isset($item['bulan'][$i]) && $item['bulan'][$i]) ? 'checked' : '' }}>
                                </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tanda Tangan -->
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm border-0 bg-light">
                        <div class="card-header bg-white text-center fw-bold border-bottom-0 pt-3">
                            Disiapkan Oleh<br>
                            <span class="text-muted fw-normal" style="font-size: 12px;">Engineer Pembangunan</span>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="border border-dark w-100 rounded mb-2 bg-white" style="height: 140px; border-style: dashed !important; position: relative;">
                                @if(isset($k3) && $k3->ttd_engineer)
                                    <div class="text-center pt-3">
                                        <img src="{{ asset('storage/' . $k3->ttd_engineer) }}" alt="TTD Engineer" style="max-height: 100px; max-width: 100%;">
                                        <div class="small text-success fw-bold mt-1">✓ Sudah TTD</div>
                                    </div>
                                @else
                                    <canvas id="signature-pad"></canvas>
                                    <input type="hidden" name="signature_engineer" id="signature_engineer">
                                @endif
                            </div>
                            @if(!isset($k3) || !$k3->ttd_engineer)
                                <button type="button" id="clear" class="btn btn-outline-danger btn-sm mb-3">Reset TTD</button>
                            @else
                                <div class="mb-3" style="height: 31px;"></div>
                            @endif

                            <input type="text" name="nama_engineer" class="form-control text-center fw-bold mb-2" placeholder="Nama Engineer" value="{{ old('nama_engineer', $k3->nama_engineer ?? '') }}" required>
                            <input type="date" name="tgl_ttd_engineer" onclick="this.showPicker()" class="form-control form-control-sm text-center text-muted" style="cursor: pointer;" value="{{ old('tgl_ttd_engineer', $k3->tgl_ttd_engineer ?? date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm border-0 bg-light">
                        <div class="card-header bg-white text-center fw-bold border-bottom-0 pt-3">
                            Diperiksa Oleh<br>
                            <span class="text-muted fw-normal" style="font-size: 12px;">Team Leader Delivery Layanan</span>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="bg-white w-100 border d-flex align-items-center justify-content-center mb-2 rounded" style="height: 140px;">
                                @if(isset($k3) && $k3->ttd_tl)
                                    <img src="{{ asset('storage/' . $k3->ttd_tl) }}" alt="TTD TL" style="max-height: 110px; max-width: 100%;">
                                @else
                                    <span class="text-muted" style="font-style: italic; font-size: 13px;">Menunggu TTD TL</span>
                                @endif
                            </div>
                            <div class="mb-3" style="height: 31px;"></div>

                            <input type="text" name="nama_tl" class="form-control text-center fw-bold mb-2" placeholder="Nama Team Leader" value="{{ old('nama_tl', $k3->nama_tl ?? '') }}" required>
                            <input type="date" name="tgl_ttd_tl" onclick="this.showPicker()" class="form-control form-control-sm text-center text-muted" style="cursor: pointer;" value="{{ old('tgl_ttd_tl', $k3->tgl_ttd_tl ?? date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm border-0 bg-light">
                        <div class="card-header bg-white text-center fw-bold border-bottom-0 pt-3">
                            Disetujui Oleh<br>
                            <span class="text-muted fw-normal" style="font-size: 12px;">Manager Pembangunan & Delivery</span>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="bg-white w-100 border d-flex align-items-center justify-content-center mb-2 rounded" style="height: 140px;">
                                @if(isset($k3) && $k3->ttd_manager)
                                    <img src="{{ asset('storage/' . $k3->ttd_manager) }}" alt="TTD Manager" style="max-height: 110px; max-width: 100%;">
                                @else
                                    <span class="text-muted" style="font-style: italic; font-size: 13px;">Menunggu TTD Manager</span>
                                @endif
                            </div>
                            <div class="mb-3" style="height: 31px;"></div>

                            <input type="text" name="nama_manager" class="form-control text-center fw-bold mb-2" placeholder="Nama Manager" value="{{ old('nama_manager', $k3->nama_manager ?? '') }}" required>
                            <input type="date" name="tgl_ttd_manager" onclick="this.showPicker()" class="form-control form-control-sm text-center text-muted" style="cursor: pointer;" value="{{ old('tgl_ttd_manager', $k3->tgl_ttd_manager ?? date('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Foto Bukti Fisik APD -->
            <div class="card mb-4 border-0 shadow-sm bg-light mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-images me-1"></i> Dokumentasi Foto Bukti Fisik APD</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Safety Helmet</label>
                            <input type="file" name="foto_helmet" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Full Body Harness</label>
                            <input type="file" name="foto_harness" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Safety Vest Biru</label>
                            <input type="file" name="foto_vest_biru" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Safety Vest Merah</label>
                            <input type="file" name="foto_vest_merah" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Formulir K3
                </button>
            </div>
        </form>
    </div>

    <!-- Script Signature Pad -->
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

            document.getElementById('k3Form').addEventListener('submit', function (e) {
                if (!signaturePad.isEmpty()) {
                    document.getElementById('signature_engineer').value = signaturePad.toDataURL('image/png');
                }
            });
        }
    </script>
</body>
</html>