<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise6;

use Codeception\Test\Unit;

/**
 * Exercise 6: Configuration
 *
 * Verifies that students created the ContactRequestConstants interface,
 * ContactRequestConfig class, ContactRequestCommunicationFactory, and ConfigController.
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise6
 */
class ConfigurationTest extends Unit
{
    private const CONSTANTS_CLASS = 'SprykerAcademy\Shared\ContactRequest\ContactRequestConstants';
    private const CONFIG_CLASS = 'SprykerAcademy\Zed\ContactRequest\ContactRequestConfig';
    private const FACTORY_CLASS = 'SprykerAcademy\Zed\ContactRequest\Communication\ContactRequestCommunicationFactory';
    private const CONTROLLER_CLASS = 'SprykerAcademy\Zed\ContactRequest\Communication\Controller\ConfigController';
    private const ABSTRACT_CONFIG_CLASS = 'Spryker\Zed\Kernel\AbstractBundleConfig';
    private const ABSTRACT_FACTORY_CLASS = 'Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory';
    private const ABSTRACT_CONTROLLER_CLASS = 'Spryker\Zed\Kernel\Communication\Controller\AbstractController';

    public function testConstantsInterfaceExists(): void
    {
        $this->assertTrue(
            interface_exists(self::CONSTANTS_CLASS),
            'ContactRequestConstants interface does not exist. Create it in Shared/ContactRequest/.',
        );
    }

    public function testConstantsInterfaceDefinesMyConfigValue(): void
    {
        if (!interface_exists(self::CONSTANTS_CLASS)) {
            $this->markTestSkipped('ContactRequestConstants interface does not exist yet.');
        }

        $reflection = new \ReflectionClass(self::CONSTANTS_CLASS);

        $this->assertTrue(
            $reflection->hasConstant('MY_CONFIG_VALUE'),
            'ContactRequestConstants must define a MY_CONFIG_VALUE constant.',
        );

        $value = $reflection->getConstant('MY_CONFIG_VALUE');
        $this->assertIsString($value, 'MY_CONFIG_VALUE must be a string.');
        $this->assertNotEmpty($value, 'MY_CONFIG_VALUE must not be empty.');
    }

    public function testConfigClassExists(): void
    {
        $this->assertTrue(
            class_exists(self::CONFIG_CLASS),
            'ContactRequestConfig class does not exist. Create it at Zed/ContactRequest/ContactRequestConfig.php.',
        );
    }

    public function testConfigClassExtendsAbstractBundleConfig(): void
    {
        if (!class_exists(self::CONFIG_CLASS)) {
            $this->markTestSkipped('ContactRequestConfig class does not exist yet.');
        }

        $this->assertTrue(
            is_subclass_of(self::CONFIG_CLASS, self::ABSTRACT_CONFIG_CLASS),
            'ContactRequestConfig must extend Spryker\Zed\Kernel\AbstractBundleConfig.',
        );
    }

    public function testConfigClassHasGetMyConfigValueMethod(): void
    {
        if (!class_exists(self::CONFIG_CLASS)) {
            $this->markTestSkipped('ContactRequestConfig class does not exist yet.');
        }

        $this->assertTrue(
            method_exists(self::CONFIG_CLASS, 'getMyConfigValue'),
            'ContactRequestConfig must have a getMyConfigValue() method.',
        );
    }

    public function testCommunicationFactoryExists(): void
    {
        $this->assertTrue(
            class_exists(self::FACTORY_CLASS),
            'ContactRequestCommunicationFactory does not exist. Create it in Communication/.',
        );
    }

    public function testCommunicationFactoryExtendsAbstract(): void
    {
        if (!class_exists(self::FACTORY_CLASS)) {
            $this->markTestSkipped('ContactRequestCommunicationFactory does not exist yet.');
        }

        $this->assertTrue(
            is_subclass_of(self::FACTORY_CLASS, self::ABSTRACT_FACTORY_CLASS),
            'ContactRequestCommunicationFactory must extend AbstractCommunicationFactory.',
        );
    }

    public function testConfigControllerExists(): void
    {
        $this->assertTrue(
            class_exists(self::CONTROLLER_CLASS),
            'ConfigController does not exist. Create it in Communication/Controller/.',
        );
    }

    public function testConfigControllerExtendsAbstractController(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('ConfigController does not exist yet.');
        }

        $this->assertTrue(
            is_subclass_of(self::CONTROLLER_CLASS, self::ABSTRACT_CONTROLLER_CLASS),
            'ConfigController must extend AbstractController.',
        );
    }

    public function testConfigControllerHasIndexAction(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('ConfigController does not exist yet.');
        }

        $this->assertTrue(
            method_exists(self::CONTROLLER_CLASS, 'indexAction'),
            'ConfigController must have an indexAction() method.',
        );
    }

    public function testConfigControllerAccessesConfigValue(): void
    {
        if (!class_exists(self::CONTROLLER_CLASS)) {
            $this->markTestSkipped('ConfigController does not exist yet.');
        }

        $reflection = new \ReflectionClass(self::CONTROLLER_CLASS);
        $source = file_get_contents($reflection->getFileName());

        $this->assertNotFalse(
            strpos($source, 'getMyConfigValue'),
            'ConfigController must call getMyConfigValue() to retrieve the config value.',
        );

        // Accept either constructor injection or factory approach
        $usesConstructorInjection = $reflection->getConstructor()
            && $reflection->getConstructor()->getNumberOfParameters() > 0;
        $usesFactory = strpos($source, 'getFactory') !== false;

        $this->assertTrue(
            $usesConstructorInjection || $usesFactory,
            'ConfigController must access the Config class either via constructor injection or via getFactory()->getConfig().',
        );
    }
}
