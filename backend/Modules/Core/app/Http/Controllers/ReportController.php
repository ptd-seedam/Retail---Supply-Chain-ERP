<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Services\Reports\CoreReportService;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    protected CoreReportService $reportService;

    public function __construct(CoreReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get Core module report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Query parameters:
     * - level: summary|detailed|full (default: summary)
     * - filters: array of filters
     */
    public function report(Request $request)
    {
        $level = $request->query('level', 'summary');
        $filters = $request->query('filters', []);

        $report = $this->reportService->getReport($level, $filters);

        return $this->successResponse($report, 'Core module report');
    }

    /**
     * Get summary report only
     */
    public function summaryReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('summary', $filters);

        return $this->successResponse($report, 'Core module summary report');
    }

    /**
     * Get detailed report
     */
    public function detailedReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('detailed', $filters);

        return $this->successResponse($report, 'Core module detailed report');
    }

    /**
     * Get full report
     */
    public function fullReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('full', $filters);

        return $this->successResponse($report, 'Core module full report');
    }
}
