<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('k3_inspections', function (Blueprint $table) {
        $table->id();
        $table->string('doc_number')->default('FR-K3L/ICON+/17-01');
        $table->string('tahun')->default('2026');
        $table->string('tgl_dokumen')->nullable();
        $table->string('nama_engineer')->default('Made Tegar');
        $table->string('nama_tl')->default('Muhammad Hijrial Mukti');
        $table->string('nama_manager')->default('Danang Arfianto');
        $table->text('ttd_engineer')->nullable();
        $table->text('ttd_tl')->nullable();
        $table->text('ttd_manager')->nullable();
        $table->boolean('is_engineer_signed')->default(false);
        $table->boolean('is_tl_signed')->default(false);
        $table->boolean('is_manager_signed')->default(false);
        $table->text('foto_helmet')->nullable();
        $table->text('foto_harness')->nullable();
        $table->text('foto_vest_biru')->nullable();
        $table->text('foto_vest_merah')->nullable();
        $table->json('jumlah_data')->nullable();
        $table->json('checklist_data')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k3_inspections');
    }
};
