<?php

namespace Pyz\Client\SupplierSearch;

use Pyz\Client\SupplierSearch\Plugin\Elasticsearch\Query\SupplierSearchQueryPlugin;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\SearchClientInterface;

class SupplierSearchFactory extends AbstractFactory
{
    /**
     * @param string $name
     *
     * @return \Pyz\Client\SupplierSearch\Plugin\Elasticsearch\Query\SupplierSearchQueryPlugin
     */
    public function createSupplierQueryPlugin(string $name): SupplierSearchQueryPlugin
    {
        return new SupplierSearchQueryPlugin($name);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSearchQueryFormatters(): array
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::SUPPLIER_SEARCH_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::CLIENT_SEARCH);
    }
}
