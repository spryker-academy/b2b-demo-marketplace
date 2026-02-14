<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierGui\Communication\Form\SupplierCreateForm;
use SprykerAcademy\Zed\SupplierGui\Communication\Table\SupplierTable;
use SprykerAcademy\Zed\SupplierGui\SupplierGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class SupplierGuiCommunicationFactory extends AbstractCommunicationFactory
{
    public function getSupplierQuery(): PyzSupplierQuery
    {
        return $this->getProvidedDependency(SupplierGuiDependencyProvider::PROPEL_QUERY_SUPPLIER);
    }

    public function createSupplierTable(): SupplierTable
    {
        return new SupplierTable($this->getSupplierQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer|null $supplierTransfer
     * @param array $options
     */
    public function createSupplierCreateForm(?SupplierTransfer $supplierTransfer = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(SupplierCreateForm::class, $supplierTransfer, $options);
    }

    public function getSupplierFacade(): SupplierFacadeInterface
    {
        return $this->getProvidedDependency(SupplierGuiDependencyProvider::FACADE_SUPPLIER);
    }
}
