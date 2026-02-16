<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Query\Term;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

class SupplierByIdSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const string SOURCE_IDENTIFIER = SupplierSearchConfig::SUPPLIER_SOURCE_IDENTIFIER;

    protected const string RESOURCE_TYPE = SupplierSearchConfig::SUPPLIER_RESOURCE_TYPE;

    protected Query $query {
        get => $field ??= $this->createSearchQuery();
    }

    protected ?SearchContextTransfer $searchContextTransfer = null {
        get => $field ??= new SearchContextTransfer()
            ->setSourceIdentifier(static::SOURCE_IDENTIFIER);
    }

    public function __construct(
        protected int $idSupplier,
    ) {
    }

    protected function createSearchQuery(): Query
    {
        $query = new Query();
        $boolQuery = new BoolQuery();

        $boolQuery->addMust(new MatchQuery(SupplierSearchConfig::KEY_TYPE, static::RESOURCE_TYPE));
        $boolQuery->addMust(new Term([SupplierSearchConfig::KEY_ID_SUPPLIER => $this->idSupplier]));

        $query->setQuery($boolQuery);
        $query->setSize(1);

        return $query;
    }

    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    public function getSearchContext(): SearchContextTransfer
    {
        return $this->searchContextTransfer;
    }

    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }
}
