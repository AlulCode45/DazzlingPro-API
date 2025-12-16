<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $faqs = FAQ::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return $this->sendResponse($faqs, 'FAQs retrieved successfully.');
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
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ], [
            'question.required' => 'Question is required',
            'answer.required' => 'Answer is required',
            'order.min' => 'Order must be at least 0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'is_active' => $request->is_active ?? true,
            'sort_order' => $request->order ?? 0,
        ]);

        return $this->sendResponse($faq, 'FAQ created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param FAQ $faq
     * @return JsonResponse
     */
    public function show(FAQ $faq): JsonResponse
    {
        return $this->sendResponse($faq, 'FAQ retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param FAQ $faq
     * @return JsonResponse
     */
    public function update(Request $request, FAQ $faq): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'is_active' => $request->is_active ?? true,
            'sort_order' => $request->order ?? 0,
        ]);

        return $this->sendResponse($faq, 'FAQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FAQ $faq
     * @return JsonResponse
     */
    public function destroy(FAQ $faq): JsonResponse
    {
        $faq->delete();
        return $this->sendResponse([], 'FAQ deleted successfully.');
    }

    /**
     * Get active FAQs
     *
     * @return JsonResponse
     */
    public function getActive(): JsonResponse
    {
        $faqs = FAQ::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($faqs, 'Active FAQs retrieved successfully.');
    }

    /**
     * Get FAQs by category
     *
     * @param string $category
     * @return JsonResponse
     */
    public function getByCategory(string $category): JsonResponse
    {
        $faqs = FAQ::where('is_active', true)
            ->where('category', $category)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($faqs, "FAQs from {$category} category retrieved successfully.");
    }

    /**
     * Get all categories
     *
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $categories = FAQ::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return $this->sendResponse($categories, 'FAQ categories retrieved successfully.');
    }
}
