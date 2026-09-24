<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $table = 'borrowings';

   protected $fillable = [
        'qr_code_id',
        'peminjam_nama',
        'divisi',
        'kategori_barang',
        'merk_barang',
        'keperluan',
        'catatan',
        'tgl_pinjam',
        'foto_pinjam',
        'tgl_kembali',
        'foto_kembali',
        'approval_status',
        'status',
    ];
}