<?php
namespace App\Test\Unit\Importer\Support;

use App\Importer\Support\ImportReader;
use App\Test\Unit\TestCase;

class ImportReaderTest extends TestCase
{
    /**
     * @expectedException \App\Importer\Exception\ImporterException
     */
    public function testThrowsExceptionWhenCannotOpen()
    {
        $importReader = new ImportReader();
        $importReader->open('some random thing');
    }

    public function testNextElementStopsOnElementOrEnds()
    {
        $xsLocation = dirname(dirname(dirname(dirname(__DIR__)))) . '/xml/contacts-xs.xml';
        $importReader = new ImportReader();
        $importReader->open($xsLocation);

        $countPeople = 0;
        $countCategories = 0;
        while($importReader->nextElement())
        {
            switch($importReader->name)
            {
                case 'category':
                    $countCategories++;
                    break;
                case 'person':
                    $countPeople++;
                    break;
            }
        }

        $this->assertEquals(1, $countPeople);
        $this->assertEquals(1, $countCategories);
    }
}
