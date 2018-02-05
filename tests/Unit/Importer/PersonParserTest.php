<?php
namespace App\Test\Unit\Importer;

use App\Importer\CreditCardParser;
use App\Importer\InterestsParser;
use App\Importer\PersonParser;
use App\Test\Unit\TestCase;
use Mockery\MockInterface;

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
        $response = $this->parser->parse($this->getPersonWithoutId());

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
        $response = $this->parser->parse($this->getPersonSample());

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

        $response = $this->parser->parse($this->getPersonWithIncorrectMail());

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

        $response = $this->parser->parse($this->getPersonWithCorrectMail());

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

        $response = $this->parser->parse($this->getPersonWithIncorrectPhone());

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

        $response = $parser->parse($this->getPersonWithCreditCard());

        $this->assertSame($expected, $response);
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
