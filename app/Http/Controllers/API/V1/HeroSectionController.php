<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class HeroSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $heroSections = HeroSection::orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();
        return $this->sendResponse($heroSections, 'Hero sections retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_url' => 'nullable|url|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|url|max:500',
            'background_image_url' => 'nullable|url|max:500',
            'background_video_url' => 'nullable|url|max:500',
            'overlay_opacity' => 'nullable|numeric|min:0|max:1',
            'text_color' => 'nullable|string|max:7', // hex color
            'button_style' => 'nullable|string|max:50',
            'animation_type' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Title is required',
            'primary_button_url.url' => 'Primary button URL must be a valid URL',
            'secondary_button_url.url' => 'Secondary button URL must be a valid URL',
            'background_image_url.url' => 'Background image URL must be a valid URL',
            'background_video_url.url' => 'Background video URL must be a valid URL',
            'overlay_opacity.min' => 'Overlay opacity must be at least 0',
            'overlay_opacity.max' => 'Overlay opacity must not exceed 1',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $heroSection = HeroSection::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'primary_button_text' => $request->primary_button_text,
            'primary_button_url' => $request->primary_button_url,
            'secondary_button_text' => $request->secondary_button_text,
            'secondary_button_url' => $request->secondary_button_url,
            'background_image_url' => $request->background_image_url,
            'background_video_url' => $request->background_video_url,
            'overlay_opacity' => $request->overlay_opacity ?? 0.5,
            'text_color' => $request->text_color ?? '#ffffff',
            'button_style' => $request->button_style ?? 'default',
            'animation_type' => $request->animation_type ?? 'fade',
            'is_active' => $request->is_active ?? true,
            'order' => $request->order ?? 0,
        ]);

        return $this->sendResponse($heroSection, 'Hero section created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param HeroSection $heroSection
     * @return JsonResponse
     */
    public function show(HeroSection $heroSection): JsonResponse
    {
        return $this->sendResponse($heroSection, 'Hero section retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param HeroSection $heroSection
     * @return JsonResponse
     */
    public function update(Request $request, HeroSection $heroSection): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_url' => 'nullable|url|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|url|max:500',
            'background_image_url' => 'nullable|url|max:500',
            'background_video_url' => 'nullable|url|max:500',
            'overlay_opacity' => 'nullable|numeric|min:0|max:1',
            'text_color' => 'nullable|string|max:7',
            'button_style' => 'nullable|string|max:50',
            'animation_type' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $heroSection->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'primary_button_text' => $request->primary_button_text,
            'primary_button_url' => $request->primary_button_url,
            'secondary_button_text' => $request->secondary_button_text,
            'secondary_button_url' => $request->secondary_button_url,
            'background_image_url' => $request->background_image_url,
            'background_video_url' => $request->background_video_url,
            'overlay_opacity' => $request->overlay_opacity ?? 0.5,
            'text_color' => $request->text_color ?? '#ffffff',
            'button_style' => $request->button_style ?? 'default',
            'animation_type' => $request->animation_type ?? 'fade',
            'is_active' => $request->is_active ?? true,
            'order' => $request->order ?? 0,
        ]);

        return $this->sendResponse($heroSection, 'Hero section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HeroSection $heroSection
     * @return JsonResponse
     */
    public function destroy(HeroSection $heroSection): JsonResponse
    {
        $heroSection->delete();
        return $this->sendResponse([], 'Hero section deleted successfully.');
    }

    /**
     * Get active hero sections
     *
     * @return JsonResponse
     */
    public function getActive(): JsonResponse
    {
        $heroSections = HeroSection::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($heroSections, 'Active hero sections retrieved successfully.');
    }
}
