<?php

namespace Flamarkt\Inventory\Api\Controller;

use Flamarkt\Inventory\History;
use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class HistoryDeleteController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
    {
        RequestUtil::getActor($request)->assertCan('backoffice');

        $id = (string)Arr::get($request->getQueryParams(), 'id');

        /**
         * @var History $history
         */
        $history = History::findOrFail($id);

        $history->delete();
    }
}
