<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Controller;

use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerAcademy\Zed\SupplierGui\Communication\Form\SupplierCreateForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \SprykerAcademy\Zed\SupplierGui\Communication\SupplierGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    protected const URL_SUPPLIER_OVERVIEW = '/supplier-gui';

    protected const URL_SUPPLIER_CREATE = '/supplier-gui/create';

    protected const MESSAGE_SUPPLIER_CREATED_SUCCESS = 'Supplier was successfully created.';

    protected const MESSAGE_SUPPLIER_EXISTS = 'Supplier with this name already exists.';

    protected const MESSAGE_SUPPLIER_CREATE_FAILED = 'Supplier could not be created.';

    protected const STATUS_ACTIVE = 1;

    protected const STATUS_INACTIVE = 0;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        $supplierCreateForm = $this->getFactory()->createSupplierCreateForm(
            new SupplierTransfer(),
            [SupplierCreateForm::FIELD_IS_ACTIVE => true],
        );

        $supplierCreateForm->handleRequest($request);

        if ($supplierCreateForm->isSubmitted() && $supplierCreateForm->isValid()) {
            /** @var \Generated\Shared\Transfer\SupplierTransfer|null $supplierTransfer */
            $supplierTransfer = $supplierCreateForm->getData();

            if ($supplierTransfer !== null && $this->isDuplicateSupplierName($supplierTransfer)) {
                $supplierCreateForm->get(SupplierCreateForm::FIELD_NAME)->addError(new FormError(static::MESSAGE_SUPPLIER_EXISTS));

                return $this->viewResponse([
                    'supplierCreateForm' => $supplierCreateForm->createView(),
                    'backUrl' => $this->getSupplierOverviewUrl(),
                ]);
            }

            return $this->createSupplier($supplierCreateForm);
        }

        return $this->viewResponse([
            'supplierCreateForm' => $supplierCreateForm->createView(),
            'backUrl' => $this->getSupplierOverviewUrl(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $supplierCreateForm
     */
    protected function createSupplier(FormInterface $supplierCreateForm): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\SupplierTransfer|null $supplierTransfer */
        $supplierTransfer = $supplierCreateForm->getData();

        if ($supplierTransfer === null) {
            return $this->redirectResponse($this->getSupplierOverviewUrl());
        }

        $supplierTransfer->setStatus(
            $supplierCreateForm->get(SupplierCreateForm::FIELD_IS_ACTIVE)->getData() ? static::STATUS_ACTIVE : static::STATUS_INACTIVE,
        );

        try {
            $this->getFactory()->getSupplierFacade()->createSupplier($supplierTransfer);
        } catch (Throwable) {
            $this->addErrorMessage(static::MESSAGE_SUPPLIER_CREATE_FAILED);

            return $this->redirectResponse(static::URL_SUPPLIER_CREATE);
        }

        $this->addSuccessMessage(static::MESSAGE_SUPPLIER_CREATED_SUCCESS);

        return $this->redirectResponse($this->getSupplierOverviewUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\SupplierTransfer $supplierTransfer
     */
    protected function isDuplicateSupplierName(SupplierTransfer $supplierTransfer): bool
    {
        $supplierName = $supplierTransfer->getName();

        if ($supplierName === null || $supplierName === '') {
            return false;
        }

        $supplierCriteriaTransfer = (new SupplierCriteriaTransfer())
            ->setName($supplierName);

        return $this->getFactory()->getSupplierFacade()->getSuppliers($supplierCriteriaTransfer) !== [];
    }

    protected function getSupplierOverviewUrl(): string
    {
        return (string)Url::generate(static::URL_SUPPLIER_OVERVIEW);
    }
}
