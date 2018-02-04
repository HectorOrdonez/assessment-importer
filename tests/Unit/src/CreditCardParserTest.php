<?php
namespace App\Test;

use App\Importer\CreditCardParser;
use PHPUnit\Framework\TestCase;

class CreditCardParserTest extends TestCase
{
    /**
     * @var CreditCardParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new CreditCardParser();
    }

    public function testTypeIsUnknownForIncorrectCreditCards()
    {
        $creditCardSample = '123123';

        $response = $this->parser->parse($creditCardSample);

        $this->assertEquals(CreditCardParser::TYPE_UNKNOWN, $response);
    }

    public function testTypeVisa()
    {
        $creditCardSamples = [
            '4111 1111 1111 1111',
            '41111111 11111111',
            '40 12 88 88 88 88 18 81',
            '4012 8888 8888 1881',
            '4222222222222',
        ];

        foreach($creditCardSamples as $sample)
        {
            $response = $this->parser->parse($sample);
            $this->assertEquals(CreditCardParser::TYPE_VISA, $response);
        }
    }

    public function testTypeMasterCard()
    {
        $creditCardSamples = [
            '5555555555554444',
            '5105105105105100',
        ];

        foreach($creditCardSamples as $sample)
        {
            $response = $this->parser->parse($sample);
            $this->assertEquals(CreditCardParser::TYPE_MASTERCARD, $response);
        }
    }

    public function testTypeMaestro()
    {
        $creditCardSamples = [
            '6759649826438453',
            '6799990100000000019',
        ];

        foreach($creditCardSamples as $sample)
        {
            $response = $this->parser->parse($sample);
            $this->assertEquals(CreditCardParser::TYPE_MAESTRO, $response);
        }
    }


}
