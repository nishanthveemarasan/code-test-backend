<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\LogExceptionTrait;
use Illuminate\Support\Facades\Gate;
use Exception;

class ProductController extends Controller
{
    use LogExceptionTrait;

    public function __construct(
        protected ProductService $productService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Gate::authorize('create', Product::class);
        try{

            return ApiResponse::success("Product Created Successfully!");
        }catch(Exception $error){
            $this->logException($error);
            return ApiResponse::error("Failed to create a Product");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
