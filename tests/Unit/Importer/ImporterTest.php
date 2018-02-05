<?php
namespace App\Test\Unit\Importer;

use App\Importer\Importer;
use App\Importer\Support\FileWriter;
use App\Importer\Support\ImportReader;
use App\Test\Unit\TestCase;

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

    public function testOutputReturnsItself()
    {
        $importer = new Importer();

        $response = $importer->setOutputName('text.csv');

        $this->assertSame($importer, $response);
    }

    public function testRunRequestsImportReaderToOpenXmlFileAndWriterToWriteHeaders()
    {
        $xmlPath = 'contacts-xs.xml';
        $importReaderMock =$this->getImportReaderMock();
        $importReaderMock->shouldReceive('open')->once();
        $importReaderMock->shouldReceive('nextElement')->once()->andReturn(false);

        $fileWriter = $this->getFileWriterMock();
        $fileWriter->shouldReceive('writeHeaders');

        $importer = new Importer(null, null, $fileWriter, $importReaderMock);
        $importer->setXmlPath($xmlPath);
        $importer->run();
    }

    /**
     * @return \Mockery\MockInterface|ImportReader
     */
    private function getImportReaderMock()
    {
        return \Mockery::mock(ImportReader::class);
    }

    /**
     * @return \Mockery\MockInterface|FileWriter
     */
    private function getFileWriterMock()
    {
        return \Mockery::mock(FileWriter::class);
    }
}
