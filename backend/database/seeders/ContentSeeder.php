<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeroSection;
use App\Models\Team;
use App\Models\Service;
use App\Models\About;
use App\Models\Contact;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Sections
        HeroSection::create([
            'title' => 'Selamat Datang di Azhar Material',
            'subtitle' => 'Solusi Material Terpercaya',
            'description' => 'Kami menyediakan berbagai jenis material berkualitas tinggi untuk kebutuhan konstruksi dan industri Anda.',
            'button_text' => 'Pelajari Lebih Lanjut',
            'button_url' => '#about',
            'is_active' => true
        ]);

        // Teams
        Team::create([
            'name' => 'Ahmad Azhar',
            'position' => 'CEO & Founder',
            'description' => 'Pemimpin perusahaan dengan pengalaman lebih dari 10 tahun di industri material.',
            'email' => 'ahmad@azharmaterial.com',
            'phone' => '+62 812 3456 7890',
            'linkedin' => 'https://linkedin.com/in/ahmad-azhar',
            'is_active' => true,
            'order' => 1
        ]);

        Team::create([
            'name' => 'Sarah Putri',
            'position' => 'Operations Manager',
            'description' => 'Mengelola operasional perusahaan dan memastikan layanan terbaik untuk pelanggan.',
            'email' => 'sarah@azharmaterial.com',
            'phone' => '+62 812 3456 7891',
            'linkedin' => 'https://linkedin.com/in/sarah-putri',
            'is_active' => true,
            'order' => 2
        ]);

        // Services
        Service::create([
            'name' => 'Distribusi Material',
            'description' => 'Layanan distribusi material konstruksi ke seluruh Indonesia dengan jaringan logistik yang luas.',
            'icon' => 'fas fa-truck',
            'button_text' => 'Hubungi Kami',
            'button_link' => '#contact',
            'is_active' => true,
            'order' => 1
        ]);

        Service::create([
            'name' => 'Konsultasi Material',
            'description' => 'Tim ahli kami siap memberikan konsultasi untuk pemilihan material yang tepat sesuai kebutuhan.',
            'icon' => 'fas fa-users',
            'button_text' => 'Konsultasi Gratis',
            'button_link' => '#contact',
            'is_active' => true,
            'order' => 2
        ]);

        Service::create([
            'name' => 'Quality Assurance',
            'description' => 'Kami menjamin kualitas material dengan sertifikasi dan pengujian yang ketat.',
            'icon' => 'fas fa-certificate',
            'button_text' => 'Lihat Sertifikat',
            'button_link' => '#about',
            'is_active' => true,
            'order' => 3
        ]);

        // About
        About::create([
            'title' => 'Tentang Azhar Material',
            'description' => 'Azhar Material adalah perusahaan terkemuka dalam distribusi material konstruksi berkualitas tinggi. Didirikan pada tahun 2015, kami telah melayani ribuan proyek konstruksi di seluruh Indonesia dengan komitmen untuk menyediakan material terbaik dengan harga yang kompetitif.',
            'company_name' => 'PT. Azhar Material Indonesia',
            'company_address' => 'Jl. Industri No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'company_phone' => '+62 21 1234 5678',
            'company_email' => 'info@azharmaterial.com',
            'company_website' => 'https://www.azharmaterial.com',
            'vision' => 'Menjadi pemimpin pasar dalam distribusi material konstruksi berkualitas tinggi di Indonesia.',
            'mission' => 'Menyediakan material konstruksi berkualitas tinggi dengan layanan terbaik dan harga yang kompetitif untuk mendukung pembangunan infrastruktur Indonesia.',
            'is_active' => true
        ]);

        // Contact
        Contact::create([
            'title' => 'Hubungi Kami',
            'description' => 'Tim kami siap membantu Anda. Silakan hubungi kami untuk informasi lebih lanjut.',
            'address' => 'Jl. Industri No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'phone' => '+62 21 1234 5678',
            'email' => 'info@azharmaterial.com',
            'website' => 'https://www.azharmaterial.com',
            'facebook' => 'https://facebook.com/azharmaterial',
            'twitter' => 'https://twitter.com/azharmaterial',
            'instagram' => 'https://instagram.com/azharmaterial',
            'linkedin' => 'https://linkedin.com/company/azhar-material',
            'youtube' => 'https://youtube.com/azharmaterial',
            'is_active' => true
        ]);
    }
}
