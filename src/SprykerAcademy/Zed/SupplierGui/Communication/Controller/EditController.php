<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use SprykerAcademy\Zed\SupplierGui\Communication\Form\SupplierCreateForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerAcademy\Zed\SupplierGui\Communication\SupplierGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const string URL_SUPPLIER_OVERVIEW = '/supplier-gui';

    public const string REQUEST_PARAM_ID_SUPPLIER = 'id-supplier';

    protected const string MESSAGE_SUPPLIER_UPDATED_SUCCESS = 'Supplier was successfully updated.';

    protected const int STATUS_ACTIVE = 1;

    protected const int STATUS_INACTIVE = 0;

    /**
     * @param \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface $supplierFacade
     */
    public function __construct(protected SupplierFacadeInterface $supplierFacade)
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function indexAction(Request $request): RedirectResponse|array
    {
        // TODO-1: Read supplier id from request and fetch supplier transfer from facade.
        $idSupplier = $this->castId($request->query->get(static::REQUEST_PARAM_ID_SUPPLIER));
        $supplierTransfer = $this->supplierFacade->findSupplierById($idSupplier);

        // TODO-2: Handle missing supplier and redirect to overview with an error message.
        if ($supplierTransfer === null) {
            $this->addErrorMessage('Supplier was not found.');

            return $this->redirectResponse($this->getSupplierOverviewUrl());
        }

        // TODO-3: Create and handle form prefilled with supplier data.
        $supplierCreateForm = $this->getFactory()->createSupplierCreateForm(
            $supplierTransfer,
            [SupplierCreateForm::FIELD_IS_ACTIVE => (int)$supplierTransfer->getStatus() === static::STATUS_ACTIVE],
        );
        $supplierCreateForm->handleRequest($request);

        if ($supplierCreateForm->isSubmitted() && $supplierCreateForm->isValid()) {
            // TODO-4: Delegate update logic into updateSupplier().
            return $this->updateSupplier($supplierCreateForm);
        }

        return $this->viewResponse([
            'supplierCreateForm' => $supplierCreateForm->createView(),
            'backUrl' => $this->getSupplierOverviewUrl(),
        ]);
    }

    protected function getSupplierOverviewUrl(): string
    {
        return (string)Url::generate(static::URL_SUPPLIER_OVERVIEW);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $supplierCreateForm
     */
    protected function updateSupplier(FormInterface $supplierCreateForm): RedirectResponse
    {
        // TODO-5: Read supplier transfer from form data and validate it.
        $supplierTransfer = $supplierCreateForm->getData();

        if ($supplierTransfer === null) {
            return $this->redirectResponse($this->getSupplierOverviewUrl());
        }

        // TODO-6: Map form active checkbox to integer status flag.
        $supplierTransfer->setStatus(
            $supplierCreateForm->get(SupplierCreateForm::FIELD_IS_ACTIVE)->getData(
            ) ? static::STATUS_ACTIVE : static::STATUS_INACTIVE,
        );
        // TODO-7: Persist updated supplier and notify user.
        $this->supplierFacade->updateSupplier($supplierTransfer);
        $this->addSuccessMessage(static::MESSAGE_SUPPLIER_UPDATED_SUCCESS);

        // TODO-8: Redirect back to supplier overview.
        return $this->redirectResponse($this->getSupplierOverviewUrl());
    }
}
