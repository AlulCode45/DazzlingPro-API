<?php

namespace App\Services\Contracts;

interface AnalyticServiceInterface
{
    public function trackPageView(array $data): bool;
    public function trackEvent(string $eventType, array $data): bool;
    public function getDashboardStats(string $startDate = null, string $endDate = null): array;
    public function getPageViewsData(string $startDate = null, string $endDate = null): array;
    public function getTopPages(int $limit = 10): array;
    public function getVisitorTrends(int $days = 30): array;
    public function getTopReferrers(int $limit = 10): array;
    public function getBrowserStats(): array;
}
