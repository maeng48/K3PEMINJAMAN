<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('k3_inspections', function (Blueprint $table) {
            $table->string('versi')->default('3')->after('doc_number');
            $table->string('tahun')->default('2026')->after('tgl_dokumen');
            $table->json('items_data')->nullable()->after('nama_manager');
            $table->string('foto_helmet')->nullable()->after('items_data');
            $table->string('foto_harness')->nullable()->after('foto_helmet');
            $table->string('foto_vest_biru')->nullable()->after('foto_harness');
            $table->string('foto_vest_merah')->nullable()->after('foto_vest_biru');
        });
    }

    public function down(): void
    {
        Schema::table('k3_inspections', function (Blueprint $table) {
            $table->dropColumn(['versi', 'tahun', 'items_data', 'foto_helmet', 'foto_harness', 'foto_vest_biru', 'foto_vest_merah']);
        });
    }
};