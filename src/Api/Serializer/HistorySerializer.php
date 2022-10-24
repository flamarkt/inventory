<?php

namespace Flamarkt\Inventory\Api\Serializer;

use Flamarkt\Core\Api\Serializer\ProductSerializer;
use Flamarkt\Inventory\History;
use Flamarkt\Core\Api\Serializer\BasicOrderSerializer;
use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\BasicUserSerializer;
use Tobscure\JsonApi\Relationship;

class HistorySerializer extends AbstractSerializer
{
    protected $type = 'flamarkt-balance-history';

    /**
     * @param History $history
     * @return int[]
     */
    protected function getDefaultAttributes($history): array
    {
        return [
            'operation' => $history->operation,
            'amount' => $history->amount,
            'comment' => $history->comment,
            'createdAt' => $this->formatDate($history->created_at),
        ];
    }

    public function product($history): ?Relationship
    {
        // Use complete product serializer because the only time this relation is useful is to refresh the product data
        return $this->hasOne($history, ProductSerializer::class);
    }

    public function order($history): ?Relationship
    {
        return $this->hasOne($history, BasicOrderSerializer::class);
    }

    public function user($history): ?Relationship
    {
        return $this->hasOne($history, BasicUserSerializer::class);
    }
}
