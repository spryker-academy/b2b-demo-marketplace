<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise5;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContactRequestCriteriaTransfer;
use Generated\Shared\Transfer\ContactRequestResponseTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerAcademy\Client\ContactRequest\Stub\ContactRequestStub;

/**
 * Exercise 5: Module Layers - Storefront (Client Stub)
 *
 * Verifies that the ContactRequestStub calls the correct gateway path
 * to communicate between Yves and Zed.
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise5
 */
class ContactRequestStubTest extends Unit
{
    private const EXPECTED_GATEWAY_PATH = '/contact-request/gateway/find-contact-request';

    public function testFindMessageCallsCorrectGatewayPath(): void
    {
        $this->assertStubClassExists();

        // Arrange
        $contactRequestCriteria = new ContactRequestCriteriaTransfer();
        $contactRequestCriteria->setIdContactRequest(1);

        $expectedResponse = new ContactRequestResponseTransfer();
        $expectedResponse->setIsSuccessful(true);

        $zedRequestClientMock = $this->createMock(ZedRequestClientInterface::class);
        $zedRequestClientMock->expects($this->once())
            ->method('call')
            ->with(
                self::EXPECTED_GATEWAY_PATH,
                $contactRequestCriteria,
            )
            ->willReturn($expectedResponse);

        // Act
        $stub = new ContactRequestStub($zedRequestClientMock);
        $result = $stub->findContactRequest($contactRequestCriteria);

        // Assert
        $this->assertInstanceOf(
            ContactRequestResponseTransfer::class,
            $result,
            'findContactRequest() must return a ContactRequestResponseTransfer.',
        );
    }

    public function testStubUsesZedRequestClient(): void
    {
        $this->assertStubClassExists();

        $reflection = new \ReflectionClass(ContactRequestStub::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull(
            $constructor,
            'ContactRequestStub must have a constructor.',
        );

        $params = $constructor->getParameters();
        $this->assertGreaterThanOrEqual(
            1,
            count($params),
            'ContactRequestStub constructor must accept at least one parameter (ZedRequestClientInterface).',
        );
    }

    private function assertStubClassExists(): void
    {
        if (!class_exists(ContactRequestStub::class)) {
            $this->markTestSkipped('ContactRequestStub class does not exist yet.');
        }

        if (!interface_exists(ZedRequestClientInterface::class)) {
            $this->markTestSkipped('ZedRequestClientInterface is not available.');
        }
    }
}
