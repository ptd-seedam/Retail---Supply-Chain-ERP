<?php

namespace Modules\CMS\Services\Reports;

use App\Services\Reports\BaseReportService;
use Modules\CMS\Models\Banner;
use Modules\CMS\Models\News;
use Illuminate\Support\Facades\DB;

/**
 * CMS Module Report Service
 *
 * Provides 3-level reports for:
 * - Banners
 * - News/Articles
 * - Content Management
 */
class CMSReportService extends BaseReportService
{
    /**
     * Get summary report
     * - Banners and News count by status
     * - Content popularity metrics
     */
    protected function getSummaryReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'banners' => [
                'total' => Banner::count(),
                'active' => Banner::where('status', 'active')->count(),
                'inactive' => Banner::where('status', 'inactive')->count(),
            ],
            'news' => [
                'total' => News::count(),
                'draft' => News::where('status', 'draft')->count(),
                'published' => News::where('status', 'published')->count(),
                'archived' => News::where('status', 'archived')->count(),
            ],
            'content_metrics' => [
                'total_banner_views' => Banner::sum('sort_order') > 0 ? 'calculated' : 0,
                'total_news_views' => News::sum('views') ?? 0,
                'most_viewed_article' => News::orderBy('views', 'desc')->first(['id', 'title', 'views']) ?? null,
            ],
        ], self::LEVEL_SUMMARY, [
            'report_type' => 'cms_module_summary',
        ]);
    }

    /**
     * Get detailed report
     * - Banner list with status and dates
     * - News list by status
     * - Content performance metrics
     */
    protected function getDetailedReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'banners' => Banner::select('id', 'title', 'image_url', 'link_url', 'sort_order', 'status', 'start_date', 'end_date')
                ->orderBy('sort_order')
                ->get()
                ->toArray(),
            'news' => News::select('id', 'title', 'slug', 'status', 'views', 'published_at')
                ->orderBy('published_at', 'desc')
                ->get()
                ->toArray(),
            'content_performance' => [
                'banners_by_status' => Banner::groupBy('status')
                    ->selectRaw('status, COUNT(*) as count')
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray(),
                'news_by_status' => News::groupBy('status')
                    ->selectRaw('status, COUNT(*) as count, SUM(views) as total_views')
                    ->get()
                    ->map(function ($item) {
                        return (array) $item;
                    })
                    ->toArray(),
                'top_articles' => News::where('status', 'published')
                    ->select('id', 'title', 'views')
                    ->orderBy('views', 'desc')
                    ->limit(10)
                    ->get()
                    ->toArray(),
            ],
            'summary' => [
                'total_banners' => Banner::count(),
                'total_news_articles' => News::count(),
            ],
        ], self::LEVEL_DETAILED, [
            'report_type' => 'cms_module_detailed',
        ]);
    }

    /**
     * Get full report
     * - Complete banner details with full metadata
     * - Full news history with content
     * - Complete audit trail
     */
    protected function getFullReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'banners' => Banner::with([
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'news' => News::with([
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->orderBy('published_at', 'desc')
                ->get()
                ->toArray(),
            'content_timeline' => [
                'banners_by_date' => DB::table('banners')
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->limit(30)
                    ->get()
                    ->toArray(),
                'news_by_date' => DB::table('news')
                    ->selectRaw('DATE(published_at) as date, COUNT(*) as count, SUM(views) as views')
                    ->whereNotNull('published_at')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->limit(30)
                    ->get()
                    ->toArray(),
            ],
            'engagement_metrics' => [
                'total_banner_impressions' => Banner::count(),
                'total_article_views' => News::sum('views') ?? 0,
                'published_articles' => News::where('status', 'published')->count(),
                'draft_articles' => News::where('status', 'draft')->count(),
                'archived_articles' => News::where('status', 'archived')->count(),
            ],
            'statistics' => [
                'total_banners' => Banner::count(),
                'deleted_banners' => Banner::onlyTrashed()->count(),
                'total_news_articles' => News::count(),
                'deleted_news' => News::onlyTrashed()->count(),
                'total_views' => News::sum('views') ?? 0,
            ],
        ], self::LEVEL_FULL, [
            'report_type' => 'cms_module_full',
            'includes_soft_deletes' => true,
            'includes_engagement_metrics' => true,
        ]);
    }
}
