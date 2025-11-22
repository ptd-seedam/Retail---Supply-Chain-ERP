<?php

namespace Modules\CRM\Services\Reports;

use App\Services\Reports\BaseReportService;
use Modules\CRM\Models\Customer;
use Modules\CRM\Models\CustomerGroup;
use Modules\CRM\Models\LoyaltyPoint;
use Illuminate\Support\Facades\DB;

/**
 * CRM Module Report Service
 *
 * Provides 3-level reports for:
 * - Customers
 * - Customer Groups
 * - Loyalty Points
 */
class CRMReportService extends BaseReportService
{
    /**
     * Get summary report
     * - Customer statistics by status
     * - Customer groups overview
     * - Loyalty points metrics
     * - Credit limits
     */
    protected function getSummaryReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'customers' => [
                'total' => Customer::count(),
                'active' => Customer::where('status', 'active')->count(),
                'inactive' => Customer::where('status', 'inactive')->count(),
                'suspended' => Customer::where('status', 'suspended')->count(),
            ],
            'customer_groups' => [
                'total' => CustomerGroup::count(),
                'active' => CustomerGroup::where('status', 'active')->count(),
            ],
            'credit_analysis' => [
                'total_credit_limit' => Customer::sum('credit_limit') ?? 0,
                'total_current_balance' => Customer::sum('current_balance') ?? 0,
                'customers_at_limit' => Customer::whereRaw('current_balance >= credit_limit')->count(),
            ],
            'loyalty_points' => [
                'total_earned' => LoyaltyPoint::where('type', 'earn')->sum('points') ?? 0,
                'total_redeemed' => LoyaltyPoint::where('type', 'redeem')->sum('points') ?? 0,
                'total_customers_with_points' => LoyaltyPoint::distinct('customer_id')->count('customer_id'),
            ],
        ], self::LEVEL_SUMMARY, [
            'report_type' => 'crm_module_summary',
        ]);
    }

    /**
     * Get detailed report
     * - Customer list with group and status
     * - Customer group analysis
     * - High-value customer identification
     * - Loyalty points summary by customer
     */
    protected function getDetailedReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'customers' => Customer::with(['group:id,name'])
                ->select('id', 'name', 'email', 'phone', 'group_id', 'credit_limit', 'current_balance', 'status')
                ->orderBy('status')
                ->orderBy('name')
                ->get()
                ->toArray(),
            'customer_groups' => CustomerGroup::withCount('customers')
                ->select('id', 'name', 'discount_percent', 'status')
                ->orderBy('name')
                ->get()
                ->toArray(),
            'high_value_customers' => Customer::select('id', 'name', 'email', 'credit_limit', 'current_balance')
                ->where('credit_limit', '>', 0)
                ->orderBy('credit_limit', 'desc')
                ->limit(20)
                ->get()
                ->toArray(),
            'loyalty_points_summary' => DB::table('loyalty_points')
                ->selectRaw('customer_id, SUM(CASE WHEN type = "earn" THEN points ELSE 0 END) as points_earned, SUM(CASE WHEN type = "redeem" THEN points ELSE 0 END) as points_redeemed')
                ->groupBy('customer_id')
                ->orderBy('points_earned', 'desc')
                ->limit(20)
                ->get()
                ->toArray(),
            'suspended_customers' => Customer::where('status', 'suspended')
                ->select('id', 'name', 'email', 'status', 'current_balance')
                ->get()
                ->toArray(),
            'summary' => [
                'total_customers' => Customer::count(),
                'total_groups' => CustomerGroup::count(),
            ],
        ], self::LEVEL_DETAILED, [
            'report_type' => 'crm_module_detailed',
        ]);
    }

    /**
     * Get full report
     * - Complete customer profiles with contact info
     * - Full loyalty points history
     * - Credit analysis
     * - Customer interaction history
     */
    protected function getFullReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'customers' => Customer::with([
                'group:id,name,discount_percent',
                'loyaltyPoints' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'customer_groups' => CustomerGroup::with([
                'customers:id,name,email,group_id',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'loyalty_points_history' => LoyaltyPoint::with([
                'customer:id,name,email',
                'createdBy:id,name'
            ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray(),
            'credit_analysis' => [
                'customers_by_credit_status' => DB::table('customers')
                    ->selectRaw('
                        SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_customers,
                        SUM(CASE WHEN status = "suspended" THEN 1 ELSE 0 END) as suspended_customers,
                        SUM(CASE WHEN current_balance > 0 THEN 1 ELSE 0 END) as with_balance,
                        SUM(CASE WHEN current_balance >= credit_limit AND credit_limit > 0 THEN 1 ELSE 0 END) as at_limit
                    ')
                    ->first(),
                'total_credit_exposure' => Customer::sum('credit_limit') ?? 0,
            ],
            'statistics' => [
                'total_customers' => Customer::count(),
                'deleted_customers' => Customer::onlyTrashed()->count(),
                'total_groups' => CustomerGroup::count(),
                'total_loyalty_points_records' => LoyaltyPoint::count(),
            ],
        ], self::LEVEL_FULL, [
            'report_type' => 'crm_module_full',
            'includes_soft_deletes' => true,
            'includes_loyalty_history' => true,
        ]);
    }
}
