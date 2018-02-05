<?php
namespace App\Test\Unit;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use \PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MockeryPHPUnitIntegration;

    protected function getPersonSample()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    protected function getPersonWithCreditCard()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <homepage>samplewebsite.com</homepage>
            <creditcard>1231 1231 1231 1231</creditcard>
        </person>');
    }
    protected function getPersonWithIncorrectMail()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <emailaddress>mailto:mail.com</emailaddress>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    protected function getPersonWithIncorrectPhone()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <phone>312312</phone>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    protected function getPersonWithCorrectMail()
    {
        return new \SimpleXMLElement('
        <person id="person0">
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    protected function getPersonWithoutId()
    {
        return new \SimpleXMLElement('
        <person>
            <name>Sample Name</name>
            <emailaddress>mailto:samplemail@mail.com</emailaddress>
            <homepage>samplewebsite.com</homepage>
        </person>');
    }

    /**
     * @return \SimpleXMLElement
     */
    protected function getPersonWithoutProfile()
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
    protected function getPersonWithoutInterests()
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

    protected function getPersonWithOneInterest()
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

    protected function getPersonWithThreeInterest()
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
