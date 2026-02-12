<?php

namespace Pyz\Zed\SupplierGui\Communication;

use Generated\Shared\Transfer\SupplierTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use Pyz\Zed\Supplier\Business\SupplierFacadeInterface;
use Pyz\Zed\SupplierGui\SupplierGuiDependencyProvider;
use Pyz\Zed\SupplierGui\Communication\Form\SupplierCreateForm;
use Pyz\Zed\SupplierGui\Communication\Table\SupplierTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class SupplierGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Orm\Zed\Supplier\Persistence\PyzSupplierQuery
     */
    public function getSupplierPropelQuery(): PyzSupplierQuery
    {
        return $this->getProvidedDependency(SupplierGuiDependencyProvider::PROPEL_QUERY_ANTELOPE);
    }

    /**
     * @return \Pyz\Zed\SupplierGui\Communication\Table\SupplierTable
     */
    public function createSupplierTable(): SupplierTable
    {
        return new SupplierTable($this->getSupplierPropelQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSupplierCreateForm(SupplierTransfer $supplierTransfer, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(SupplierCreateForm::class, $supplierTransfer, $options);
    }

    /**
     * @return \Pyz\Zed\Supplier\Business\SupplierFacadeInterface
     */
    public function getSupplierFacade(): SupplierFacadeInterface
    {
        return $this->getProvidedDependency(SupplierGuiDependencyProvider::FACADE_ANTELOPE);
    }
}
