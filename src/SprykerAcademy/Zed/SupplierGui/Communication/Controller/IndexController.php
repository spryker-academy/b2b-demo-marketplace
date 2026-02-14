<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \SprykerAcademy\Zed\SupplierGui\Communication\SupplierGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        // TODO-1: Get an instance of the SupplierTable by using the `getFactory()`-method
        $table = null;

        // TODO-2: Use the `viewResponse()`-method to return a rendered 'supplierTable'
        // Hint-1: Use the string 'supplierTable' as key for the passed array
        // Hint-2: The class AbstractTable provides a method `render()`
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        // TODO-3: Get an instance of the SupplierTable by using the `getFactory()`-method
        $table = null;

        // TODO-4: Return a json-response with the table data
        // Hint-1: The class AbstractTable provides a method `fetchData()`
    }
}
