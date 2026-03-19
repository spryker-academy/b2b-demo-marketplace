<?php

declare(strict_types=1);

namespace SprykerAcademyTest\Zed\ContactRequest\Exercise2;

use Codeception\Test\Unit;

/**
 * Exercise 2: Data Transfer Object
 *
 * Verifies that students defined a ContactRequest transfer with the correct
 * properties in contact_request.transfer.xml.
 *
 * Run: vendor/bin/codecept run -c tests/SprykerAcademyTest/Zed/ContactRequest/ Exercise2
 */
class ContactRequestTransferDefinitionTest extends Unit
{
    private const TRANSFER_XML_RELATIVE_PATH = 'src/SprykerAcademy/Shared/ContactRequest/Transfer/contact_request.transfer.xml';

    private function findTransferXmlPath(): string
    {
        // Walk up from this test file to find the project root (where src/ lives)
        $dir = dirname(__DIR__, 5); // tests/SprykerAcademyTest/Zed/ContactRequest/Exercise2 -> project root
        $candidate = $dir . '/' . self::TRANSFER_XML_RELATIVE_PATH;

        if (file_exists($candidate)) {
            return $candidate;
        }

        // Try from the working directory
        $cwd = getcwd();
        $candidate = $cwd . '/' . self::TRANSFER_XML_RELATIVE_PATH;

        if (file_exists($candidate)) {
            return $candidate;
        }

        $this->fail(
            'Cannot find contact_request.transfer.xml. Expected at: ' . self::TRANSFER_XML_RELATIVE_PATH,
        );
    }

    private function loadTransferXml(): \SimpleXMLElement
    {
        $path = $this->findTransferXmlPath();
        $xml = simplexml_load_file($path);
        $this->assertNotFalse($xml, 'Failed to parse contact_request.transfer.xml.');

        return $xml;
    }

    public function testTransferXmlFileExists(): void
    {
        $this->findTransferXmlPath();
        $this->assertTrue(true);
    }

    public function testContactRequestTransferIsDefined(): void
    {
        $xml = $this->loadTransferXml();
        $xml->registerXPathNamespace('t', 'spryker:transfer-01');

        $transfers = $xml->xpath('//t:transfer[@name="ContactRequest"]');

        if (empty($transfers)) {
            // Try without namespace (some students may omit it)
            $transfers = $xml->xpath('//transfer[@name="ContactRequest"]');
        }

        $this->assertNotEmpty(
            $transfers,
            'A <transfer name="ContactRequest"> definition is missing in contact_request.transfer.xml.',
        );
    }

    public function testContactRequestTransferHasIdContactRequestProperty(): void
    {
        $xml = $this->loadTransferXml();
        $xml->registerXPathNamespace('t', 'spryker:transfer-01');

        $properties = $xml->xpath('//t:transfer[@name="ContactRequest"]/t:property[@name="idContactRequest"]');

        if (empty($properties)) {
            $properties = $xml->xpath('//transfer[@name="ContactRequest"]/property[@name="idContactRequest"]');
        }

        $this->assertNotEmpty(
            $properties,
            'ContactRequest transfer must have a property named "idContactRequest".',
        );

        $type = (string)$properties[0]['type'];
        $this->assertSame(
            'int',
            $type,
            'Property "idContactRequest" must be of type "int", got "' . $type . '".',
        );
    }

    public function testContactRequestTransferHasMessageProperty(): void
    {
        $xml = $this->loadTransferXml();
        $xml->registerXPathNamespace('t', 'spryker:transfer-01');

        $properties = $xml->xpath('//t:transfer[@name="ContactRequest"]/t:property[@name="message"]');

        if (empty($properties)) {
            $properties = $xml->xpath('//transfer[@name="ContactRequest"]/property[@name="message"]');
        }

        $this->assertNotEmpty(
            $properties,
            'ContactRequest transfer must have a property named "message".',
        );

        $type = (string)$properties[0]['type'];
        $this->assertSame(
            'string',
            $type,
            'Property "message" must be of type "string", got "' . $type . '".',
        );
    }
}
