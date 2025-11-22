<?php

namespace Modules\CRM\Http\Controllers;

use Modules\CRM\Services\Reports\CRMReportService;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    protected CRMReportService $reportService;

    public function __construct(CRMReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function report(Request $request)
    {
        $level = $request->query('level', 'summary');
        $filters = $request->query('filters', []);

        $report = $this->reportService->getReport($level, $filters);

        return $this->successResponse($report, 'CRM module report');
    }

    public function summaryReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('summary', $filters);

        return $this->successResponse($report, 'CRM module summary report');
    }

    public function detailedReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('detailed', $filters);

        return $this->successResponse($report, 'CRM module detailed report');
    }

    public function fullReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('full', $filters);

        return $this->successResponse($report, 'CRM module full report');
    }
}
