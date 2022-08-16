<?php

namespace Flamarkt\Inventory;

use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Core\Extend\Availability;
use Flarum\Extend;
use Flarum\User\User;

return [
    (new Extend\Frontend('backoffice'))
        ->js(__DIR__ . '/js/dist/backoffice.js'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Routes('api'))
        ->get('/flamarkt/inventory', 'flamarkt.inventory.index', Api\Controller\HistoryIndexController::class)
        ->post('/flamarkt/products/{id}/inventory', 'flamarkt.inventory.store', Api\Controller\HistoryStoreController::class)
        ->patch('/flamarkt/inventory/{id:[0-9]+}', 'flamarkt.inventory.update', Api\Controller\HistoryUpdateController::class)
        ->delete('/flamarkt/inventory/{id:[0-9]+}', 'flamarkt.inventory.delete', Api\Controller\HistoryDeleteController::class),

    (new Extend\Model(User::class))
        ->hasMany('flamarktBalanceHistory', History::class),

    (new Extend\ApiSerializer(ProductSerializer::class))
        ->attributes(ProductAttributes::class)
        ->hasMany('inventoryHistory', Api\Serializer\HistorySerializer::class),

    (new Extend\Filter(HistoryFilterer::class))
        ->addFilter(Filter\ProductFilter::class)
        ->addFilter(Filter\UserFilter::class),

    (new Extend\ModelVisibility(History::class))
        ->scope(Scope\View::class),

    (new Availability)
        ->driver('inventory', InventoryAvailabilityDriver::class),
];
