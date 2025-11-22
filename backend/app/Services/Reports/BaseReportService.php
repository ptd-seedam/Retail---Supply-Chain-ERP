<?php

namespace App\Services\Reports;

use Illuminate\Support\Collection;

/**
 * Base Report Service
 *
 * Provides 3-level reporting capability: Summary, Detailed, and Full
 */
abstract class BaseReportService
{
    /**
     * Report levels
     */
    public const LEVEL_SUMMARY = 'summary';
    public const LEVEL_DETAILED = 'detailed';
    public const LEVEL_FULL = 'full';

    /**
     * Get report with specified level
     *
     * @param string $level
     * @param array $filters
     * @return array
     */
    public function getReport(string $level = self::LEVEL_SUMMARY, array $filters = []): array
    {
        return match ($level) {
            self::LEVEL_SUMMARY => $this->getSummaryReport($filters),
            self::LEVEL_DETAILED => $this->getDetailedReport($filters),
            self::LEVEL_FULL => $this->getFullReport($filters),
            default => $this->getSummaryReport($filters),
        };
    }

    /**
     * Get summary report (minimal data, high-level overview)
     * - Count/statistics
     * - Basic metrics
     * - No detailed information
     *
     * @param array $filters
     * @return array
     */
    abstract protected function getSummaryReport(array $filters = []): array;

    /**
     * Get detailed report (balanced data)
     * - Includes summary data
     * - Key details and breakdowns
     * - Some relationships
     * - Useful for dashboards
     *
     * @param array $filters
     * @return array
     */
    abstract protected function getDetailedReport(array $filters = []): array;

    /**
     * Get full report (all data)
     * - Complete information
     * - All relationships
     * - Full audit trail where applicable
     * - Complete data dump
     *
     * @param array $filters
     * @return array
     */
    abstract protected function getFullReport(array $filters = []): array;

    /**
     * Format report response with metadata
     *
     * @param array $data
     * @param string $level
     * @param array $metadata
     * @return array
     */
    protected function formatReportResponse(array $data, string $level, array $metadata = []): array
    {
        return [
            'level' => $level,
            'generated_at' => now()->toIso8601String(),
            'data' => $data,
            'metadata' => array_merge([
                'total_records' => count($data['records'] ?? []) ?? 0,
                'total_items' => $data['total'] ?? 0,
            ], $metadata),
        ];
    }

    /**
     * Get filters from request/array
     *
     * @param array $filters
     * @return array
     */
    protected function applyFilters(array $filters): array
    {
        return array_filter($filters, fn($value) => $value !== null && $value !== '');
    }
}
