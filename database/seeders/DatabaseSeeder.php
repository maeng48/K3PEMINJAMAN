<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\K3Inspection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key check sementara & bersihkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        K3Inspection::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat User Login Standard
        User::create([
            'name' => 'Admin System',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'PIC K3',
            'username' => 'pic',
            'password' => Hash::make('password'),
            'role' => 'pic',
        ]);

        User::create([
            'name' => 'Team Leader',
            'username' => 'tl',
            'password' => Hash::make('password'),
            'role' => 'tl',
        ]);

        // Buat Data Awal K3 Inspection
        K3Inspection::create([
            'doc_number' => 'FR-K3L/ICON+/17-01',
            'tahun' => '2026',
            'tgl_dokumen' => date('Y-m-d'),
            'nama_engineer' => 'Made Tegar',
            'nama_tl' => 'Muhammad Hijrial Mukti',
            'nama_manager' => 'Danang Arfianto',
            'jumlah_data' => [
                'jumlah_1' => 14,
                'jumlah_2' => 1,
                'jumlah_3' => 14,
                'jumlah_4' => 3,
            ]
        ]);
    }
}