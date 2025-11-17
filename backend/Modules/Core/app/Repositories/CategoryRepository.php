<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->where('status', 'active')->orderBy('sort_order')->get();
    }

    public function getByParent($parentId = null)
    {
        return $this->model->where('parent_id', $parentId)->orderBy('sort_order')->get();
    }

    public function getWithChildren($parentId = null)
    {
        return $this->model
            ->where('parent_id', $parentId)
            ->with('children')
            ->orderBy('sort_order')
            ->get();
    }
}
