<?php

namespace Modules\Ecommerce\Services\Reports;

use App\Services\Reports\BaseReportService;
use Modules\Ecommerce\Models\Order;
use Modules\Ecommerce\Models\OrderItem;
use Modules\Ecommerce\Models\Promotion;
use Illuminate\Support\Facades\DB;

/**
 * Ecommerce Module Report Service
 *
 * Provides 3-level reports for:
 * - Orders
 * - Order Items
 * - Promotions
 * - Sales Analysis
 */
class EcommerceReportService extends BaseReportService
{
    /**
     * Get summary report
     * - Order statistics by status
     * - Sales metrics
     * - Promotion usage
     */
    protected function getSummaryReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'orders' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
                'refunded' => Order::where('status', 'refunded')->count(),
            ],
            'sales' => [
                'total_revenue' => Order::where('status', 'completed')->sum('total_amount') ?? 0,
                'total_discount' => Order::sum('discount_amount') ?? 0,
                'total_tax' => Order::sum('tax_amount') ?? 0,
                'average_order_value' => Order::where('status', 'completed')->avg('total_amount') ?? 0,
            ],
            'order_items' => [
                'total_items_sold' => OrderItem::sum('quantity') ?? 0,
                'total_unique_products' => OrderItem::distinct('product_id')->count('product_id'),
            ],
            'promotions' => [
                'total' => Promotion::count(),
                'active' => Promotion::where('status', 'active')->count(),
                'total_usage' => Promotion::sum('current_usage') ?? 0,
            ],
        ], self::LEVEL_SUMMARY, [
            'report_type' => 'ecommerce_module_summary',
        ]);
    }

    /**
     * Get detailed report
     * - Recent orders with items
     * - Sales by status
     * - Top selling products
     * - Promotion performance
     */
    protected function getDetailedReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'orders' => Order::with(['customer:id,name,email', 'items:id,order_id,product_id,quantity,unit_price'])
                ->select('id', 'order_number', 'customer_id', 'status', 'subtotal', 'discount_amount', 'tax_amount', 'total_amount', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->toArray(),
            'sales_by_status' => DB::table('orders')
                ->selectRaw('status, COUNT(*) as count, SUM(total_amount) as revenue')
                ->groupBy('status')
                ->get()
                ->toArray(),
            'top_products' => DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('products.id, products.sku, products.name, SUM(order_items.quantity) as total_quantity, SUM(order_items.subtotal) as total_revenue')
                ->groupBy('products.id', 'products.sku', 'products.name')
                ->orderBy('total_quantity', 'desc')
                ->limit(20)
                ->get()
                ->toArray(),
            'promotions' => Promotion::select('id', 'code', 'name', 'type', 'discount_value', 'current_usage', 'max_usage', 'status')
                ->orderBy('current_usage', 'desc')
                ->get()
                ->toArray(),
            'summary' => [
                'total_orders' => Order::count(),
                'total_completed_orders' => Order::where('status', 'completed')->count(),
                'total_promotions' => Promotion::count(),
            ],
        ], self::LEVEL_DETAILED, [
            'report_type' => 'ecommerce_module_detailed',
        ]);
    }

    /**
     * Get full report
     * - Complete order details with all relationships
     * - Full order item history
     * - Complete promotion analysis
     * - Sales trends and analysis
     */
    protected function getFullReport(array $filters = []): array
    {
        $filters = $this->applyFilters($filters);

        return $this->formatReportResponse([
            'orders' => Order::with([
                'customer:id,name,email,phone',
                'items' => function ($query) {
                    $query->with('product:id,sku,name');
                },
                'promotion:id,code,name,discount_value',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'order_items' => OrderItem::with([
                'order:id,order_number,total_amount',
                'product:id,sku,name,cost_price,selling_price',
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray(),
            'promotions' => Promotion::with([
                'createdBy:id,name',
                'updatedBy:id,name'
            ])
                ->get()
                ->toArray(),
            'sales_analysis' => [
                'daily_sales' => DB::table('orders')
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
                    ->where('status', 'completed')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->limit(30)
                    ->get()
                    ->toArray(),
                'by_warehouse' => DB::table('orders')
                    ->selectRaw('warehouse_id, COUNT(*) as orders, SUM(total_amount) as revenue')
                    ->groupBy('warehouse_id')
                    ->get()
                    ->toArray(),
            ],
            'statistics' => [
                'total_orders' => Order::count(),
                'deleted_orders' => Order::onlyTrashed()->count(),
                'total_order_items' => OrderItem::count(),
                'total_promotions' => Promotion::count(),
                'total_revenue' => Order::where('status', 'completed')->sum('total_amount') ?? 0,
                'total_discount_given' => Order::sum('discount_amount') ?? 0,
            ],
        ], self::LEVEL_FULL, [
            'report_type' => 'ecommerce_module_full',
            'includes_soft_deletes' => true,
            'includes_sales_trends' => true,
        ]);
    }
}
