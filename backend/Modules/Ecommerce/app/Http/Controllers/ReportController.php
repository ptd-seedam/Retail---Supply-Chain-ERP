<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Services\Reports\EcommerceReportService;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    protected EcommerceReportService $reportService;

    public function __construct(EcommerceReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function report(Request $request)
    {
        $level = $request->query('level', 'summary');
        $filters = $request->query('filters', []);

        $report = $this->reportService->getReport($level, $filters);

        return $this->successResponse($report, 'Ecommerce module report');
    }

    public function summaryReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('summary', $filters);

        return $this->successResponse($report, 'Ecommerce module summary report');
    }

    public function detailedReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('detailed', $filters);

        return $this->successResponse($report, 'Ecommerce module detailed report');
    }

    public function fullReport(Request $request)
    {
        $filters = $request->query('filters', []);
        $report = $this->reportService->getReport('full', $filters);

        return $this->successResponse($report, 'Ecommerce module full report');
    }
}
