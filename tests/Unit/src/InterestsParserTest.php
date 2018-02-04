<?php
namespace App\Test;

use App\Importer\InterestsParser;
use PHPUnit\Framework\TestCase;

class InterestsParserTest extends TestCase
{
    /**
     * @var InterestsParser
     */
    private $parser;

    public function testInterestsParserReturnsNothingWhenNoProfile()
    {
        $parser = new InterestsParser([]);
        $interests = $parser->parse($this->getSampleWithoutProfile());

        $this->assertEmpty($interests);
    }

    public function testInterestsParserReturnsNothingWhenNoInterests()
    {
        $parser = new InterestsParser([]);
        $interests = $parser->parse($this->getSampleWithoutInterests());

        $this->assertEmpty($interests);
    }

    public function testParsingInterestsMissingInCategoriesListYieldsEmptyString()
    {
        // You need to provide an array of categories... even an empty one
        $availableCategories = [];

        $parser = new InterestsParser($availableCategories);
        $parser->setAvailableCategories($availableCategories);

        $interests = $parser->parse($this->getSampleWithOneInterest());

        $this->assertEmpty($interests);
    }

    public function testParsingInterestThatExists()
    {
        $availableCategories = ['category1' => 'First Category'];

        $parser = new InterestsParser();
        $parser->setAvailableCategories($availableCategories);

        $interests = $parser->parse($this->getSampleWithOneInterest());

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

        $interests = $parser->parse($this->getSampleWithThreeInterest());

        $this->assertEquals('First Category Second Category Third Category', $interests);
    }

    /**
     * @return \SimpleXMLElement
     */
    private function getSampleWithoutProfile()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }

    /**
     * @return \SimpleXMLElement
     */
    private function getSampleWithoutInterests()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
            <profile income="20186.59">
                <education>Graduate School</education>
                <business>No</business>
            </profile>
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

}
