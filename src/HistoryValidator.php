<?php

namespace Flamarkt\Inventory;

use Flarum\Foundation\AbstractValidator;
use Illuminate\Validation\Rule;

class HistoryValidator extends AbstractValidator
{
    protected function getRules(): array
    {
        return [
            'operation' => ['nullable', Rule::in(['add', 'set', 'untrack'])],
            'amount' => ['numeric'],
            'comment' => ['nullable', 'string', 'max:20000'],
        ];
    }
}
