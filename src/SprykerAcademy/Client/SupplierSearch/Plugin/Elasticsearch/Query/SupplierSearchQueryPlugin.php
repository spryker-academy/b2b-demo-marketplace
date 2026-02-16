<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

class SupplierSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    /**
     * @var string
     */
    protected const string SOURCE_IDENTIFIER = 'page';

    /**
     * @var string
     */
    protected const string RESOURCE_TYPE = 'supplier';

    protected Query $query;

    protected ?SearchContextTransfer $searchContextTransfer = null;

    public function __construct()
    {
        $this->query = $this->createSearchQuery();
    }

    protected function createSearchQuery(): Query
    {
        $query = new Query();
        $boolQuery = new BoolQuery();

        $boolQuery->addMust(new MatchQuery('type', static::RESOURCE_TYPE));

        $query->setQuery($boolQuery);

        return $query;
    }

    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    public function getSearchContext(): SearchContextTransfer
    {
        if ($this->searchContextTransfer === null) {
            $this->searchContextTransfer = new SearchContextTransfer()
                ->setSourceIdentifier(static::SOURCE_IDENTIFIER);
        }

        return $this->searchContextTransfer;
    }

    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }
}
