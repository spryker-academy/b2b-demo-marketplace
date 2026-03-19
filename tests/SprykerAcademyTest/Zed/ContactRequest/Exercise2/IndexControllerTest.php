<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise2;

use Codeception\Test\Unit;

/**
 * Exercise 2: Data Transfer Object - Controller
 *
 * Verifies that students updated IndexController to use ContactRequestTransfer
 * and pass it to viewResponse() under the 'contactRequest' key.
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise2
 */
class IndexControllerTest extends Unit
{
    private const CONTROLLER_CLASS = 'SprykerAcademy\Zed\ContactRequest\Communication\Controller\IndexController';
    private const TRANSFER_CLASS = 'Generated\Shared\Transfer\ContactRequestTransfer';

    private function skipIfNoController(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('IndexController class does not exist yet.');
        }
    }

    private function getControllerSource(): string
    {
        $reflectionClass = new \ReflectionClass(self::CONTROLLER_CLASS);

        return file_get_contents($reflectionClass->getFileName());
    }

    public function testIndexControllerUsesContactRequestTransfer(): void
    {
        $this->skipIfNoController();

        $source = $this->getControllerSource();

        $this->assertNotFalse(
            strpos($source, 'ContactRequestTransfer'),
            'indexAction() must instantiate a ContactRequestTransfer object.',
        );
    }

    public function testIndexControllerSetsMessageOnTransfer(): void
    {
        $this->skipIfNoController();

        $source = $this->getControllerSource();

        $this->assertNotFalse(
            strpos($source, 'setMessage'),
            'indexAction() must call setMessage() on the ContactRequestTransfer.',
        );
    }

    public function testIndexControllerSetsMessageValueContactRequest(): void
    {
        $this->skipIfNoController();

        $source = $this->getControllerSource();

        $this->assertNotFalse(
            stripos($source, 'contact request!'),
            'indexAction() must set the message to "Contact Request!" (case insensitive).',
        );
    }

    public function testIndexControllerSetsIdContactRequestOnTransfer(): void
    {
        $this->skipIfNoController();

        $source = $this->getControllerSource();

        $this->assertNotFalse(
            strpos($source, 'setIdContactRequest'),
            'indexAction() must call setIdContactRequest() on the ContactRequestTransfer.',
        );
    }

    public function testIndexControllerSetsIdContactRequestToOne(): void
    {
        $this->skipIfNoController();

        $source = $this->getControllerSource();

        $this->assertNotFalse(
            strpos($source, 'setIdContactRequest(1)'),
            'indexAction() must set idContactRequest to 1.',
        );
    }

    public function testIndexControllerPassesTransferToViewUnderContactRequestKey(): void
    {
        $this->skipIfNoController();

        $source = $this->getControllerSource();

        $this->assertNotFalse(
            strpos($source, "'contactRequest'"),
            'indexAction() must pass the transfer to viewResponse() under the key "contactRequest".',
        );
    }
}
