<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $teams = Team::where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->get();

            return response()->json([
                'success' => true,
                'data' => $teams,
                'message' => 'Data tim berhasil diambil'
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
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'description' => 'nullable|string',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
                'linkedin' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'image' => 'nullable|image|max:1024',
                'is_active' => 'boolean',
                'order' => 'integer|min:0'
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
                $data['image'] = $request->file('image')->store('teams', 'public');
            }

            $team = Team::create($data);

            return response()->json([
                'success' => true,
                'data' => $team,
                'message' => 'Data tim berhasil ditambahkan'
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
    public function show(Team $team): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $team,
                'message' => 'Data tim berhasil diambil'
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
    public function update(Request $request, Team $team): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'description' => 'nullable|string',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
                'linkedin' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'image' => 'nullable|image|max:1024',
                'is_active' => 'boolean',
                'order' => 'integer|min:0'
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
                if ($team->image) {
                    Storage::disk('public')->delete($team->image);
                }
                $data['image'] = $request->file('image')->store('teams', 'public');
            }

            $team->update($data);

            return response()->json([
                'success' => true,
                'data' => $team,
                'message' => 'Data tim berhasil diperbarui'
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
    public function destroy(Team $team): JsonResponse
    {
        try {
            // Delete image if exists
            if ($team->image) {
                Storage::disk('public')->delete($team->image);
            }

            $team->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data tim berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
