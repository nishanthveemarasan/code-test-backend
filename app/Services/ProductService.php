<?php
namespace App\Services;

use App\Models\Product;

class ProductService{
    
    public function createOrUpdate(array $data, ?Product $product = null){
        $images = $data['images'];
        unset($data['images']);
        if(!$product){
            $product = Product::create($data);
        }

        foreach($images as $image){
            $path = $image['file']->store('main_page_images', 'public');
            $product->images()->create([
                'path' => $path,
                'mime_type' => $image['file']->getClientMimeType(),
                'order' => $imageData['order'] ?? null,
            ]);
        }
    }
}