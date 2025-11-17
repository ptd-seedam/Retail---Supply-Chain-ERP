<?php

namespace Modules\Core\Services;

use Modules\Core\Repositories\ProductRepository;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllProducts()
    {
        return $this->repository->all();
    }

    public function getActiveProducts()
    {
        return $this->repository->getActive();
    }

    public function getProduct($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function getProductBySku($sku)
    {
        return $this->repository->getBySku($sku);
    }

    public function getProductByBarcode($barcode)
    {
        return $this->repository->getByBarcode($barcode);
    }

    public function createProduct(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->repository->delete($id);
    }

    public function paginateProducts($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function getProductsByCategory($categoryId)
    {
        return $this->repository->getByCategory($categoryId);
    }
}
