<?php

namespace Flamarkt\Inventory\Filter;

use Flarum\Filter\FilterInterface;
use Flarum\Filter\FilterState;
use Flarum\User\UserRepository;
use Illuminate\Database\Query\Builder;

class UserFilter implements FilterInterface
{
    public function __construct(
        protected UserRepository $users
    )
    {
    }

    public function getFilterKey(): string
    {
        return 'user';
    }

    public function filter(FilterState $filterState, string $filterValue, bool $negate)
    {
        $this->constrain($filterState->getQuery(), $filterValue, $negate);
    }

    protected function constrain(Builder $query, $rawUsernames, $negate)
    {
        $usernames = trim($rawUsernames, '"');
        $usernames = explode(',', $usernames);

        $ids = [];
        foreach ($usernames as $username) {
            $ids[] = $this->users->getIdForUsername($username);
        }

        $query->whereIn('flamarkt_balance_history.user_id', $ids, 'and', $negate);
    }
}
