<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Http\Requests\StoreProductRequest;
use Modules\Core\Http\Requests\UpdateProductRequest;
use Modules\Core\Services\ProductService;

class ProductController extends BaseController
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $products = $this->service->paginateProducts();
        return $this->successResponse($products, 'Danh sách sản phẩm');
    }

    public function show($id)
    {
        $product = $this->service->getProduct($id);
        return $this->successResponse(['product' => $product], 'Sản phẩm');
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->service->createProduct($request->validated());
        return $this->successResponse(['product' => $product], 'Tạo sản phẩm thành công', 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->service->updateProduct($id, $request->validated());
        return $this->successResponse(['product' => $product], 'Cập nhật sản phẩm thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteProduct($id);
        return $this->successResponse(null, 'Xóa sản phẩm thành công');
    }

    public function getByCategory($categoryId)
    {
        $products = $this->service->getProductsByCategory($categoryId);
        return $this->successResponse($products, 'Sản phẩm theo danh mục');
    }

    public function getActive()
    {
        $products = $this->service->getActiveProducts();
        return $this->successResponse($products, 'Danh sách sản phẩm hoạt động');
    }
}
