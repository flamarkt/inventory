<?php

namespace Flamarkt\Inventory\Api\Controller;

use Flamarkt\Core\Product\ProductRepository;
use Flamarkt\Inventory\Api\Serializer\HistorySerializer;
use Flamarkt\Inventory\History;
use Flamarkt\Inventory\HistoryValidator;
use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Http\RequestUtil;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class HistoryStoreController extends AbstractCreateController
{
    public $serializer = HistorySerializer::class;

    // Include order+user because the UI will probably display a new entry in a table after manual creation
    // Include product because the UI will likely be on the product page, and we want to load the new amount
    public $include = [
        'product',
        'order',
        'user',
    ];

    public function __construct(
        protected ProductRepository   $repository,
        protected HistoryValidator    $validator,
        protected ConnectionInterface $db
    )
    {
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);

        $actor->assertCan('backoffice');

        $productId = (string)Arr::get($request->getQueryParams(), 'id');
        $attributes = (array)Arr::get($request->getParsedBody(), 'data.attributes');
        $operation = Arr::get($attributes, 'operation');
        $amount = Arr::get($attributes, 'amount');
        $comment = Arr::get($attributes, 'comment');

        $this->validator->assertValid([
            'operation' => $operation,
            'amount' => $amount,
            'comment' => $comment,
        ]);

        return $this->db->transaction(function () use ($actor, $productId, $operation, $amount, $comment) {
            $product = $this->repository->findUidOrFail($productId, $actor);

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
            $history->comment = $comment;
            $history->save();

            return $history;
        });
    }
}
