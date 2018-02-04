<?php
namespace App\Test;

use App\Importer\CreditCardParser;
use App\Importer\PersonParser;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PersonParserTest extends TestCase
{
    /**
     * @var PersonParser
     */
    private $parser;

    public function setUp()
    {
        /**
         * @var CreditCardParser|MockInterface $creditCardParserMock
         */
        $creditCardParserMock = \Mockery::mock(CreditCardParser::class);

        $this->parser = new PersonParser($creditCardParserMock);
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
            'credit_card_type' => '',
            'interests' => '',
        ];
        $response = $this->parser->parse($this->getBasicSample());

        $this->assertSame($expected, $response);
    }

    public function testIncorrectMailDoesNotGetParsed()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => '',
            'interests' => '',
        ];

        $response = $this->parser->parse($this->getSampleWithIncorrectMail());

        $this->assertSame($expected, $response);
    }

    public function testCorrectMailGetsParsed()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => 'samplemail@mail.com',
            'phone' => '',
            'credit_card_type' => '',
            'interests' => '',
        ];

        $response = $this->parser->parse($this->getSampleWithCorrectMail());

        $this->assertSame($expected, $response);
    }

    public function testInvalidPhoneDoesNotGetParsed()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => '',
            'interests' => '',
        ];

        $response = $this->parser->parse($this->getSampleWithIncorrectPhone());

        $this->assertSame($expected, $response);
    }

    public function testParsingInterestsMissingInCategoriesListYieldsEmptyString()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => '',
            'interests' => '',

        ];
        $parser = $this->parser;

        $response = $parser->parse($this->getSampleWithOneInterest());

        $this->assertSame($expected, $response);
    }

    public function testParsingInterestThatExists()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => '',
            'interests' => 'category name here',

        ];
        $availableCategories = [
            'category1' => 'category name here',
        ];
        $this->parser->setAvailableCategories($availableCategories);

        $response = $this->parser->parse($this->getSampleWithOneInterest());

        $this->assertSame($expected, $response);
    }

    public function testParsingThreeInterestsThatExists()
    {
        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => '',
            'interests' => 'boardgames starcraft2 biking like crazy',

        ];
        $availableCategories = [
            'category1' => 'boardgames',
            'category2' => 'starcraft2',
            'category3' => 'biking like crazy',
        ];

        $this->parser->setAvailableCategories($availableCategories);

        $response = $this->parser->parse($this->getSampleWithThreeInterest());

        $this->assertSame($expected, $response);
    }

    public function testParserAsksCreditCardParserForCreditCardType()
    {
        /**
         * @var CreditCardParser|MockInterface $creditCardParserMock
         */
        $creditCardParserMock = \Mockery::mock(CreditCardParser::class);
        $creditCardParserMock->shouldReceive('parse')->once()->with('1231 1231 1231 1231')->andReturn('visa');

        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => 'visa',
            'interests' => '',
        ];

        $parser = new PersonParser($creditCardParserMock);

        $response = $parser->parse($this->getSampleWithCreditCard());

        $this->assertSame($expected, $response);
    }

    private function getBasicSample()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    private function getSampleWithCreditCard()
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
        </person>');
    }

    private function getSampleWithIncorrectPhone()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <phone>312312</phone>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    private function getSampleWithCorrectMail()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    private function getSampleWithoutId()
    {
        return new \SimpleXMLElement('
        <person>
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }
}
