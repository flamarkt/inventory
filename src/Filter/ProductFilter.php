<?php

namespace Flamarkt\Inventory\Filter;

use Flarum\Filter\FilterInterface;
use Flarum\Filter\FilterState;
use Illuminate\Database\Query\Builder;

class ProductFilter implements FilterInterface
{
    public function getFilterKey(): string
    {
        return 'product';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        $this->constrain($filterState->getQuery(), $filterValue, $negate);
    }

    protected function constrain(Builder $query, $ids, $negate)
    {
        $ids = explode(',', $ids);

        $query->whereIn('flamarkt_inventory_history.product_id', $ids, 'and', $negate);
    }
}
