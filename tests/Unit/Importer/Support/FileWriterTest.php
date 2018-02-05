<?php
namespace App\Test\Unit\Importer\Support;

use App\Importer\Support\FileWriter;
use App\Test\Unit\TestCase;

class FileWriterTest extends TestCase
{
    const TEST_FILE = 'unittest-filename.csv';

    public function testFilenameSetterReturnsItself()
    {
        $fileWriter = new FileWriter();

        $response = $fileWriter->setFileName('some name');

        $this->assertSame($fileWriter, $response);
    }

    public function testWriteHeaders()
    {
        $fileWriter = new FileWriter();
        $fileWriter->setFileName(self::TEST_FILE);
        $fileWriter->writeHeaders();

        $results = file_get_contents(self::TEST_FILE);

        $this->assertContains('name,email,phone,dob,credit card type,interests space separated', $results);
    }

    public function testWriteLine()
    {
        $fileWriter = new FileWriter();
        $fileWriter->setFileName(self::TEST_FILE);
        $fileWriter->writeHeaders();
        $fileWriter->writeln('testing some line here', FILE_APPEND);

        $results = file_get_contents(self::TEST_FILE);

        $this->assertContains("name,email,phone,dob,credit card type,interests space separated\r\ntesting some line here", $results);
    }

    public function tearDown()
    {
        if (file_exists(self::TEST_FILE)) {
            unlink(self::TEST_FILE);
        }
    }
}
