<?php

namespace Flamarkt\Inventory;

use Carbon\Carbon;
use Flamarkt\Core\Order\Order;
use Flamarkt\Core\Product\Product;
use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Relations;

/**
 * @property int $id
 * @property int $product_id
 * @property int $order_id
 * @property int $user_id
 * @property string $operation
 * @property int $amount
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product $product
 * @property Order|null $order
 * @property User|null $user
 */
class History extends AbstractModel
{
    use ScopeVisibilityTrait;

    protected $table = 'flamarkt_inventory_history';

    public $timestamps = true;

    protected $casts = [
        'amount' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
