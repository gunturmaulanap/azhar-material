<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $about = About::where('is_active', true)->first();

            return response()->json([
                'success' => true,
                'data' => $about,
                'message' => 'Data about berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|max:1024',
                'company_name' => 'required|string|max:255',
                'company_address' => 'nullable|string|max:255',
                'company_phone' => 'nullable|string|max:255',
                'company_email' => 'nullable|email|max:255',
                'company_website' => 'nullable|url|max:255',
                'vision' => 'nullable|string',
                'mission' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('about', 'public');
            }

            $about = About::create($data);

            return response()->json([
                'success' => true,
                'data' => $about,
                'message' => 'Data about berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $about,
                'message' => 'Data about berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|max:1024',
                'company_name' => 'required|string|max:255',
                'company_address' => 'nullable|string|max:255',
                'company_phone' => 'nullable|string|max:255',
                'company_email' => 'nullable|email|max:255',
                'company_website' => 'nullable|url|max:255',
                'vision' => 'nullable|string',
                'mission' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($about->image) {
                    Storage::disk('public')->delete($about->image);
                }
                $data['image'] = $request->file('image')->store('about', 'public');
            }

            $about->update($data);

            return response()->json([
                'success' => true,
                'data' => $about,
                'message' => 'Data about berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about): JsonResponse
    {
        try {
            // Delete image if exists
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }

            $about->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data about berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
