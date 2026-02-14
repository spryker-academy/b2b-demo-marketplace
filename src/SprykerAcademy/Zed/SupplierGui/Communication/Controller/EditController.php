<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerAcademy\Zed\SupplierGui\Communication\Form\SupplierCreateForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerAcademy\Zed\SupplierGui\Communication\SupplierGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const URL_SUPPLIER_OVERVIEW = '/supplier-gui';

    public const REQUEST_PARAM_ID_SUPPLIER = 'id-supplier';

    protected const MESSAGE_SUPPLIER_UPDATED_SUCCESS = 'Supplier was successfully updated.';

    protected const STATUS_ACTIVE = 1;

    protected const STATUS_INACTIVE = 0;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        $idSupplier = $this->castId($request->query->get(static::REQUEST_PARAM_ID_SUPPLIER));
        $supplierTransfer = $this->getFactory()->getSupplierFacade()->findSupplierById($idSupplier);

        if ($supplierTransfer === null) {
            $this->addErrorMessage('Supplier was not found.');

            return $this->redirectResponse($this->getSupplierOverviewUrl());
        }

        $supplierCreateForm = $this->getFactory()->createSupplierCreateForm(
            $supplierTransfer,
            [SupplierCreateForm::FIELD_IS_ACTIVE => (int)$supplierTransfer->getStatus() === static::STATUS_ACTIVE],
        );
        $supplierCreateForm->handleRequest($request);

        if ($supplierCreateForm->isSubmitted() && $supplierCreateForm->isValid()) {
            return $this->updateSupplier($supplierCreateForm);
        }

        return $this->viewResponse([
            'supplierCreateForm' => $supplierCreateForm->createView(),
            'backUrl' => $this->getSupplierOverviewUrl(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $supplierCreateForm
     */
    protected function updateSupplier(FormInterface $supplierCreateForm): RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\SupplierTransfer|null $supplierTransfer */
        $supplierTransfer = $supplierCreateForm->getData();

        if ($supplierTransfer === null) {
            return $this->redirectResponse($this->getSupplierOverviewUrl());
        }

        $supplierTransfer->setStatus(
            $supplierCreateForm->get(SupplierCreateForm::FIELD_IS_ACTIVE)->getData() ? static::STATUS_ACTIVE : static::STATUS_INACTIVE,
        );
        $this->getFactory()->getSupplierFacade()->updateSupplier($supplierTransfer);
        $this->addSuccessMessage(static::MESSAGE_SUPPLIER_UPDATED_SUCCESS);

        return $this->redirectResponse($this->getSupplierOverviewUrl());
    }

    protected function getSupplierOverviewUrl(): string
    {
        return (string)Url::generate(static::URL_SUPPLIER_OVERVIEW);
    }
}
