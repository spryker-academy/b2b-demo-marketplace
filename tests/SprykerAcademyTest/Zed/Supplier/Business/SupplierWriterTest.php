<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademyTest\Zed\Supplier\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SupplierTransfer;
use SprykerAcademy\Zed\Supplier\Business\Writer\SupplierWriter;
use SprykerAcademy\Zed\Supplier\Persistence\SupplierEntityManagerInterface;

/**
 * @group SprykerAcademyTest
 * @group Zed
 * @group Supplier
 * @group Business
 * @group SupplierWriterTest
 */
class SupplierWriterTest extends Unit
{
    public function testCreateDelegatesToEntityManager(): void
    {
        $supplierTransfer = (new SupplierTransfer())->setName('Supplier A');
        $expectedSupplierTransfer = (new SupplierTransfer())->setIdSupplier(1)->setName('Supplier A');

        $supplierEntityManagerMock = $this->createMock(SupplierEntityManagerInterface::class);
        $supplierEntityManagerMock->expects($this->once())
            ->method('createSupplier')
            ->with($supplierTransfer)
            ->willReturn($expectedSupplierTransfer);

        $supplierWriter = new SupplierWriter($supplierEntityManagerMock);

        $actualSupplierTransfer = $supplierWriter->create($supplierTransfer);

        $this->assertSame($expectedSupplierTransfer, $actualSupplierTransfer);
    }

    public function testUpdateDelegatesToEntityManager(): void
    {
        $supplierTransfer = (new SupplierTransfer())->setIdSupplier(2)->setName('Supplier B');
        $expectedSupplierTransfer = (new SupplierTransfer())->setIdSupplier(2)->setName('Supplier B updated');

        $supplierEntityManagerMock = $this->createMock(SupplierEntityManagerInterface::class);
        $supplierEntityManagerMock->expects($this->once())
        ->method('updateSupplier')
        ->with($supplierTransfer)
        ->willReturn($expectedSupplierTransfer);

        $supplierWriter = new SupplierWriter($supplierEntityManagerMock);

        $actualSupplierTransfer = $supplierWriter->update($supplierTransfer);

        $this->assertSame($expectedSupplierTransfer, $actualSupplierTransfer);
    }

    public function testDeleteDelegatesToEntityManager(): void
    {
        $supplierTransfer = (new SupplierTransfer())->setIdSupplier(3);

        $supplierEntityManagerMock = $this->createMock(SupplierEntityManagerInterface::class);
        $supplierEntityManagerMock->expects($this->once())
        ->method('deleteSupplier')
        ->with($supplierTransfer);

        $supplierWriter = new SupplierWriter($supplierEntityManagerMock);
        $supplierWriter->delete($supplierTransfer);
    }
}
