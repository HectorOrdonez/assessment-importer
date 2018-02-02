<?php
namespace App\Test;

use App\Importer\Importer;
use PHPUnit\Framework\TestCase;

class ImporterTest extends TestCase
{
    const TEST_XML_PATH = 'contacts-xs.xml';
    /**
     * @expectedException \App\Importer\Exception\ImporterException
     */
    public function testSetXmlPathThrowsExceptionIfItDoesNotExist()
    {
        $importer = new Importer();

        $importer->setXmlPath('non-existing-path');
    }

    public function testSetXmlReturnsImporterWhenSuccessful()
    {
        $importer = new Importer();

        $response = $importer->setXmlPath(self::TEST_XML_PATH);

        $this->assertInstanceOf(Importer::class, $response);
    }
}
