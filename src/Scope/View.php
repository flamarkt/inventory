<?php

namespace Flamarkt\Inventory\Scope;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class View
{
    public function __invoke(User $actor, Builder $query)
    {
        if (!$actor->can('backoffice')) {
            $query->whereRaw('false');
        }
    }
}
