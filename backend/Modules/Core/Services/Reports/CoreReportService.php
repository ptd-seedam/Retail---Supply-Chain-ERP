<?php

namespace Modules\Core\Services\Reports;

use App\Services\Reports\BaseReportService;
use Modules\Core\Models\Warehouse;
use Modules\Core\Models\Product;
use Modules\Core\Models\Category;
use Illuminate\Support\Facades\DB;

/**
 * Core Module Report Service
 *
 * Provides 3-level reports for:
 * - Warehouses
 * - Products
 * - Categories
 */
class CoreReportService extends BaseReportService
{
    /**
     * Get summary report
     * - Total counts
     * - Active/Inactive statistics
     * - Basic metrics
     */
    protected function getSummaryReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'warehouses' => [
                'total' => Warehouse::count(),
                'active' => Warehouse::where('status', 'active')->count(),
                'inactive' => Warehouse::where('status', 'inactive')->count(),
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('status', 'active')->count(),
                'inactive' => Product::where('status', 'inactive')->count(),
                'by_category' => Category::withCount('products')
                    ->pluck('products_count', 'name')
                    ->toArray(),
            ],
            'categories' => [
                'total' => Category::count(),
                'active' => Category::where('status', 'active')->count(),
                'inactive' => Category::where('status', 'inactive')->count(),
                'with_children' => Category::whereNotNull('parent_id')->count(),
            ],
            'inventory' => [
                'low_stock_products' => Product::whereRaw('reorder_level > 0')->count(),
                'price_range' => [
                    'min_price' => Product::min('selling_price') ?? 0,
                    'max_price' => Product::max('selling_price') ?? 0,
                    'avg_price' => Product::avg('selling_price') ?? 0,
                ],
            ],
        ], self::LEVEL_SUMMARY, [
            'report_type' => 'core_module_summary',
        ]);
    }

    /**
     * Get detailed report
     * - Warehouse list with basic info
     * - Product list by category
     * - Category hierarchy
     * - Inventory metrics
     */
    protected function getDetailedReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'warehouses' => Warehouse::select('id', 'code', 'name', 'location', 'status')
                ->orderBy('code')
                ->get()
                ->toArray(),
            'products' => Product::with('category:id,name')
                ->select('id', 'sku', 'name', 'category_id', 'cost_price', 'selling_price', 'status')
                ->orderBy('category_id', 'asc')
                ->orderBy('name')
                ->get()
                ->toArray(),
            'categories' => Category::select('id', 'name', 'parent_id', 'sort_order', 'status')
                ->orderBy('parent_id')
                ->orderBy('sort_order')
                ->get()
                ->toArray(),
            'inventory_analysis' => [
                'total_value' => DB::table('products')
                    ->selectRaw('SUM(cost_price) as total_cost, SUM(selling_price) as total_selling')
                    ->first(),
                'products_by_status' => Product::groupBy('status')
                    ->selectRaw('status, COUNT(*) as count')
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray(),
            ],
            'summary' => [
                'total_warehouses' => Warehouse::count(),
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
            ],
        ], self::LEVEL_DETAILED, [
            'report_type' => 'core_module_detailed',
        ]);
    }

    /**
     * Get full report
     * - Complete warehouse details with created/updated user info
     * - Products with full relationships
     * - Category hierarchy with all details
     * - Complete inventory transactions
     * - Audit trail
     */
    protected function getFullReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'warehouses' => Warehouse::with(['createdBy:id,name', 'updatedBy:id,name'])
                ->get()
                ->toArray(),
            'products' => Product::with([
                'category:id,name,parent_id',
                'createdBy:id,name',
                'updatedBy:id,name',
                'inventoryTransactions' => function ($query) {
                    $query->orderBy('created_at', 'desc')->limit(10);
                }
            ])
                ->get()
                ->toArray(),
            'categories' => Category::with([
                'parent:id,name',
                'children',
                'products:id,name,sku',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'inventory_transactions' => DB::table('inventory_transactions')
                ->select('*')
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get()
                ->toArray(),
            'statistics' => [
                'total_warehouses' => Warehouse::count(),
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'total_transactions' => DB::table('inventory_transactions')->count(),
                'deleted_products' => Product::onlyTrashed()->count(),
                'deleted_categories' => Category::onlyTrashed()->count(),
            ],
        ], self::LEVEL_FULL, [
            'report_type' => 'core_module_full',
            'includes_soft_deletes' => true,
            'includes_audit_trail' => true,
        ]);
    }
}
