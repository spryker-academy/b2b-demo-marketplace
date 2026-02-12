<?php

namespace Pyz\Glue\SuppliersApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SuppliersApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_ANTELOPES = 'suppliers';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_SUPPLIER_NOT_FOUND = '9404';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAIL_SUPPLIER_NOT_FOUND = 'Supplier not found.';
}
