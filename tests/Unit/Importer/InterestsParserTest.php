<?php
namespace App\Test\Unit\Importer;

use App\Importer\InterestsParser;
use App\Test\Unit\TestCase;

class InterestsParserTest extends TestCase
{
    public function testInterestsParserReturnsNothingWhenNoProfile()
    {
        $parser = new InterestsParser([]);
        $interests = $parser->parse($this->getPersonWithoutProfile());

        $this->assertEmpty($interests);
    }

    public function testInterestsParserReturnsNothingWhenNoInterests()
    {
        $parser = new InterestsParser([]);
        $interests = $parser->parse($this->getPersonWithoutInterests());

        $this->assertEmpty($interests);
    }

    public function testParsingInterestsMissingInCategoriesListYieldsEmptyString()
    {
        // You need to provide an array of categories... even an empty one
        $availableCategories = [];

        $parser = new InterestsParser($availableCategories);
        $parser->setAvailableCategories($availableCategories);

        $interests = $parser->parse($this->getPersonWithOneInterest());

        $this->assertEmpty($interests);
    }

    public function testParsingInterestThatExists()
    {
        $availableCategories = ['category1' => 'First Category'];

        $parser = new InterestsParser();
        $parser->setAvailableCategories($availableCategories);

        $interests = $parser->parse($this->getPersonWithOneInterest());

        $this->assertEquals('First Category', $interests);
    }

    public function testParsingThreeInterestThatExists()
    {
        $availableCategories = [
            'category1' => 'First Category',
            'category2' => 'Second Category',
            'category3' => 'Third Category',
        ];

        $parser = new InterestsParser();
        $parser->setAvailableCategories($availableCategories);

        $interests = $parser->parse($this->getPersonWithThreeInterest());

        $this->assertEquals('First Category Second Category Third Category', $interests);
    }

}
