<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication;

use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerAcademy\Zed\SupplierGui\Communication\Form\SupplierCreateForm;
use Symfony\Component\Form\FormInterface;

class SupplierGuiCommunicationFactory extends AbstractCommunicationFactory
{
    // (For the Table part of the exercise)
    // TODO-1: Provide the PyzSupplierQuery from the SupplierGuiDependencyProvider
    // Hint-1: Naming convention for methods getting things from somewhere else are prefixed by "get"
    // i.e.: getMyClassName()
    // Hint-2: Have a look at `src/Pyz/Zed/DataImport/Business/DataImportBusinessFactory.php::getCurrencyFacade()` for the right syntax

    // (For the Table part of the exercise)
    // TODO-2: Instantiate the SupplierTable with the right dependency and return it
    // Hint-1: Naming convention for methods instantiating classes would be the class name prefixed by "create"
    // i.e.: createMyClassName()

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer|null $supplierTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSupplierCreateForm(?SupplierTransfer $supplierTransfer = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(SupplierCreateForm::class, $supplierTransfer, $options);
    }

    // (Later: For the Form part of the exercise)
    // TODO-3: Provide the SupplierFacade from the SupplierGuiDependencyProvider
    // Hint-1: Naming convention for methods getting things from somewhere else are prefixed by "get"
    // i.e.: getMyClassName()
    // Hint-2: Have a look at `src/Pyz/Zed/DataImport/Business/DataImportBusinessFactory.php::getCurrencyFacade()` for the right syntax
    // Hint-3: Use the interface as return type
}
