<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CompanyInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $companyInfo = CompanyInformation::orderBy('created_at', 'desc')->get();
        return $this->sendResponse($companyInfo, 'Company information retrieved successfully.');
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
            'company_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'website_url' => 'nullable|url|max:255',
            'logo_url' => 'nullable|url|max:500',
            'favicon_url' => 'nullable|url|max:500',
            'social_media' => 'nullable|array',
            'social_media.*.platform' => 'required|string|max:50',
            'social_media.*.url' => 'required|url|max:255',
            'operating_hours' => 'nullable|array',
            'google_maps_url' => 'nullable|url|max:500',
            'business_registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'about_us' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'core_values' => 'nullable|string',
            'seo_meta' => 'nullable|array',
            'seo_meta.title' => 'nullable|string|max:255',
            'seo_meta.description' => 'nullable|string|max:500',
            'seo_meta.keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'company_name.string' => 'Company name must be a string',
            'company_name.max' => 'Company name may not be greater than 255 characters',
            'email.email' => 'Email must be a valid email address',
            'website_url.url' => 'Website URL must be a valid URL',
            'logo_url.url' => 'Logo URL must be a valid URL',
            'favicon_url.url' => 'Favicon URL must be a valid URL',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $companyInfo = CompanyInformation::create([
            'company_name' => $request->company_name,
            'tagline' => $request->tagline,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'country' => $request->country ?? 'Indonesia',
            'website_url' => $request->website_url,
            'logo_url' => $request->logo_url,
            'favicon_url' => $request->favicon_url,
            'social_media' => $request->social_media ? json_encode($request->social_media) : null,
            'operating_hours' => $request->operating_hours ? json_encode($request->operating_hours) : null,
            'google_maps_url' => $request->google_maps_url,
            'business_registration_number' => $request->business_registration_number,
            'tax_id' => $request->tax_id,
            'bank_account' => $request->bank_account,
            'bank_name' => $request->bank_name,
            'about_us' => $request->about_us,
            'mission' => $request->mission,
            'vision' => $request->vision,
            'core_values' => $request->core_values,
            'seo_meta' => $request->seo_meta ? json_encode($request->seo_meta) : null,
            'is_active' => $request->is_active ?? true,
        ]);

        return $this->sendResponse($companyInfo, 'Company information created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param CompanyInformation $companyInformation
     * @return JsonResponse
     */
    public function show(CompanyInformation $companyInformation): JsonResponse
    {
        return $this->sendResponse($companyInformation, 'Company information retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CompanyInformation $companyInformation
     * @return JsonResponse
     */
    public function update(Request $request, CompanyInformation $companyInformation): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'website_url' => 'nullable|url|max:255',
            'logo_url' => 'nullable|url|max:500',
            'favicon_url' => 'nullable|url|max:500',
            'social_media' => 'nullable|array',
            'social_media.*.platform' => 'required|string|max:50',
            'social_media.*.url' => 'required|url|max:255',
            'operating_hours' => 'nullable|array',
            'google_maps_url' => 'nullable|url|max:500',
            'business_registration_number' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'about_us' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'core_values' => 'nullable|string',
            'seo_meta' => 'nullable|array',
            'seo_meta.title' => 'nullable|string|max:255',
            'seo_meta.description' => 'nullable|string|max:500',
            'seo_meta.keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validasi.', $validator->errors(), 422);
        }

        $companyInformation->update($request->except(['social_media', 'operating_hours', 'seo_meta']));

        if ($request->has('social_media')) {
            $companyInformation->social_media = $request->social_media ? json_encode($request->social_media) : null;
        }

        if ($request->has('operating_hours')) {
            $companyInformation->operating_hours = $request->operating_hours ? json_encode($request->operating_hours) : null;
        }

        if ($request->has('seo_meta')) {
            $companyInformation->seo_meta = $request->seo_meta ? json_encode($request->seo_meta) : null;
        }

        $companyInformation->save();

        return $this->sendResponse($companyInformation, 'Company information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CompanyInformation $companyInformation
     * @return JsonResponse
     */
    public function destroy(CompanyInformation $companyInformation): JsonResponse
    {
        $companyInformation->delete();
        return $this->sendResponse([], 'Company information deleted successfully.');
    }

    /**
     * Get active company information
     *
     * @return JsonResponse
     */
    public function getActive(): JsonResponse
    {
        $companyInfo = CompanyInformation::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        return $this->sendResponse($companyInfo, 'Active company information retrieved successfully.');
    }
}