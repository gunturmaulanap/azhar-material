<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform to include background image URLs
        $heroSections->transform(function ($section) {
            $section->background_image_url = $section->background_image 
                ? asset('storage/' . $section->background_image) 
                : null;
            
            return $section;
        });

        return response()->json([
            'success' => true,
            'data' => $heroSections
        ]);
    }

    public function show($id)
    {
        $heroSection = HeroSection::find($id);

        if (!$heroSection) {
            return response()->json([
                'success' => false,
                'message' => 'Hero section not found'
            ], 404);
        }

        // Transform to include background image URL
        $heroSection->background_image_url = $heroSection->background_image 
            ? asset('storage/' . $heroSection->background_image) 
            : null;

        return response()->json([
            'success' => true,
            'data' => $heroSection
        ]);
    }

    public function active()
    {
        $heroSection = HeroSection::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$heroSection) {
            return response()->json([
                'success' => false,
                'message' => 'No active hero section found'
            ], 404);
        }

        // Transform to include background image URL
        $heroSection->background_image_url = $heroSection->background_image 
            ? asset('storage/' . $heroSection->background_image) 
            : null;

        return response()->json([
            'success' => true,
            'data' => $heroSection
        ]);
    }
} 