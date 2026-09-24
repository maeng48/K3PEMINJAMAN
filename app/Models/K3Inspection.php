<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class K3Inspection extends Model
{
    use HasFactory;

    protected $table = 'k3_inspections';

    protected $fillable = [
        'doc_number',
        'versi',
        'tahun',
        'tgl_dokumen',

        // Nama pejabat
        'nama_engineer',
        'nama_tl',
        'nama_manager',

        // TTD lama jika masih digunakan
        'ttd_engineer',
        'ttd_tl',
        'ttd_manager',

        // Path file tanda tangan
        'engineer_signature_path',
        'tl_signature_path',
        'manager_signature_path',

        // Waktu tanda tangan
        'engineer_signed_at',
        'tl_signed_at',
        'manager_signed_at',

        // Status tanda tangan
        'is_engineer_signed',
        'is_tl_signed',
        'is_manager_signed',

        // Foto APD
        'foto_helmet',
        'foto_harness',
        'foto_vest_biru',
        'foto_vest_merah',

        // Data K3
        'jumlah_data',
        'checklist_data',
        'items_data',
    ];

    protected $casts = [
        'jumlah_data' => 'array',
        'checklist_data' => 'array',

        'is_engineer_signed' => 'boolean',
        'is_tl_signed' => 'boolean',
        'is_manager_signed' => 'boolean',

        'engineer_signed_at' => 'datetime',
        'tl_signed_at' => 'datetime',
        'manager_signed_at' => 'datetime',
    ];
}