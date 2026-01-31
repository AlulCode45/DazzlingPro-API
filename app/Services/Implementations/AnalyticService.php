<?php

namespace App\Services\Implementations;

use App\Services\Contracts\AnalyticServiceInterface;
use App\Repositories\Contracts\AnalyticRepositoryInterface;
use Carbon\Carbon;

class AnalyticService implements AnalyticServiceInterface
{
    protected AnalyticRepositoryInterface $analyticRepository;

    public function __construct(AnalyticRepositoryInterface $analyticRepository)
    {
        $this->analyticRepository = $analyticRepository;
    }

    public function trackPageView(array $data): bool
    {
        $trackingData = [
            'event_type' => 'page_view',
            'page_url' => $data['page_url'] ?? null,
            'page_title' => $data['page_title'] ?? null,
            'referrer' => $data['referrer'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'ip_address' => $data['ip_address'] ?? null,
            'session_id' => $data['session_id'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ];

        return $this->analyticRepository->trackEvent($trackingData);
    }

    public function trackEvent(string $eventType, array $data): bool
    {
        $trackingData = array_merge([
            'event_type' => $eventType,
            'page_url' => null,
            'page_title' => null,
            'referrer' => null,
            'user_agent' => null,
            'ip_address' => null,
            'session_id' => null,
            'metadata' => null,
        ], $data);

        return $this->analyticRepository->trackEvent($trackingData);
    }

    public function getDashboardStats(string $startDate = null, string $endDate = null): array
    {
        $pageViews = $this->analyticRepository->getPageViews($startDate, $endDate);
        $totalVisitors = $this->analyticRepository->getTotalVisitors($startDate, $endDate);
        $uniqueSessions = $this->analyticRepository->getUniqueSessionCount($startDate, $endDate);

        return [
            'total_page_views' => $pageViews['total'],
            'total_visitors' => $totalVisitors,
            'unique_sessions' => $uniqueSessions,
            'avg_pages_per_session' => $uniqueSessions > 0 ? round($pageViews['total'] / $uniqueSessions, 2) : 0,
        ];
    }

    public function getPageViewsData(string $startDate = null, string $endDate = null): array
    {
        return $this->analyticRepository->getPageViews($startDate, $endDate);
    }

    public function getTopPages(int $limit = 10): array
    {
        return $this->analyticRepository->getTopPages($limit);
    }

    public function getVisitorTrends(int $days = 30): array
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $visitors = $this->analyticRepository->getVisitorsByDate(
            $startDate->toDateTimeString(),
            $endDate->toDateTimeString()
        );

        // Fill in missing dates with 0 visitors
        $filledData = [];
        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - $i - 1)->format('Y-m-d');
            $found = false;

            foreach ($visitors as $visitor) {
                if ($visitor['date'] === $date) {
                    $filledData[] = $visitor;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $filledData[] = [
                    'date' => $date,
                    'visitors' => 0,
                ];
            }
        }

        return $filledData;
    }

    public function getTopReferrers(int $limit = 10): array
    {
        return $this->analyticRepository->getTopReferrers($limit);
    }

    public function getBrowserStats(): array
    {
        return $this->analyticRepository->getBrowserStats();
    }
}
