<?php

namespace Modules\Core\Services;

use Modules\Core\Repositories\CategoryRepository;

class CategoryService
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories()
    {
        return $this->repository->all();
    }

    public function getActiveCategories()
    {
        return $this->repository->getActive();
    }

    public function getCategory($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function createCategory(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateCategory($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->repository->delete($id);
    }

    public function paginateCategories($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function getCategoriesByParent($parentId = null)
    {
        return $this->repository->getByParent($parentId);
    }

    public function getCategoryTree($parentId = null)
    {
        return $this->repository->getWithChildren($parentId);
    }
}
