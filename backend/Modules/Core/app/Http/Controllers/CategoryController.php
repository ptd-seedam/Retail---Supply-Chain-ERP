<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Http\Requests\StoreCategoryRequest;
use Modules\Core\Http\Requests\UpdateCategoryRequest;
use Modules\Core\Services\CategoryService;

class CategoryController extends BaseController
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $categories = $this->service->paginateCategories();
        return $this->successResponse($categories, 'Danh sách danh mục');
    }

    public function show($id)
    {
        $category = $this->service->getCategory($id);
        return $this->successResponse(['category' => $category], 'Danh mục');
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->createCategory($request->validated());
        return $this->successResponse(['category' => $category], 'Tạo danh mục thành công', 201);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->service->updateCategory($id, $request->validated());
        return $this->successResponse(['category' => $category], 'Cập nhật danh mục thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteCategory($id);
        return $this->successResponse(null, 'Xóa danh mục thành công');
    }

    public function getCategoryTree()
    {
        $tree = $this->service->getCategoryTree();
        return $this->successResponse(['tree' => $tree], 'Cây danh mục');
    }

    public function getChildren($parentId)
    {
        $children = $this->service->getCategoryChildren($parentId);
        return $this->successResponse($children, 'Danh mục con');
    }

    public function getActive()
    {
        $categories = $this->service->getActiveCategories();
        return $this->successResponse($categories, 'Danh sách danh mục hoạt động');
    }
}
