<?php

namespace Pyz\Glue\SuppliersApi\Processor\Reader;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Pyz\Client\SupplierSearch\SupplierSearchClientInterface;
use Pyz\Glue\SuppliersApi\SuppliersApiConfig;
use Pyz\Glue\SuppliersApi\Processor\Mapper\SupplierMapper;
use Symfony\Component\HttpFoundation\Response;

class SupplierReader
{
    protected SupplierSearchClientInterface $supplierSearchClient;

    protected SupplierMapper $supplierMapper;

    /**
     * @param \Pyz\Client\SupplierSearch\SupplierSearchClientInterface $supplierSearchClient
     * @param \Pyz\Glue\SuppliersApi\Processor\Mapper\SupplierMapper $supplierMapper
     */
    public function __construct(
        SupplierSearchClientInterface $supplierSearchClient,
        SupplierMapper $supplierMapper,
    ) {
        $this->supplierSearchClient = $supplierSearchClient;
        $this->supplierMapper = $supplierMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getSupplier(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer = new GlueResponseTransfer();
        $supplierName = $glueRequestTransfer->getResourceOrFail()->getIdOrFail();

        $supplierTransfer = $this->supplierSearchClient->getSupplierByName($supplierName);

        if (!$supplierTransfer) {
            return $this->addSupplierNotFoundError($glueResponseTransfer);
        }

        $suppliersApiAttributesTransfer = $this->supplierMapper->mapSupplierTransferToSuppliersApiAttributesTransfer($supplierTransfer);

        $glueResourceTransfer = (new GlueResourceTransfer())
            ->setId($suppliersApiAttributesTransfer->getNameOrFail())
            ->setType(SuppliersApiConfig::RESOURCE_ANTELOPES)
            ->setAttributes($suppliersApiAttributesTransfer);

        $glueResponseTransfer->addResource($glueResourceTransfer);

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function addSupplierNotFoundError(GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        return $glueResponseTransfer
            ->setHttpStatus(Response::HTTP_NOT_FOUND)
            ->addError(
                (new GlueErrorTransfer())
                    ->setCode(SuppliersApiConfig::RESPONSE_CODE_SUPPLIER_NOT_FOUND)
                    ->setMessage(SuppliersApiConfig::RESPONSE_DETAIL_SUPPLIER_NOT_FOUND)
                    ->setStatus(Response::HTTP_NOT_FOUND),
            );
    }
}
