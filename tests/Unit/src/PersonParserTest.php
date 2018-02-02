<?php
namespace App\Test;

use App\Importer\PersonParser;
use PHPUnit\Framework\TestCase;

class PersonParserTest extends TestCase
{
    public function testParseReturnsFalseIfIdCannotBeFind()
    {
        $parser = new PersonParser();

        $response = $parser->parse($this->getSampleWithoutId());

        $this->assertFalse($response);
    }

    public function testBasicParsing()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
        ];
        $parser = new PersonParser();

        $response = $parser->parse($this->getBasicSample());

        $this->assertEquals($expected, $response);
    }

    private function getBasicSample()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
            <phone>+12 (123) 12312312</phone>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }

    private function getSampleWithoutId()
    {
        return new \SimpleXMLElement('
        <person>
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
            <phone>+12 (123) 12312312</phone>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }
}
