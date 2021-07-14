<?php

namespace Flamarkt\Inventory;

use Flamarkt\Core\Product\AvailabilityManager;
use Flamarkt\Core\Product\Contract\AvailabilityDriverInterface;
use Flamarkt\Core\Product\Product;
use Flarum\User\User;
use Psr\Http\Message\ServerRequestInterface;

class InventoryAvailabilityDriver implements AvailabilityDriverInterface
{
    public function __invoke(Product $product, User $actor, ServerRequestInterface $request = null)
    {
        if ($product->inventory > 0) {
            return AvailabilityManager::AVAILABLE;
        }
    }
}
