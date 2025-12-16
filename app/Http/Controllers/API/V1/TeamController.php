<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $teams = Team::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return $this->sendResponse($teams, 'Team members retrieved successfully.');
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
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo_url' => 'nullable|url|max:500',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required|string|max:50',
            'social_links.*.url' => 'required|url|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Name is required',
            'position.required' => 'Position is required',
            'email.email' => 'Email must be a valid email address',
            'photo_url.url' => 'Photo URL must be a valid URL',
            'order.min' => 'Order must be at least 0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        // Handle social links from array to individual fields
        $socialLinks = $request->social_links ?? [];
        $socialFields = [
            'linkedin_url' => null,
            'instagram_url' => null,
            'facebook_url' => null,
            'twitter_url' => null,
        ];

        foreach ($socialLinks as $link) {
            $platform = strtolower($link['platform']);
            if (isset($socialFields[$platform . '_url'])) {
                $socialFields[$platform . '_url'] = $link['url'];
            }
        }

        $team = Team::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'position' => $request->position,
            'department' => $request->department,
            'bio' => $request->bio,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo_url' => $request->photo_url,
            'linkedin_url' => $socialFields['linkedin_url'],
            'instagram_url' => $socialFields['instagram_url'],
            'facebook_url' => $socialFields['facebook_url'],
            'twitter_url' => $socialFields['twitter_url'],
            'skills' => $request->skills,
            'is_active' => $request->is_active ?? true,
            'is_featured' => $request->is_featured ?? false,
            'sort_order' => $request->order ?? 0,
        ]);

        return $this->sendResponse($team, 'Team member created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function show(Team $team): JsonResponse
    {
        return $this->sendResponse($team, 'Team member retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Team $team
     * @return JsonResponse
     */
    public function update(Request $request, Team $team): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo_url' => 'nullable|url|max:500',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required|string|max:50',
            'social_links.*.url' => 'required|url|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', $validator->errors());
        }

        // Handle social links from array to individual fields
      $socialLinks = $request->social_links ?? [];
      $socialFields = [
          'linkedin_url' => null,
          'instagram_url' => null,
          'facebook_url' => null,
          'twitter_url' => null,
      ];

      foreach ($socialLinks as $link) {
          $platform = strtolower($link['platform']);
          if (isset($socialFields[$platform . '_url'])) {
              $socialFields[$platform . '_url'] = $link['url'];
          }
      }

      $updateData = $request->except(['social_links', 'skills']);
      $updateData = array_merge($updateData, $socialFields);

      // Update slug if name changed
      if ($team->name !== $request->name) {
          $updateData['slug'] = Str::slug($request->name);
      }

      $team->update($updateData);

      // Handle skills separately since it needs casting
      if ($request->has('skills')) {
          $team->skills = $request->skills;
          $team->save();
      }

        return $this->sendResponse($team, 'Team member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function destroy(Team $team): JsonResponse
    {
        $team->delete();
        return $this->sendResponse([], 'Team member deleted successfully.');
    }

    /**
     * Get active team members
     *
     * @return JsonResponse
     */
    public function getActive(): JsonResponse
    {
        $teams = Team::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($teams, 'Active team members retrieved successfully.');
    }

    /**
     * Get featured team members
     *
     * @return JsonResponse
     */
    public function getFeatured(): JsonResponse
    {
        $teams = Team::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($teams, 'Featured team members retrieved successfully.');
    }

    /**
     * Get team members by department
     *
     * @param string $department
     * @return JsonResponse
     */
    public function getByDepartment(string $department): JsonResponse
    {
        $teams = Team::where('is_active', true)
            ->where('department', $department)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($teams, "Team members from {$department} department retrieved successfully.");
    }
}
