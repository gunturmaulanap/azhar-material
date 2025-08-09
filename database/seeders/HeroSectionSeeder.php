<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HeroSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing hero sections first
        HeroSection::truncate();

        // Copy video file to storage if it doesn't exist
        $videoPath = 'hero-sections/videos/azhar.mp4';
        $sourceVideoPath = public_path('videos/azhar.mp4');
        $destinationVideoPath = storage_path('app/public/' . $videoPath);

        // Create directories if they don't exist
        $videoDir = dirname($destinationVideoPath);
        if (!File::exists($videoDir)) {
            File::makeDirectory($videoDir, 0755, true);
        }

        // Copy video file to storage if it doesn't exist and source file exists
        if (File::exists($sourceVideoPath) && !File::exists($destinationVideoPath)) {
            File::copy($sourceVideoPath, $destinationVideoPath);
        }

        // Create hero section based on Home.tsx static data
        HeroSection::create([
            'title' => 'Build with Quality.',
            'subtitle' => 'Build with Azhar.',
            'description' => 'Supplying trusted construction materials to fuel your vision.',
            'button_text' => 'View Products',
            'button_url' => '#product-preview',
            'background_video' => $videoPath, // Reference to the video in storage
            'background_type' => 'video',
            'is_active' => true,
        ]);

        $this->command->info('Hero section seeded successfully with video background!');
    }
}
