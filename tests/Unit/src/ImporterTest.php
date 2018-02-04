<?php
namespace App\Test;

use App\Importer\Importer;
use PHPUnit\Framework\TestCase;

class ImporterTest extends TestCase
{
    const TEST_XML_PATH = 'contacts-xs.xml';

    /**
     * @expectedException \App\Importer\Exception\ImporterException
     * @expectedMessage Are you sure it is located in the xml folder?
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

    /**
     * @expectedException \App\Importer\Exception\ImporterException
     * @expectedMessage The output file should be a csv type of file.
     */
    public function testOutputExtensionThrowsExceptionIfNotCsv()
    {
        $importer = new Importer();

        $importer->setOutputName('some-file-name.txt');
    }

    /**
     * @expectedException \App\Importer\Exception\ImporterException
     * @expectedMessage Output name is not valid.
     */
    public function testOutputNameThrowsExceptionIfEmpty()
    {
        $importer = new Importer();

        $importer->setOutputName('.csv');
    }
}
