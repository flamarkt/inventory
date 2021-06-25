<?php

namespace Flamarkt\Inventory\Api\Controller;

use Flamarkt\Inventory\Api\Serializer\HistorySerializer;
use Flamarkt\Inventory\History;
use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class HistoryUpdateController extends AbstractShowController
{
    public $serializer = HistorySerializer::class;

    public $include = [
        'order',
        'user',
    ];

    public $optionalInclude = [
        'product',
    ];

    protected function data(ServerRequestInterface $request, Document $document)
    {
        RequestUtil::getActor($request)->assertCan('backoffice');

        $id = Arr::get($request->getQueryParams(), 'id');

        /**
         * @var History $history
         */
        $history = History::findOrFail($id);

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        //TODO: validator

        if (Arr::exists($attributes, 'comment')) {
            $history->comment = Arr::get($attributes, 'comment');
        }

        $history->save();

        return $history;
    }
}
