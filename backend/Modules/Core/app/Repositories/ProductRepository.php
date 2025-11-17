<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getBySku($sku)
    {
        return $this->model->where('sku', $sku)->first();
    }

    public function getByBarcode($barcode)
    {
        return $this->model->where('barcode', $barcode)->first();
    }

    public function getByCategory($categoryId)
    {
        return $this->model->where('category_id', $categoryId)->get();
    }
}
