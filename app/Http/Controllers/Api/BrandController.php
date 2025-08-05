<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $brands = Brand::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();

            // Transform brands to include image status
            $brands->transform(function ($brand) {
                $brand->logo_url = $brand->logo 
                    ? asset('storage/' . $brand->logo) 
                    : null;
                $brand->has_image = !empty($brand->logo);
                $brand->image_message = $brand->has_image 
                    ? 'Logo tersedia' 
                    : 'Belum ada logo/gambar untuk brand ini';
                
                return $brand;
            });

            return response()->json([
                'success' => true,
                'data' => $brands,
                'message' => 'Data brand berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $brand = Brand::find($id);

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand tidak ditemukan'
                ], 404);
            }

            // Transform brand to include image status
            $brand->logo_url = $brand->logo 
                ? asset('storage/' . $brand->logo) 
                : null;
            $brand->has_image = !empty($brand->logo);
            $brand->image_message = $brand->has_image 
                ? 'Logo tersedia' 
                : 'Belum ada logo/gambar untuk brand ini';

            return response()->json([
                'success' => true,
                'data' => $brand,
                'message' => 'Data brand berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function active(): JsonResponse
    {
        try {
            $brands = Brand::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();

            // Transform brands to include image status
            $brands->transform(function ($brand) {
                $brand->logo_url = $brand->logo 
                    ? asset('storage/' . $brand->logo) 
                    : null;
                $brand->has_image = !empty($brand->logo);
                $brand->image_message = $brand->has_image 
                    ? 'Logo tersedia' 
                    : 'Belum ada logo/gambar untuk brand ini';
                
                return $brand;
            });

            return response()->json([
                'success' => true,
                'data' => $brands,
                'message' => 'Data brand aktif berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update brand content fields (logo, website_url, is_active)
     */
    public function updateContent(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'logo' => 'nullable|image|max:1024',
                'website_url' => 'nullable|url|max:255',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $brand = Brand::find($id);

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand tidak ditemukan'
                ], 404);
            }

            $data = $validator->validated();

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo);
                }
                $data['logo'] = $request->file('logo')->store('brands', 'public');
            }

            $brand->update($data);

            return response()->json([
                'success' => true,
                'data' => $brand,
                'message' => 'Data brand berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
