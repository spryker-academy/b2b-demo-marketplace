<?php

declare(strict_types=1);

namespace SprykerAcademy\Client\SupplierSearch;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\Query\SupplierSearchQueryPlugin;
use SprykerAcademy\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter\SupplierSearchResultFormatterPlugin;

class SupplierSearchDependencyProvider extends AbstractDependencyProvider
{
    public const string CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const string PLUGIN_SUPPLIER_SEARCH_QUERY = 'PLUGIN_SUPPLIER_SEARCH_QUERY';
    public const string PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER = 'PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER';
    public const string PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER = 'PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSearchClient($container);
        $container = $this->addSupplierSearchQueryPlugin($container);
        $container = $this->addSupplierSearchResultFormatterPlugins($container);
        $container = $this->addSupplierSearchQueryExpanderPlugins($container);

        return $container;
    }

    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return $container->getLocator()->search()->client();
        });

        return $container;
    }

    protected function addSupplierSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SUPPLIER_SEARCH_QUERY, function (): QueryInterface {
            return new SupplierSearchQueryPlugin();
        });

        return $container;
    }

    protected function addSupplierSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SUPPLIER_SEARCH_RESULT_FORMATTER, function (): array {
            return $this->getSupplierSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getSupplierSearchResultFormatterPlugins(): array
    {
        return [
            new SupplierSearchResultFormatterPlugin(),
        ];
    }

    protected function addSupplierSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SUPPLIER_SEARCH_QUERY_EXPANDER, function (): array {
            return $this->getSupplierSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getSupplierSearchQueryExpanderPlugins(): array
    {
        return [];
    }
}
