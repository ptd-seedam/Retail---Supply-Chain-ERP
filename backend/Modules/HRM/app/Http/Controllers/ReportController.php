<?php

namespace Modules\HRM\Http\Controllers;

use Modules\HRM\Services\Reports\HRMReportService;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    protected HRMReportService $reportService;

    public function __construct(HRMReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function report(Request $request)
    {
        $level = $request->query('level', 'summary');
        $filters = $request->query('filters', []);

        $report = $this->reportService->getReport($level, $filters);

        return $this->successResponse($report, 'HRM module report');
    }

    public function summaryReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('summary', $filters);

        return $this->successResponse($report, 'HRM module summary report');
    }

    public function detailedReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('detailed', $filters);

        return $this->successResponse($report, 'HRM module detailed report');
    }

    public function fullReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('full', $filters);

        return $this->successResponse($report, 'HRM module full report');
    }
}
