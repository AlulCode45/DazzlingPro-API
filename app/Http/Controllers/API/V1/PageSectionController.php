<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\PageSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageSectionController extends Controller
{
    /**
     * Display a paginated listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $sections = PageSection::query()
            ->when($request->filled('key'), function ($query) use ($request) {
                $query->where('key', $request->key);
            })
            ->when($request->filled('is_active'), function ($query) use ($request) {
                $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            })
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return $this->sendResponseWithPagination($sections, 'Page sections retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:191|unique:page_sections,key',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), 422);
        }

        $section = PageSection::create([
            'key' => $request->key,
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return $this->sendResponse($section, 'Page section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PageSection $pageSection): JsonResponse
    {
        return $this->sendResponse($pageSection, 'Page section retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PageSection $pageSection): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:191|unique:page_sections,key,' . $pageSection->id,
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors(), 422);
        }

        $pageSection->update([
            'key' => $request->key,
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active', $pageSection->is_active),
        ]);

        return $this->sendResponse($pageSection, 'Page section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PageSection $pageSection): JsonResponse
    {
        $pageSection->delete();
        return $this->sendResponse([], 'Page section deleted successfully.');
    }

    /**
     * Public: get active section by key.
     */
    public function getByKey(string $key): JsonResponse
    {
        $section = PageSection::where('key', $key)
            ->where('is_active', true)
            ->first();

        if (!$section) {
            return $this->sendError('Page section not found.', [], 404);
        }

        return $this->sendResponse($section, 'Page section retrieved successfully.');
    }

    /**
     * Public: list all active sections.
     */
    public function publicIndex(): JsonResponse
    {
        $sections = PageSection::where('is_active', true)
            ->orderByDesc('created_at')
            ->get();

        return $this->sendResponse($sections, 'Active page sections retrieved successfully.');
    }
}
