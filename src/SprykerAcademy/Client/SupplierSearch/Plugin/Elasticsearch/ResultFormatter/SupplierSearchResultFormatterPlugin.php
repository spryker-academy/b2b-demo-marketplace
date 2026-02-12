<?php

namespace Pyz\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class SupplierSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'supplier';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\SupplierTransfer|null
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): ?SupplierTransfer
    {
        foreach ($searchResult->getResults() as $document) {
            return (new SupplierTransfer())->fromArray($document->getSource());
        }

        return null;
    }
}
