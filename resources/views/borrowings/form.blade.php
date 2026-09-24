<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Barang - K3 System</title>
    <!-- BOOTSTRAP 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .form-container { 
            max-width: 550px; 
            margin: 40px auto; 
            background: #fff; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h4 class="fw-bold text-primary text-center mb-1">Status Peminjaman Barang</h4>
            <p class="text-muted text-center small mb-4">Kode Barang: <strong>{{ $item->qr_code_id ?? request()->route('id') }}</strong></p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- 1. KONDISI: JIKA STATUS DIKEMBALIKAN --}}
            @if(isset($isReturnedOnly) && $isReturnedOnly)
                <div class="alert alert-success text-center p-4 shadow-sm mb-4">
                    <h4 class="fw-bold mb-2">✅ Barang Sudah Dikembalikan</h4>
                    <p class="small text-muted mb-3">{{ $returnedMessage ?? 'Barang ini telah selesai dikembalikan.' }}</p>
                    <hr>
                    <p class="small text-secondary mb-1"><strong>Peminjam Terakhir:</strong> {{ $item->peminjam_nama ?? '-' }}</p>
                    <p class="small text-secondary mb-0"><strong>Waktu Kembali:</strong> {{ $item->tgl_kembali ?? '-' }}</p>
                </div>

            {{-- 2. KONDISI: JIKA STATUS REJECTED (DITOLAK) --}}
            @elseif($item && optional($item)->approval_status === 'REJECTED')
                <!-- NOTIFIKASI KOTAK MERAH UNTUK STATUS REJECT -->
                <div class="alert alert-danger text-center p-4 shadow-sm mb-4 border-danger border-2 rounded-3">
                    <div class="mb-2">
                        <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-2">Permohonan Peminjaman Ditolak!</h4>
                    <p class="small text-dark mb-3 fw-semibold">
                        Maaf, permohonan peminjaman untuk barang dengan kode <u>{{ $item->qr_code_id ?? '' }}</u> ditolak oleh Admin/PIC.
                    </p>
                    
                    @if(!empty($item->catatan_penolakan) || !empty($item->reason) || session('reject_reason'))
                        <div class="bg-white p-3 rounded border text-start mb-3 shadow-sm">
                            <span class="d-block small text-danger fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> Alasan Penolakan:</span>
                            <p class="small text-secondary mb-0">
                                "{{ $item->catatan_penolakan ?? $item->reason ?? session('reject_reason') }}"
                            </p>
                        </div>
                    @endif

                    <hr class="my-3">
                    <p class="small text-muted mb-0">
                        Anda dapat mengajukan permohonan ulang dengan mengisi kembali formulir di bawah ini.
                    </p>
                </div>

                <!-- Form Pengajuan Ulang setelah Ditolak -->
                <form action="{{ route('borrowing.store', $item->qr_code_id ?? request()->route('id')) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Peminjam</label>
                        <input type="text" name="peminjam_nama" class="form-control" placeholder="Nama Lengkap" value="{{ old('peminjam_nama') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Divisi / Unit Kerja</label>
                        <input type="text" name="divisi" class="form-control" placeholder="Contoh: Operation / Maintenance" value="{{ old('divisi') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kategori Barang</label>
                        <input type="text" name="kategori_barang" class="form-control" placeholder="Contoh: APD K3 / Tools" value="{{ old('kategori_barang') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Merk / Nama Barang</label>
                        <input type="text" name="merk_barang" class="form-control" placeholder="Merk Barang" value="{{ old('merk_barang') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Keperluan Peminjaman</label>
                        <textarea name="keperluan" class="form-control" rows="2" placeholder="Untuk Pekerjaan..." required>{{ old('keperluan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Foto Saat Meminjam</label>
                        <input type="file" name="foto" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Catatan Tambahan</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Catatan kondisi barang (opsional)" value="{{ old('catatan') }}">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2">Kirim Ulang Permohonan</button>
                    </div>
                </form>

            {{-- 3. KONDISI: JIKA STATUS PENDING --}}
            @elseif(isset($isPendingOnly) && $isPendingOnly)
                <div class="alert alert-warning text-center p-4 shadow-sm">
                    <h4 class="fw-bold mb-2">⏳ Menunggu Approve</h4>
                    <p class="small text-muted mb-3">{{ $pendingMessage ?? 'Barang sedang dalam status PENDING.' }}</p>
                    <p class="small text-secondary mb-0">Halaman ini akan otomatis diperbarui setelah Admin memberikan persetujuan atau penolakan.</p>
                </div>
            @else
                <!-- FORMULIR PEMINJAMAN BARU (KOSONG) -->
                <form action="{{ route('borrowing.store', $item->qr_code_id ?? request()->route('id')) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Peminjam</label>
                        <input type="text" name="peminjam_nama" class="form-control" placeholder="Nama Lengkap" value="{{ old('peminjam_nama') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Divisi / Unit Kerja</label>
                        <input type="text" name="divisi" class="form-control" placeholder="Contoh: Operation / Maintenance" value="{{ old('divisi') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kategori Barang</label>
                        <input type="text" name="kategori_barang" class="form-control" placeholder="Contoh: APD K3 / Tools" value="{{ old('kategori_barang') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Merk / Nama Barang</label>
                        <input type="text" name="merk_barang" class="form-control" placeholder="Merk Barang" value="{{ old('merk_barang') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Keperluan Peminjaman</label>
                        <textarea name="keperluan" class="form-control" rows="2" placeholder="Untuk Pekerjaan..." required>{{ old('keperluan') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Foto Saat Meminjam</label>
                        <input type="file" name="foto" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small">Catatan Tambahan</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Catatan kondisi barang (opsional)" value="{{ old('catatan') }}">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2">Kirim Permohonan Pinjam</button>
                    </div>
                </form>
            @endif

        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SWEETALERT2 UNTUK NOTIFIKASI POP-UP -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- POP-UP SWEETALERT JIKA DI-REJECT --}}
    @if($item && optional($item)->approval_status === 'REJECTED')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Permohonan Ditolak!',
                text: 'Maaf, permohonan peminjaman barang ini di-reject oleh Admin/PIC.',
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-4'
                }
            });
        });
    </script>
    @endif

    {{-- SCRIPT AUTO-REFRESH KETIKA STATUS BERUBAH DARI PENDING (MENJADI APPROVED ATAU REJECTED) --}}
    @if(isset($isPendingOnly) && $isPendingOnly)
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const borrowingId = "{{ $item->id ?? '' }}"; 

            if (borrowingId) {
                setInterval(function() {
                    fetch("{{ url('/borrowings/status-check') }}/" + borrowingId)
                        .then(response => response.json())
                        .then(res => {
                            const status = res.approval_status ? res.approval_status.toUpperCase() : '';
                            
                            // Jika disetujui, langsung redirect ke halaman pengembalian barang
                            if (status === 'APPROVED' || res.status === 'DIPINJAM') {
                                if (res.return_url) {
                                    window.location.href = res.return_url;
                                } else {
                                    window.location.href = "{{ url('/return') }}/" + borrowingId;
                                }
                            } 
                            // Jika ditolak, refresh halaman untuk menampilkan pemberitahuan ditolak
                            else if (status === 'REJECTED' || res.status === 'DITOLAK') {
                                location.reload();
                            }
                        })
                        .catch(error => console.error('Gagal mengecek status peminjaman:', error));
                }, 3000); // Cek perubahan status setiap 3 detik
            }
        });
    </script>
    @endif
</body>
</html>