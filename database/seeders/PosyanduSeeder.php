<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosyanduSeeder extends Seeder
{
    public function run()
    {

        DB::table('data_orangtua')->insert([
            [
                'nik'      => '3201010101010001',
                'nama_ibu' => 'Siti Aminah',
                'no_telp'  => '081234567890',
                'alamat'   => 'Jl. Melati No. 12, Jember',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // 2. data_anak
        DB::table('data_anak')->insert([
            [
                'nik'         => '3201010101010002',
                'parent_nik'  => '3201010101010001',
                'nama_anak'   => 'Budi Santoso',
                'tempat_lahir'=> 'Jember',
                'tanggal_lahir'=> '2022-01-15',
                'usia'        => '2 tahun',
                'jenis_kelamin'=> 'Laki-laki',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);

        // 3. pengguna
        DB::table('pengguna')->insert([
            [
                'nik'        => '3201010101010001',
                'password'   => bcrypt('secret123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // 4. petugas
        DB::table('petugas')->insert([
            [
                'username'   => 'petugas1',
                'password'   => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // 5. jadwal
        DB::table('jadwal')->insert([
            [
                'tanggal'    => Carbon::now()->addWeek()->toDateString(),
                'waktu'      => '09:00:00',
                'petugas_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // 6. imunisasi
        DB::table('imunisasi')->insert([
            [
                'anak_nik'         => '3201010101010002',
                'tanggal_imunisasi'=> '2024-04-01',
                'vaksin'           => 'BCG',
                'status'           => 'Selesai',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
        ]);

        // 7. perkembangan_anak
        DB::table('perkembangan_anak')->insert([
            [
                'anak_nik'        => '3201010101010002',
                'tanggal'         => '2024-03-15',
                'berat_badan'     => 12.50,
                'ket_berat_badan' => 'Normal',
                'tinggi_badan'    => 85.00,
                'ket_tinggi_badan'=> 'Normal',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
        ]);

        // 8. stunting
        DB::table('stunting')->insert([
            [
                'anak_nik'    => '3201010101010002',
                'tanggal'     => '2024-03-15',
                'usia'        => '2 tahun',
                'berat_badan' => 12.50,
                'tinggi_badan'=> 85.00,
                'lingkar_kepala'=> 45.00,
                'catatan'     => 'Normal',
                'status'      => 'Tidak Stunting',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ]);

        // 9. vitamin
        DB::table('vitamin')->insert([
            [
                'anak_nik'     => '3201010101010002',
                'tanggal_vitamin'=> '2024-04-10',
                'vitamin'      => 'Vitamin A',
                'status'       => 'Selesai',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ]);

        // 10. artikel
        DB::table('artikel')->insert([
            [
                'judul'         => 'Tips Perawatan Anak Balita',
                'gambar_artikel'=> 'tips-balita.jpg',
                'isi_artikel'   => 'Beberapa tips merawat balita agar tumbuh sehat...',
                'tanggal'       => Carbon::now()->toDateString(),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
