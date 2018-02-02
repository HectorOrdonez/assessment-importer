<?php
namespace App\Test;

use App\Importer\PersonParser;
use PHPUnit\Framework\TestCase;

class PersonParserTest extends TestCase
{
    /**
     * @var PersonParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new PersonParser([]);
    }

    public function testParseReturnsFalseIfIdCannotBeFind()
    {
        $response = $this->parser->parse($this->getSampleWithoutId());

        $this->assertFalse($response);
    }

    public function testBasicParsing()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'interests' => '',
        ];
        $response = $this->parser->parse($this->getBasicSample());

        $this->assertEquals($expected, $response);
    }

    public function testIncorrectMailDoesNotGetParsed()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'interests' => '',
        ];

        $response = $this->parser->parse($this->getSampleWithIncorrectMail());

        $this->assertEquals($expected, $response);
    }

    public function testCorrectMailGetsParsed()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => 'samplemail@mail.com',
            'phone' => '',
            'interests' => '',
        ];

        $response = $this->parser->parse($this->getSampleWithCorrectMail());

        $this->assertEquals($expected, $response);
    }

    public function testInvalidPhoneDoesNotGetParsed()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'interests' => '',
        ];

        $response = $this->parser->parse($this->getSampleWithIncorrectPhone());

        $this->assertEquals($expected, $response);
    }

    public function testParsingInterestsMissingInCategoriesListYieldsEmptyString()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'interests' => '',

        ];
        $parser = $this->parser;

        $response = $parser->parse($this->getSampleWithOneInterest());

        $this->assertEquals($expected, $response);
    }

    public function testParsingInterestThatExists()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'interests' => 'category name here',

        ];
        $parser = new PersonParser(['category1' => 'category name here']);

        $response = $parser->parse($this->getSampleWithOneInterest());

        $this->assertEquals($expected, $response);
    }
    public function testParsingThreeInterestsThatExists()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'interests' => 'boardgames starcraft2 biking like crazy',

        ];
        $availableCategories = [
            'category1' => 'boardgames',
            'category2' => 'starcraft2',
            'category3' => 'biking like crazy',
        ];
        $parser = new PersonParser($availableCategories);

        $response = $parser->parse($this->getSampleWithThreeInterest());

        $this->assertEquals($expected, $response);
    }

    private function getBasicSample()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }

    private function getSampleWithOneInterest()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
            <profile income="20186.59">
                <interest category="category1"/>
                <education>Graduate School</education>
                <business>No</business>
            </profile>
        </person>');
    }

    private function getSampleWithThreeInterest()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
            <profile income="20186.59">
                <interest category="category1"/>
                <interest category="category2"/>
                <interest category="category3"/>
                <education>Graduate School</education>
                <business>No</business>
            </profile>
        </person>');
    }

    private function getSampleWithIncorrectMail()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <emailaddress>mailto:mail.com</emailaddress>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }

    private function getSampleWithIncorrectPhone()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <phone>312312</phone>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }

    private function getSampleWithCorrectMail()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
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
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }
}
