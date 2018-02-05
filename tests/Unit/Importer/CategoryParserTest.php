<?php
namespace App\Test\Unit\Importer;

use App\Importer\CategoryParser;
use App\Test\Unit\TestCase;

class CategoryParserTest extends TestCase
{
    public function testParseReturnsFalseIfIdCannotBeFind()
    {
        $parser = new CategoryParser();

        $response = $parser->parse($this->getSampleWithoutId());

        $this->assertFalse($response);
    }

    public function testParseReturnsFalseIfNameCannotBeFind()
    {
        $parser = new CategoryParser();

        $response = $parser->parse($this->getSampleWithoutName());

        $this->assertFalse($response);
    }

    public function testParseReturnsKeyValue()
    {
        $parser = new CategoryParser();

        $response = $parser->parse($this->getSample());

        $this->assertEquals(['category1', 'My Category Name'], $response);
    }

    /**
     * Sample with both id and name information
     *
     * @return \SimpleXMLElement
     */
    private function getSample()
    {
        return new \SimpleXMLElement('
<category id="category1">
    <name>My Category Name</name>
    <description>
        <text>
            loyalty merit wicked plagued strangely devise doom ginger five makes fiery expedition wondrous
            inward necessity glou expectation offers shake ushered behaviour
        </text>
    </description>
</category>
        ');
    }

    /**
     * Sample with missing name
     *
     * @return \SimpleXMLElement
     */
    private function getSampleWithoutName()
    {
        return new \SimpleXMLElement('
<category id="category1">
    <description>
        <text>
            loyalty merit wicked plagued strangely devise doom ginger five makes fiery expedition wondrous
            inward necessity glou expectation offers shake ushered behaviour
        </text>
    </description>
</category>
        ');
    }

    /**
     * Sample with missing id
     *
     * @return \SimpleXMLElement
     */
    private function getSampleWithoutId()
    {
        return new \SimpleXMLElement('
<category>
    <name>arm solemn weak finds</name>
    <description>
        <text>
            loyalty merit wicked plagued strangely devise doom ginger five makes fiery expedition wondrous
            inward necessity glou expectation offers shake ushered behaviour
        </text>
    </description>
</category>
        ');
    }
}
