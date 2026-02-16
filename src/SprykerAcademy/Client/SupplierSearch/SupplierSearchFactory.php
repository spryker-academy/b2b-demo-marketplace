<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerAcademy\Client\SupplierSearch\Reader\SupplierSearchReader;
use SprykerAcademy\Client\SupplierSearch\Reader\SupplierSearchReaderInterface;

class SupplierSearchFactory extends AbstractFactory
{
    public function createSupplierSearchReader(): SupplierSearchReaderInterface
    {
        return new SupplierSearchReader(
            $this->getSearchClient(),
            $this->getSupplierSearchQueryPlugin(),
            $this->getSupplierSearchQueryExpanderPlugins(),
            $this->getSupplierSearchResultFormatterPlugins(),
        );
    }

    public function getSearchClient(): SearchClientInterface
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::CLIENT_SEARCH);
    }

    public function getSupplierSearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::PLUGIN_SUPPLIER_SEARCH_QUERY);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    public function getSupplierSearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSupplierSearchResultFormatterPlugins(): array
    {
        return $this->getProvidedDependency(SupplierSearchDependencyProvider::PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER);
    }
}
