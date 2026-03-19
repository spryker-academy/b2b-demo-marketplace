<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise4;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContactRequestTransfer;
use SprykerAcademy\Zed\ContactRequest\Business\Writer\ContactRequestWriter;
use SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestEntityManagerInterface;

/**
 * Exercise 4: Module Layers - ContactRequestWriter
 *
 * Verifies that the ContactRequestWriter delegates to the EntityManager
 * and returns the created message.
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise4
 */
class ContactRequestWriterTest extends Unit
{
    public function testCreateDelegatesToEntityManagerAndReturnsResult(): void
    {
        $this->assertWriterClassExists();

        // Arrange
        $inputTransfer = new ContactRequestTransfer();
        $inputTransfer->setMessage('Test Message');

        $savedTransfer = new ContactRequestTransfer();
        $savedTransfer->setIdContactRequest(42);
        $savedTransfer->setMessage('Test Message');

        $entityManagerMock = $this->createMock(ContactRequestEntityManagerInterface::class);
        $entityManagerMock->expects($this->once())
            ->method('createMessage')
            ->with($inputTransfer)
            ->willReturn($savedTransfer);

        // Act
        $writer = new ContactRequestWriter($entityManagerMock);
        $result = $writer->create($inputTransfer);

        // Assert
        $this->assertSame(
            42,
            $result->getIdContactRequest(),
            'create() must return the ContactRequestTransfer from the EntityManager.',
        );
        $this->assertSame(
            'Test Message',
            $result->getMessage(),
            'The returned message content must match.',
        );
    }

    private function assertWriterClassExists(): void
    {
        if (!class_exists(ContactRequestWriter::class)) {
            $this->markTestSkipped('ContactRequestWriter class does not exist yet.');
        }

        if (!interface_exists(ContactRequestEntityManagerInterface::class)) {
            $this->markTestSkipped('ContactRequestEntityManagerInterface does not exist yet.');
        }
    }
}
