<?php

namespace Pyz\Client\SupplierSearch;

use Pyz\Client\SupplierSearch\Plugin\Elasticsearch\ResultFormatter\SupplierSearchResultFormatterPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Search\SearchClientInterface;

class SupplierSearchDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    public const SUPPLIER_SEARCH_RESULT_FORMATTER_PLUGINS = 'SUPPLIER_SEARCH_RESULT_FORMATTER_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addSearchClient($container);
        $container = $this->addSupplierSearchResultFormatterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container): Container
    {
        $container[static::CLIENT_SEARCH] = function (Container $container): SearchClientInterface {
            return $container->getLocator()->search()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addSupplierSearchResultFormatterPlugins(Container $container): Container
    {
        $container[static::SUPPLIER_SEARCH_RESULT_FORMATTER_PLUGINS] = function () {
            return $this->getSupplierSearchResultFormatterPlugins();
        };

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    public function getSupplierSearchResultFormatterPlugins(): array
    {
        return [
            new SupplierSearchResultFormatterPlugin(),
        ];
    }
}
