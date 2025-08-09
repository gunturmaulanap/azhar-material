<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Menampilkan daftar proyek yang telah dipublikasikan dengan opsi filter.
     * Dapat difilter berdasarkan 'featured' dan diurutkan menggunakan scope.
     *
     * GET /api/projects?status=published&featured=true
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Menggunakan scope Published jika ada di query
        if ($request->has('status') && $request->status === 'published') {
            $query->published();
        }

        // Menggunakan scope Featured jika ada di query
        if ($request->has('featured') && filter_var($request->featured, FILTER_VALIDATE_BOOLEAN)) {
            $query->featured();
        }

        // Menggunakan scope Ordered untuk pengurutan default
        $projects = $query->ordered()->paginate(15);

        return response()->json($projects);
    }

    /**
     * Menampilkan detail satu proyek.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        return response()->json($project);
    }

    /**
     * Menyimpan proyek baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file image
            'external_image_url' => 'nullable|url',
            'client' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'weight' => 'nullable|integer',
            'year' => 'nullable|integer',
            'date' => 'nullable|date',
            'status' => 'required|string|in:draft,published',
            'featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('image');

        // Mengunggah file image jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($data);

        return response()->json($project, 201); // 201 Created
    }

    /**
     * Memperbarui proyek yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'category' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'external_image_url' => 'nullable|url',
            'client' => 'string|max:255',
            'location' => 'string|max:255',
            'weight' => 'integer',
            'year' => 'integer',
            'date' => 'date',
            'status' => 'string|in:draft,published',
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('image');

        // Menghapus image lama dan mengunggah yang baru jika ada
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);

        return response()->json($project);
    }

    /**
     * Menghapus proyek dari database (soft delete).
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
