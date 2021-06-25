<?php

namespace Flamarkt\Inventory;

use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Core\Product\Product;

class ProductAttributes
{
    public function __invoke(ProductSerializer $serializer, Product $product): array
    {
        return [
            'inventory' => is_null($product->inventory) ? null : (int)$product->inventory,
        ];
    }
}
