<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademy\Zed\SupplierGui\Communication\Controller;

use Generated\Shared\Transfer\SupplierTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerAcademy\Zed\SupplierGui\Communication\SupplierGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const string URL_SUPPLIER_OVERVIEW = '/supplier-gui';

    public const string REQUEST_PARAM_ID_SUPPLIER = 'id-supplier';

    protected const string MESSAGE_SUPPLIER_DELETED_SUCCESS = 'Supplier was successfully deleted.';

    /**
     * @param \SprykerAcademy\Zed\Supplier\Business\SupplierFacadeInterface $supplierFacade
     */
    public function __construct(protected SupplierFacadeInterface $supplierFacade)
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function indexAction(Request $request): RedirectResponse
    {
        // TODO-1: Read supplier id from request by using REQUEST_PARAM_ID_SUPPLIER.
        $idSupplier = $this->castId($request->query->get(static::REQUEST_PARAM_ID_SUPPLIER));

        // TODO-2: Validate supplier id and return to overview with an error message when invalid.
        if ($idSupplier <= 0) {
            $this->addErrorMessage('Supplier was not found.');

            return $this->redirectResponse($this->getSupplierOverviewUrl());
        }

        // TODO-3: Delete supplier via facade by passing a SupplierTransfer with idSupplier.
        $this->supplierFacade->deleteSupplier(
            (new SupplierTransfer())->setIdSupplier($idSupplier),
        );

        // TODO-4: Add a success message after deletion.
        $this->addSuccessMessage(static::MESSAGE_SUPPLIER_DELETED_SUCCESS);

        // TODO-5: Redirect to supplier overview page.
        return $this->redirectResponse($this->getSupplierOverviewUrl());
    }

    protected function getSupplierOverviewUrl(): string
    {
        return (string)Url::generate(static::URL_SUPPLIER_OVERVIEW);
    }
}
