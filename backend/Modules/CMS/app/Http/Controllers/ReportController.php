<?php

namespace Modules\CMS\Http\Controllers;

use Modules\CMS\Services\Reports\CMSReportService;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    protected CMSReportService $reportService;

    public function __construct(CMSReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function report(Request $request)
    {
        $level = $request->query('level', 'summary');
        $filters = $request->query('filters', []);

        $report = $this->reportService->getReport($level, $filters);

        return $this->successResponse($report, 'CMS module report');
    }

    public function summaryReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('summary', $filters);

        return $this->successResponse($report, 'CMS module summary report');
    }

    public function detailedReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('detailed', $filters);

        return $this->successResponse($report, 'CMS module detailed report');
    }

    public function fullReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('full', $filters);

        return $this->successResponse($report, 'CMS module full report');
    }
}
