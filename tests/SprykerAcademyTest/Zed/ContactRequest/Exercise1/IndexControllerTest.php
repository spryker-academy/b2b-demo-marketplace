<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise1;

use Codeception\Test\Unit;

/**
 * Exercise 1: Hello World Back Office
 *
 * Verifies that students created a IndexController with an indexAction
 * that returns 'Contact Requests' via viewResponse().
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise1
 */
class IndexControllerTest extends Unit
{
    private const CONTROLLER_CLASS = 'SprykerAcademy\Zed\ContactRequest\Communication\Controller\IndexController';
    private const ABSTRACT_CONTROLLER_CLASS = 'Spryker\Zed\Kernel\Communication\Controller\AbstractController';

    public function testIndexControllerClassExists(): void
    {
        $this->assertTrue(
            class_exists(self::CONTROLLER_CLASS),
            sprintf(
                'Class %s does not exist. Did you create the IndexController?',
                self::CONTROLLER_CLASS,
            ),
        );
    }

    public function testIndexControllerExtendsAbstractController(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('IndexController class does not exist yet.');
        }

        $this->assertTrue(
            is_subclass_of(self::CONTROLLER_CLASS, self::ABSTRACT_CONTROLLER_CLASS),
            sprintf(
                '%s must extend %s.',
                self::CONTROLLER_CLASS,
                self::ABSTRACT_CONTROLLER_CLASS,
            ),
        );
    }

    public function testIndexActionMethodExists(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('IndexController class does not exist yet.');
        }

        $this->assertTrue(
            method_exists(self::CONTROLLER_CLASS, 'indexAction'),
            'IndexController must have an indexAction() method.',
        );
    }

    public function testIndexActionReturnsContactRequestText(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('IndexController class does not exist yet.');
        }

        $reflectionMethod = new \ReflectionMethod(self::CONTROLLER_CLASS, 'indexAction');
        $source = file_get_contents($reflectionMethod->getFileName());

        $this->assertNotFalse(
            strpos($source, 'Contact Request!'),
            'indexAction() must contain the string "Contact Request!". Use viewResponse() to return it.',
        );
    }

    public function testIndexActionUsesViewResponse(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('IndexController class does not exist yet.');
        }

        $reflectionMethod = new \ReflectionMethod(self::CONTROLLER_CLASS, 'indexAction');
        $source = file_get_contents($reflectionMethod->getFileName());

        $this->assertNotFalse(
            strpos($source, 'viewResponse'),
            'indexAction() must use $this->viewResponse() to return the data.',
        );
    }
}
