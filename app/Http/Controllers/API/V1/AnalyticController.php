<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Services\Contracts\AnalyticServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    private AnalyticServiceInterface $analyticService;

    public function __construct(AnalyticServiceInterface $analyticService)
    {
        $this->analyticService = $analyticService;
    }

    /**
     * Track a page view
     */
    public function trackPageView(Request $request): JsonResponse
    {
        $data = [
            'page_url' => $request->input('page_url'),
            'page_title' => $request->input('page_title'),
            'referrer' => $request->input('referrer'),
            'user_agent' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'session_id' => $request->input('session_id'),
            'metadata' => $request->input('metadata', []),
        ];

        $tracked = $this->analyticService->trackPageView($data);

        if ($tracked) {
            return $this->sendResponse([], 'Page view tracked successfully.');
        }

        return $this->sendError('Failed to track page view.', [], 500);
    }

    /**
     * Track a custom event
     */
    public function trackEvent(Request $request): JsonResponse
    {
        $eventType = $request->input('event_type');
        $data = [
            'page_url' => $request->input('page_url'),
            'page_title' => $request->input('page_title'),
            'referrer' => $request->input('referrer'),
            'user_agent' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'session_id' => $request->input('session_id'),
            'metadata' => $request->input('metadata', []),
        ];

        $tracked = $this->analyticService->trackEvent($eventType, $data);

        if ($tracked) {
            return $this->sendResponse([], 'Event tracked successfully.');
        }

        return $this->sendError('Failed to track event.', [], 500);
    }

    /**
     * Get dashboard analytics stats
     */
    public function getDashboardStats(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $stats = $this->analyticService->getDashboardStats($startDate, $endDate);

        return $this->sendResponse($stats, 'Dashboard stats retrieved successfully.');
    }

    /**
     * Get page views data
     */
    public function getPageViews(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->analyticService->getPageViewsData($startDate, $endDate);

        return $this->sendResponse($data, 'Page views retrieved successfully.');
    }

    /**
     * Get top pages
     */
    public function getTopPages(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $pages = $this->analyticService->getTopPages($limit);

        return $this->sendResponse($pages, 'Top pages retrieved successfully.');
    }

    /**
     * Get visitor trends
     */
    public function getVisitorTrends(Request $request): JsonResponse
    {
        $days = $request->input('days', 30);

        $trends = $this->analyticService->getVisitorTrends($days);

        return $this->sendResponse($trends, 'Visitor trends retrieved successfully.');
    }

    /**
     * Get top referrers
     */
    public function getTopReferrers(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $referrers = $this->analyticService->getTopReferrers($limit);

        return $this->sendResponse($referrers, 'Top referrers retrieved successfully.');
    }

    /**
     * Get browser statistics
     */
    public function getBrowserStats(): JsonResponse
    {
        $stats = $this->analyticService->getBrowserStats();

        return $this->sendResponse($stats, 'Browser stats retrieved successfully.');
    }

    /**
     * Get comprehensive analytics overview
     */
    public function getOverview(Request $request): JsonResponse
    {
        $days = $request->input('days', 30);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $overview = [
            'dashboard_stats' => $this->analyticService->getDashboardStats($startDate, $endDate),
            'top_pages' => $this->analyticService->getTopPages(5),
            'visitor_trends' => $this->analyticService->getVisitorTrends($days),
            'top_referrers' => $this->analyticService->getTopReferrers(5),
            'browser_stats' => $this->analyticService->getBrowserStats(),
        ];

        return $this->sendResponse($overview, 'Analytics overview retrieved successfully.');
    }
}
