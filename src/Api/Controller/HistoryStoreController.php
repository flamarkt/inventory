<?php

namespace Flamarkt\Inventory\Api\Controller;

use Flamarkt\Core\Product\ProductRepository;
use Flamarkt\Inventory\Api\Serializer\HistorySerializer;
use Flamarkt\Inventory\History;
use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class HistoryStoreController extends AbstractCreateController
{
    public $serializer = HistorySerializer::class;

    public $include = [
        'order',
        'user',
    ];

    public $optionalInclude = [
        'product',
    ];

    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);

        $actor->assertCan('backoffice');

        $productId = Arr::get($request->getQueryParams(), 'id');

        $product = $this->repository->findOrFail($productId, $actor);

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        // TODO: validation
        // TODO: transaction

        $operation = Arr::get($attributes, 'operation');
        $amount = Arr::get($attributes, 'amount');

        if ($operation === 'add') {
            $product->inventory += $amount;
        } else if ($operation === 'set') {
            $product->inventory = $amount;
        } else {
            // Any other "operation" value will set the balance to null
            // This is useful because in the UI we will offer this as a third option
            $operation = 'set';
            $product->inventory = null;
            $amount = null;
        }

        $product->save();

        $history = new History();
        $history->product()->associate($product);
        $history->user()->associate($actor);
        $history->operation = $operation;
        $history->amount = $amount;
        $history->comment = Arr::get($attributes, 'comment');
        $history->save();

        return $history;
    }
}
