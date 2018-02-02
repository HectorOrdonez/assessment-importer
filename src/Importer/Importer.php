<?php
namespace App\Importer;

use App\Importer\Exception\ImporterException;
use App\Importer\Support\XMLReader;

/**
 * Class Importer
 *
 * This object will handle
 * @package App\Importer
 */
class Importer
{
    const ERROR_XML_PATH = 'Xml not found in [%s]. Are you sure it is located in the xml folder?';

    private $xmlPath;

    /**
     * Tells the importer where to look for the xml file
     *
     * @param string $xml
     * @return $this
     * @throws ImporterException
     */
    public function setXmlPath($xml)
    {
        $path = dirname(dirname(__DIR__)) . "/xml/{$xml}";

        if (!file_exists($path)) {
            throw new ImporterException(sprintf(self::ERROR_XML_PATH, $path));
        }

        $this->xmlPath = $path;

        return $this;
    }

    public function loadXml()
    {
        $reader = new XMLReader();

        if (!$reader->open($this->xmlPath)) {
            throw new ImporterException();
        }

        $categories = [];
        $output = [];
        file_put_contents("output.csv", "name,email,phone,dob,credit card type,interests space seperated\r\n");

        while ($reader->read()) {
            if ($reader->nodeType != XMLReader::ELEMENT) {
                continue;
            }

            switch ($reader->name) {
                case 'category':
                    $string = $reader->readOuterXml();
                    $category = simplexml_load_string($reader->readOuterXml());

                    $categories[(string)$category['id']] = (string)$category->name;
                    break;
                case 'person';
                    $person = simplexml_load_string($reader->readOuterXml());
                    echo var_export((string) $reader->readOuterXml(), true);


                    $name = (string)$person->name;
                    $mail = (string)$person->emailaddress;
                    $phone = (string)$person->phone;
                    $creditCardCheck = (bool)$person->creditcard;
                    $age = (string)$person->age;
                    $interests = $this->getCategoriesFromInterests($categories, $person->profile->interest);

                    $data = [$name, $mail, $phone, $age, $creditCardCheck, implode(' ', $interests)];

                    file_put_contents("output.csv", implode(',', $data) . "\r\n", FILE_APPEND);

                    break;
            }
        }
    }

    /**
     * @param array $categories
     * @param array $interests
     * @return false|string
     */
    private function getCategoriesFromInterests($categories, $interests)
    {
        $interestedCategories = [];
        foreach ($interests as $interest) {
            $interestedCategories[] = $this->getCategoryNameById($categories, (string)$interest['category']);
        }

        return $interestedCategories;
    }

    /**
     * @param array $categories
     * @param int $categoryId
     * @return string|false
     */
    private function getCategoryNameById($categories, $categoryId)
    {
        return array_key_exists($categoryId, $categories) ? $categories[$categoryId] : false;
    }
}
