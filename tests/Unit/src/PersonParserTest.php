<?php
namespace App\Test;

use App\Importer\CreditCardParser;
use App\Importer\InterestsParser;
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
        $creditCardParserMock = $this->getCreditCardParserMock();
        $creditCardParserMock->shouldReceive('parse')->andReturn('');

        $interestsMock = $this->getInterestsParserMock();
        $interestsMock->shouldReceive('parse')->andReturn('');

        $this->parser = new PersonParser($creditCardParserMock, $interestsMock);
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


    public function testAvailableCategoriesReturnsTheParser()
    {
        $categories = ['Sample content'];

        $creditCardParserMock = $this->getCreditCardParserMock();
        $interestsParserMock = $this->getInterestsParserMock();
        $interestsParserMock->shouldReceive('setAvailableCategories')->once()->with($categories);

        $parser = new PersonParser($creditCardParserMock, $interestsParserMock);
        $response = $parser->setAvailableCategories($categories);

        $this->assertSame($parser, $response);
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

    public function testParserAsksCreditCardParserForCreditCardType()
    {
        $creditCardParserMock = $this->getCreditCardParserMock();
        $interestsParserMock = $this->getInterestsParserMock();
        $creditCardParserMock->shouldReceive('parse')->once()->with('1231 1231 1231 1231')->andReturn('visa');

        $expected = [
            'id' => 'person0',
            'name' => 'Sample Name',
            'mail' => '',
            'phone' => '',
            'credit_card_type' => 'visa',
            'interests' => '',
        ];

        $parser = new PersonParser($creditCardParserMock, $interestsParserMock);

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

    /**
     * @return CreditCardParser|MockInterface $creditCardParserMock
     */
    private function getCreditCardParserMock()
    {
        return  \Mockery::mock(CreditCardParser::class);
    }

    /**
     * @return InterestsParser|MockInterface $interestsParserMock
     */
    private function getInterestsParserMock()
    {
        $mock = \Mockery::mock(InterestsParser::class);
        $mock->shouldReceive('parse')->andReturn('');

        return $mock;
    }
}
