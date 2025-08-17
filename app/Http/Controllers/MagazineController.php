<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MagazineService;
use App\Models\Magazine;
use Illuminate\Support\Str;
use App\Http\Requests\StoreMagazineRequest;
use App\Http\Requests\UpdateMagazineRequest;
use Illuminate\Support\Facades\Storage;

class MagazineController extends Controller
{
    public function __construct(
        protected MagazineService $magazineService
    ) {}

    public function index(string $search)
    {
        if($search == 'null') {
            $search = null;
        }
        $magazines = $this->magazineService->getAll($search);
        return response()->json($magazines);
    }

    public function store(StoreMagazineRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();

        $slug = Str::slug($validated['title']);
        $count = 0;
        $baseSlug = $slug;

        while (\App\Models\Magazine::where('slug', $slug)->exists()) {
            $count++;
            $slug = $baseSlug . '-' . $count;
        }

        $validated['slug'] = $slug;

        $magazine = $this->magazineService->create($validated);

        return response()->json($magazine, 201);
    }

    public function show(string $slug)
    {
        $magazine = $this->magazineService->findBySlug($slug);
        return response()->json($magazine);
    }

    public function update(UpdateMagazineRequest $request, int $id)
    {
        $validated = $request->validated();


        $slug = Str::slug($validated['title']);
        $count = 0;
        $baseSlug = $slug;

        while (\App\Models\Magazine::where('slug', $slug)->exists()) {
            $count++;
            $slug = $baseSlug . '-' . $count;
        }

        $validated['slug'] = $slug;

        $updated = $this->magazineService->update($id, $validated);

        return response()->json($updated);
    }

    public function destroy(int $id)
    {
        return $this->magazineService->delete($id);

    }
}
