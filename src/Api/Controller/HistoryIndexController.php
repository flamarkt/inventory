<?php

namespace Flamarkt\Inventory\Api\Controller;

use Flamarkt\Inventory\Api\Serializer\HistorySerializer;
use Flamarkt\Inventory\HistoryFilterer;
use Flarum\Api\Controller\AbstractListController;
use Flarum\Http\RequestUtil;
use Flarum\Http\UrlGenerator;
use Flarum\Query\QueryCriteria;
use Flarum\User\UserRepository;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class HistoryIndexController extends AbstractListController
{
    public $serializer = HistorySerializer::class;

    public $include = [
        'order',
        'user',
    ];

    // Don't include product by default because this will usually be loaded on the product page where the product is already loaded
    public $optionalInclude = [
        'product',
    ];

    public $sortFields = [
        'createdAt',
    ];

    public $sort = [
        'createdAt' => 'desc',
    ];

    public function __construct(
        protected UserRepository  $repository,
        protected HistoryFilterer $filterer,
        protected UrlGenerator    $url
    )
    {
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $filters = $this->extractFilter($request);
        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $criteria = new QueryCriteria($actor, $filters, $sort);
        $results = $this->filterer->filter($criteria, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->to('api')->route('flamarkt.inventory.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $results->areMoreResults() ? null : 0
        );

        $this->loadRelations($results->getResults(), $include);

        return $results->getResults();
    }
}
