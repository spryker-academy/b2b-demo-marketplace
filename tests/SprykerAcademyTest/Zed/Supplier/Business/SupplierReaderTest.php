<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademyTest\Zed\Supplier\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SupplierCriteriaTransfer;
use Generated\Shared\Transfer\SupplierTransfer;
use SprykerAcademy\Zed\Supplier\Business\Reader\SupplierReader;
use SprykerAcademy\Zed\Supplier\Persistence\SupplierRepositoryInterface;

/**
 * @group SprykerAcademyTest
 * @group Zed
 * @group Supplier
 * @group Business
 * @group SupplierReaderTest
 */
class SupplierReaderTest extends Unit
{
    public function testGetSuppliersReturnsRepositoryResult(): void
    {
        $supplierCriteriaTransfer = new SupplierCriteriaTransfer();
        $expectedSupplierTransfers = [(new SupplierTransfer())->setIdSupplier(1)];

        $supplierRepositoryMock = $this->createMock(SupplierRepositoryInterface::class);
        $supplierRepositoryMock->expects($this->once())
            ->method('getSuppliers')
            ->with($supplierCriteriaTransfer)
            ->willReturn($expectedSupplierTransfers);

        $supplierReader = new SupplierReader($supplierRepositoryMock);

        $actualSupplierTransfers = $supplierReader->getSuppliers($supplierCriteriaTransfer);

        $this->assertSame($expectedSupplierTransfers, $actualSupplierTransfers);
    }

    public function testFindSupplierByIdReturnsRepositoryResult(): void
    {
        $idSupplier = 7;
        $expectedSupplierTransfer = (new SupplierTransfer())->setIdSupplier($idSupplier);

        $supplierRepositoryMock = $this->createMock(SupplierRepositoryInterface::class);
        $supplierRepositoryMock->expects($this->once())
        ->method('findSupplierById')
        ->with($idSupplier)
        ->willReturn($expectedSupplierTransfer);

        $supplierReader = new SupplierReader($supplierRepositoryMock);

        $actualSupplierTransfer = $supplierReader->findSupplierById($idSupplier);

        $this->assertSame($expectedSupplierTransfer, $actualSupplierTransfer);
    }
}
