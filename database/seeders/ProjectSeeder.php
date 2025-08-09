<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing projects first
        Project::truncate();

        // Create projects based on Projects.tsx static data
        $projects = [
            [
                'title' => 'Pembangunan Jembatan Desa Sukamaju',
                'description' => 'Proyek pembangunan jembatan beton untuk menghubungkan dua desa dengan panjang 35 meter menggunakan besi tulangan berkualitas tinggi.',
                'location' => 'Sukamaju, Cilacap',
                'date' => '2024',
                'weight' => '45.2 ton',
                'category' => 'infrastruktur',
                'external_image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop&crop=entropy&auto=format',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 1,
                'year' => 2024,
                'client' => 'Pemerintah Desa Sukamaju',
            ],
            [
                'title' => 'Renovasi Balai Desa Tanjungsari',
                'description' => 'Renovasi total balai desa dengan penambahan struktur baja ringan untuk atap dan perkuatan fondasi menggunakan besi beton.',
                'location' => 'Tanjungsari, Cilacap',
                'date' => '2024',
                'weight' => '28.5 ton',
                'category' => 'bangunan',
                'external_image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop&crop=entropy&auto=format',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 2,
                'year' => 2024,
                'client' => 'Pemerintah Desa Tanjungsari',
            ],
            [
                'title' => 'Proyek MCK Komunal Desa Karanganyar',
                'description' => 'Pembangunan 8 unit MCK komunal untuk melayani 150 kepala keluarga dengan sistem septik tank dan instalasi pipa berkualitas.',
                'location' => 'Karanganyar, Cilacap',
                'date' => '2023',
                'weight' => '12.8 ton',
                'category' => 'sanitasi',
                'external_image_url' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=800&h=600&fit=crop&crop=entropy&auto=format',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 3,
                'year' => 2023,
                'client' => 'Pemerintah Desa Karanganyar',
            ],
            [
                'title' => 'Jalan Kampung Betonisasi Desa Cipaku',
                'description' => 'Betonisasi jalan kampung sepanjang 800 meter dengan lebar 3.5 meter menggunakan besi wiremesh dan beton K-225.',
                'location' => 'Cipaku, Cilacap',
                'date' => '2023',
                'weight' => '67.3 ton',
                'category' => 'infrastruktur',
                'external_image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop&crop=entropy&auto=format',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 4,
                'year' => 2023,
                'client' => 'Pemerintah Desa Cipaku',
            ],
            [
                'title' => 'Pembangunan Posyandu Desa Margasari',
                'description' => 'Konstruksi bangunan posyandu 2 lantai dengan struktur beton bertulang dan atap baja ringan untuk pelayanan kesehatan masyarakat.',
                'location' => 'Margasari, Cilacap',
                'date' => '2023',
                'weight' => '18.9 ton',
                'category' => 'bangunan',
                'external_image_url' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&h=600&fit=crop&crop=entropy&auto=format',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 5,
                'year' => 2023,
                'client' => 'Pemerintah Desa Margasari',
            ],
            [
                'title' => 'Saluran Irigasi Desa Bojongmalang',
                'description' => 'Pembangunan saluran irigasi sepanjang 1.2 km dengan dinding beton bertulang untuk mengairi 50 hektar sawah petani.',
                'location' => 'Bojongmalang, Cilacap',
                'date' => '2023',
                'weight' => '34.7 ton',
                'category' => 'infrastruktur',
                'external_image_url' => 'https://images.unsplash.com/photo-1587052694737-a972e4186288?q=80&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 6,
                'year' => 2023,
                'client' => 'Pemerintah Desa Bojongmalang',
            ],
            [
                'title' => 'Masjid Al-Barokah Desa Kedawung',
                'description' => 'Pembangunan masjid dengan kapasitas 300 jamaah menggunakan struktur beton bertulang dan kubah baja dengan finishing berkualitas.',
                'location' => 'Kedawung, Cilacap',
                'date' => '2022',
                'weight' => '52.4 ton',
                'category' => 'bangunan',
                'external_image_url' => 'https://images.unsplash.com/photo-1512632578888-169bbbc64f33?q=80&w=2560&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 7,
                'year' => 2022,
                'client' => 'DKM Al-Barokah',
            ],
            [
                'title' => 'Embung Desa Sindangsari',
                'description' => 'Konstruksi embung untuk cadangan air dengan kapasitas 500 ribu liter menggunakan dinding beton bertulang dan sistem drainase.',
                'location' => 'Sindangsari, Cilacap',
                'date' => '2022',
                'weight' => '89.6 ton',
                'category' => 'infrastruktur',
                'external_image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop&crop=entropy&auto=format',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 8,
                'year' => 2022,
                'client' => 'Pemerintah Desa Sindangsari',
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        $this->command->info('Projects seeded successfully with ' . count($projects) . ' projects!');
    }
}
