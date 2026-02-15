<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerAcademyTest\Zed\Supplier\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Supplier\Persistence\PyzSupplierQuery;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use SprykerAcademy\Shared\SupplierSearch\SupplierSearchConfig;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\DescriptionToLowercaseStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataImportStep\SupplierWriterStep;
use SprykerAcademy\Zed\SupplierDataImport\Business\DataSet\SupplierDataSetInterface;

/**
 * @group SprykerAcademyTest
 * @group Zed
 * @group Supplier
 * @group Business
 * @group SupplierDataImportTest
 */
class SupplierDataImportTest extends Unit
{
    protected const string SUPPLIER_NAME = 'Supplier Import Test';

    protected const string SUPPLIER_DESCRIPTION_LOWERCASE = 'imported supplier description';

    protected const int STATUS_ACTIVE = 1;

    protected const string STATUS_ACTIVE_STRING = '1';

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteImportedSupplier();
        $this->resetDataImporterPublisherState();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->deleteImportedSupplier();
        $this->resetDataImporterPublisherState();

        parent::tearDown();
    }

    public function testImportSupplierPersistsSupplierData(): void
    {
        $dataSet = $this->createSupplierDataSet();
        $descriptionToLowercaseStep = new DescriptionToLowercaseStep();
        $supplierWriterStep = new SupplierWriterStep();

        $descriptionToLowercaseStep->execute($dataSet);
        $supplierWriterStep->execute($dataSet);
        $supplierWriterStep->afterExecute();

        $supplierEntity = PyzSupplierQuery::create()
            ->filterByName(static::SUPPLIER_NAME)
            ->findOne();

        $this->assertNotNull($supplierEntity);
        $this->assertSame(static::SUPPLIER_DESCRIPTION_LOWERCASE, $supplierEntity->getDescription());
        $this->assertSame(static::STATUS_ACTIVE, $supplierEntity->getStatus());
    }

    public function testImportSupplierTriggersSupplierPublishEventWhenPublishing(): void
    {
        $dataSet = $this->createSupplierDataSet();
        $supplierWriterStep = new SupplierWriterStep();

        $supplierWriterStep->execute($dataSet);
        $supplierWriterStep->afterExecute();

        $supplierEntity = PyzSupplierQuery::create()
            ->filterByName(static::SUPPLIER_NAME)
            ->findOne();
        $this->assertNotNull($supplierEntity);

        $eventFacadeMock = $this->createMock(EventFacadeInterface::class);
        $eventFacadeMock->expects($this->once())
            ->method('triggerBulk')
            ->with(
                SupplierSearchConfig::SUPPLIER_PUBLISH,
                $this->callback(function (array $eventEntityTransfers) use ($supplierEntity): bool {
                    $this->assertCount(1, $eventEntityTransfers);
                    $this->assertInstanceOf(EventEntityTransfer::class, $eventEntityTransfers[0]);
                    $this->assertSame($supplierEntity->getIdSupplier(), $eventEntityTransfers[0]->getId());

                    return true;
                }),
            );
        $this->setDataImporterPublisherEventFacade($eventFacadeMock);

        DataImporterPublisher::triggerEvents();
    }

    protected function createSupplierDataSet(): DataSet
    {
        return new DataSet([
            SupplierDataSetInterface::COLUMN_NAME => static::SUPPLIER_NAME,
            SupplierDataSetInterface::COLUMN_DESCRIPTION => 'IMPORTED SUPPLIER DESCRIPTION',
            SupplierDataSetInterface::COLUMN_STATUS => static::STATUS_ACTIVE_STRING,
            SupplierDataSetInterface::COLUMN_EMAIL => 'supplier-import-test@example.com',
            SupplierDataSetInterface::COLUMN_PHONE => '+1-202-555-0123',
            SupplierDataSetInterface::COLUMN_MERCHANT_IDS => '',
        ]);
    }

    protected function deleteImportedSupplier(): void
    {
        PyzSupplierQuery::create()
            ->filterByName(static::SUPPLIER_NAME)
            ->delete();
    }

    protected function resetDataImporterPublisherState(): void
    {
        $reflectionClass = new ReflectionClass(DataImporterPublisher::class);

        $eventFacadeProperty = $reflectionClass->getProperty('eventFacade');
        $eventFacadeProperty->setAccessible(true);
        $eventFacadeProperty->setValue(null, null);

        $importedEntityEventsProperty = $reflectionClass->getProperty('importedEntityEvents');
        $importedEntityEventsProperty->setAccessible(true);
        $importedEntityEventsProperty->setValue(null, []);

        $triggeredEventIdsProperty = $reflectionClass->getProperty('triggeredEventIds');
        $triggeredEventIdsProperty->setAccessible(true);
        $triggeredEventIdsProperty->setValue(null, []);
    }

    protected function setDataImporterPublisherEventFacade(EventFacadeInterface $eventFacade): void
    {
        $reflectionClass = new ReflectionClass(DataImporterPublisher::class);
        $eventFacadeProperty = $reflectionClass->getProperty('eventFacade');
        $eventFacadeProperty->setAccessible(true);
        $eventFacadeProperty->setValue(null, $eventFacade);
    }
}
