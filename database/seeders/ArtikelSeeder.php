<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artikel')->insert([
            [
                'judul' => 'Makanan sehat',
                'gambar_artikel' => 'nfYhckwyJ9TLRC7o6D929XDjiKtFBzSTQjGYi7KD.jpg',
                'isi_artikel' => 'Makanan yang mengandung nutrisi lengkap dan seimbang, termasuk karbohidrat, protein, lemak sehat, vitamin, mineral, serta serat. Konsumsi makanan sehat sangat penting untuk menjaga kesehatan tubuh, meningkatkan daya tahan tubuh, serta mencegah berbagai penyakit.',
                'tanggal' => '2025-03-07',
                'created_at' => Carbon::parse('2025-03-07 08:08:36'),
                'updated_at' => Carbon::parse('2025-03-07 08:08:36'),
            ],
            [
                'judul' => 'Stunting',
                'gambar_artikel' => 'vYyN7WOFvTwymhfHByXRaHzaUVyG9xbTHiCbtf8U.jpg',
                'isi_artikel' => 'Kondisi gagal tumbuh pada anak akibat kekurangan gizi kronis yang terjadi dalam 1.000 hari pertama kehidupan, yaitu sejak masa kehamilan hingga anak berusia 2 tahun. Kondisi ini ditandai dengan tinggi badan anak yang lebih pendek dari standar usianya.',
                'tanggal' => '2025-03-07',
                'created_at' => Carbon::parse('2025-03-07 08:09:37'),
                'updated_at' => Carbon::parse('2025-03-07 08:09:37'),
            ],
            [
                'judul' => 'Vitamin',
                'gambar_artikel' => 'w6UIRx5AuP85fr6r1nVcHUos9lTDqUXNEeP6HjTv.jpg',
                'isi_artikel' => 'Bentuk kapsul merah dan biru umumnya mengacu pada suplemen yang digunakan untuk memenuhi kebutuhan nutrisi tertentu, terutama pada ibu hamil, balita, atau kelompok rentan lainnya. Dalam program kesehatan di Indonesia, vitamin ini sering dikaitkan dengan suplemen yang diberikan dalam program Posyandu dan Puskesmas.',
                'tanggal' => '2025-03-07',
                'created_at' => Carbon::parse('2025-03-07 08:14:56'),
                'updated_at' => Carbon::parse('2025-03-07 08:14:56'),
            ],
            [
                'judul' => 'Perkembangan Anak',
                'gambar_artikel' => 'Xn16CASxBiNcle9ArN5c2K9jAA3v6OYJtF5y2PMO.jpg',
                'isi_artikel' => 'Proses pertumbuhan dan perubahan yang terjadi pada anak sejak lahir hingga dewasa. Proses ini mencakup perkembangan fisik, kognitif, sosial, emosional, dan bahasa yang dipengaruhi oleh faktor genetik, lingkungan, serta pola asuh.',
                'tanggal' => '2025-03-07',
                'created_at' => Carbon::parse('2025-03-07 08:22:27'),
                'updated_at' => Carbon::parse('2025-03-07 08:22:27'),
            ],
            [
                'judul' => 'gizi',
                'gambar_artikel' => 'y1LRPBxJh7V4Px4I2hKLwqiDjHZRq77GfvouyUYt.png',
                'isi_artikel' => 'Gizi anak merujuk pada pola makan dan nutrisi yang dibutuhkan oleh anak-anak untuk mendukung pertumbuhan dan perkembangan mereka. Gizi yang baik sangat penting bagi anak, karena pada usia ini tubuh mereka tumbuh dengan cepat, baik secara fisik maupun mental. Gizi yang cukup membantu anak untuk memiliki energi, meningkatkan daya tahan tubuh, serta mendukung perkembangan otak.',
                'tanggal' => '2025-03-09',
                'created_at' => Carbon::parse('2025-03-07 22:40:06'),
                'updated_at' => Carbon::parse('2025-03-07 22:40:06'),
            ],
        ]);
    }
}
