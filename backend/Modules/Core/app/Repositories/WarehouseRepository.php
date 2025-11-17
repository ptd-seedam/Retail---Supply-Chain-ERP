<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\Warehouse;

class WarehouseRepository extends BaseRepository
{
    public function __construct(Warehouse $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }
}
