<?php

namespace Modules\HRM\Services\Reports;

use App\Services\Reports\BaseReportService;
use Modules\HRM\Models\Employee;
use Modules\HRM\Models\Salary;
use Modules\HRM\Models\Department;
use Modules\HRM\Models\Shift;
use Illuminate\Support\Facades\DB;

/**
 * HRM Module Report Service
 *
 * Provides 3-level reports for:
 * - Employees
 * - Salaries
 * - Departments
 * - Shifts
 */
class HRMReportService extends BaseReportService
{
    /**
     * Get summary report
     * - Employee statistics
     * - Salary statistics
     * - Department headcount
     */
    protected function getSummaryReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'employees' => [
                'total' => Employee::count(),
                'active' => Employee::where('status', 'active')->count(),
                'inactive' => Employee::where('status', 'inactive')->count(),
                'on_leave' => Employee::where('status', 'on_leave')->count(),
            ],
            'departments' => [
                'total' => Department::count(),
                'active' => Department::where('status', 'active')->count(),
                'headcount' => Employee::selectRaw('department_id, COUNT(*) as count')
                    ->where('status', 'active')
                    ->groupBy('department_id')
                    ->pluck('count', 'department_id')
                    ->toArray(),
            ],
            'salaries' => [
                'total_records' => Salary::count(),
                'pending' => Salary::where('status', 'pending')->count(),
                'approved' => Salary::where('status', 'approved')->count(),
                'paid' => Salary::where('status', 'paid')->count(),
                'total_payroll' => Salary::where('status', 'paid')->sum('total_salary') ?? 0,
            ],
            'shifts' => [
                'total' => Shift::count(),
                'active' => Shift::where('status', 'active')->count(),
            ],
        ], self::LEVEL_SUMMARY, [
            'report_type' => 'hrm_module_summary',
        ]);
    }

    /**
     * Get detailed report
     * - Employee list with department/shift
     * - Salary by employee and status
     * - Department structure with headcount
     * - Payroll analysis
     */
    protected function getDetailedReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'employees' => Employee::with(['department:id,name', 'shift:id,name', 'user:id,name,email'])
                ->select('id', 'user_id', 'employee_code', 'department_id', 'shift_id', 'phone', 'hire_date', 'status')
                ->orderBy('employee_code')
                ->get()
                ->toArray(),
            'departments' => Department::withCount('employees')
                ->select('id', 'name', 'code', 'status')
                ->orderBy('name')
                ->get()
                ->toArray(),
            'salaries_current_month' => Salary::with('employee:id,employee_code')
                ->select('id', 'employee_id', 'base_salary', 'bonus', 'deductions', 'total_salary', 'status')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->get()
                ->toArray(),
            'shifts' => Shift::withCount('employees')
                ->select('id', 'name', 'start_time', 'end_time', 'status')
                ->orderBy('start_time')
                ->get()
                ->toArray(),
            'payroll_analysis' => [
                'total_payroll_pending' => Salary::where('status', 'pending')->sum('total_salary') ?? 0,
                'total_payroll_approved' => Salary::where('status', 'approved')->sum('total_salary') ?? 0,
                'total_payroll_paid' => Salary::where('status', 'paid')->sum('total_salary') ?? 0,
                'average_salary' => Salary::avg('base_salary') ?? 0,
            ],
            'summary' => [
                'total_employees' => Employee::count(),
                'total_departments' => Department::count(),
                'total_shifts' => Shift::count(),
            ],
        ], self::LEVEL_DETAILED, [
            'report_type' => 'hrm_module_detailed',
        ]);
    }

    /**
     * Get full report
     * - Complete employee profiles with audit trail
     * - Full salary history
     * - Complete department structure
     * - Shift assignments
     */
    protected function getFullReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'employees' => Employee::with([
                'user:id,name,email',
                'department:id,name,code',
                'shift:id,name',
                'salaries' => function ($query) {
                    $query->orderBy('year', 'desc')->orderBy('month', 'desc');
                },
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'salary_history' => Salary::with([
                'employee:id,employee_code',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get()
                ->toArray(),
            'departments' => Department::with([
                'employees:id,employee_code,department_id',
                'parent:id,name',
                'children',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'shifts' => Shift::with([
                'employees:id,employee_code,shift_id',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'statistics' => [
                'total_employees' => Employee::count(),
                'deleted_employees' => Employee::onlyTrashed()->count(),
                'total_departments' => Department::count(),
                'total_shifts' => Shift::count(),
                'total_salary_records' => Salary::count(),
                'total_payroll_processed' => Salary::where('status', 'paid')->sum('total_salary') ?? 0,
            ],
        ], self::LEVEL_FULL, [
            'report_type' => 'hrm_module_full',
            'includes_soft_deletes' => true,
            'includes_salary_history' => true,
        ]);
    }
}
