<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code_id');
            $table->string('peminjam_nama');
            $table->string('keperluan')->nullable();
            $table->string('divisi')->nullable();
            $table->string('kategori_barang')->nullable();
            $table->string('merk_barang');
            $table->string('tgl_pinjam');
            $table->text('foto_pinjam');
            $table->text('catatan')->nullable();
            $table->string('tgl_kembali')->nullable();
            $table->text('foto_kembali')->nullable();
            $table->enum('status', ['DIPINJAM', 'DIKEMBALIKAN'])->default('DIPINJAM');
            $table->enum('approval_status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};