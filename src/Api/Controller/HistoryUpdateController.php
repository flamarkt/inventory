<?php

namespace Flamarkt\Inventory\Api\Controller;

use Flamarkt\Inventory\Api\Serializer\HistorySerializer;
use Flamarkt\Inventory\History;
use Flamarkt\Inventory\HistoryValidator;
use Flarum\Api\Controller\AbstractShowController;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class HistoryUpdateController extends AbstractShowController
{
    public $serializer = HistorySerializer::class;

    // Don't include any relationships by default since they cannot be edited in this call so there won't be any need to refresh them client-side
    public $optionalInclude = [
        'product',
        'order',
        'user',
    ];

    protected $validator;

    public function __construct(HistoryValidator $validator)
    {
        $this->validator = $validator;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        RequestUtil::getActor($request)->assertCan('backoffice');

        $id = (string)Arr::get($request->getQueryParams(), 'id');

        /**
         * @var History $history
         */
        $history = History::findOrFail($id);

        $attributes = (array)Arr::get($request->getParsedBody(), 'data.attributes');

        $this->validator->assertValid($attributes);

        if (Arr::exists($attributes, 'comment')) {
            $history->comment = Arr::get($attributes, 'comment');
        }

        $history->save();

        return $history;
    }
}
