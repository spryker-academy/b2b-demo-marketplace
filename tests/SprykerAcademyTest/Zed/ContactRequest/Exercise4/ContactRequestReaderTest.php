<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise4;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestTransfer;
use SprykerAcademy\Zed\ContactRequest\Business\Reader\ContactRequestReader;
use SprykerAcademy\Zed\ContactRequest\Persistence\ContactRequestRepositoryInterface;

/**
 * Exercise 4: Module Layers - ContactRequestReader
 *
 * Verifies that the ContactRequestReader correctly uses the Repository
 * and returns appropriate responses.
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise4
 */
class ContactRequestReaderTest extends Unit
{
    public function testFindMessageReturnsSuccessfulResponseWhenMessageFound(): void
    {
        $this->assertReaderClassExists();

        // Arrange
        $contactRequestTransfer = new ContactRequestTransfer();
        $contactRequestTransfer->setIdContactRequest(1);
        $contactRequestTransfer->setMessage('Hello');

        $repositoryMock = $this->createMock(ContactRequestRepositoryInterface::class);
        $repositoryMock->method('findMessage')
            ->willReturn($contactRequestTransfer);

        $contactRequestCriteria = new ContactRequestCriteriaTransfer();
        $contactRequestCriteria->setIdContactRequest(1);

        // Act
        $reader = new ContactRequestReader($repositoryMock);
        $response = $reader->findContactRequest($contactRequestCriteria);

        // Assert
        $this->assertTrue(
            $response->getIsSuccessful(),
            'findContactRequest() must return isSuccessful=true when a message is found.',
        );
        $this->assertNotNull(
            $response->getMessage(),
            'findContactRequest() must set the message on the response when found.',
        );
        $this->assertSame(
            'Hello',
            $response->getMessage()->getMessage(),
            'The returned message must match the one from the Repository.',
        );
    }

    public function testFindMessageReturnsUnsuccessfulResponseWhenMessageNotFound(): void
    {
        $this->assertReaderClassExists();

        // Arrange
        $repositoryMock = $this->createMock(ContactRequestRepositoryInterface::class);
        $repositoryMock->method('findMessage')
            ->willReturn(null);

        $contactRequestCriteria = new ContactRequestCriteriaTransfer();
        $contactRequestCriteria->setIdContactRequest(999);

        // Act
        $reader = new ContactRequestReader($repositoryMock);
        $response = $reader->findContactRequest($contactRequestCriteria);

        // Assert
        $this->assertFalse(
            $response->getIsSuccessful(),
            'findContactRequest() must return isSuccessful=false when no message is found.',
        );
    }

    private function assertReaderClassExists(): void
    {
        if (!class_exists(ContactRequestReader::class)) {
            $this->markTestSkipped('ContactRequestReader class does not exist yet.');
        }

        if (!interface_exists(ContactRequestRepositoryInterface::class)) {
            $this->markTestSkipped('ContactRequestRepositoryInterface does not exist yet.');
        }
    }
}
