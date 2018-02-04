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
    private $categoryParser;
    private $personParser;

    public function __construct()
    {
        $this->categoryParser = new CategoryParser();
        $this->personParser = new PersonParser(new CreditCardParser());
    }

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

        $categoriesAreParsed = false;
        $categories = [];
        file_put_contents("output.csv", "name,email,phone,dob,credit card type,interests space seperated\r\n");

        while ($reader->read()) {
            if ($reader->nodeType != XMLReader::ELEMENT) {
                continue;
            }

            switch ($reader->name) {
                case 'category':
                    $categoryXml = simplexml_load_string($reader->readOuterXml());
                    list($key, $value) = $this->categoryParser->parse($categoryXml);
                    $categories[$key] = $value;
                    break;
                case 'person';
                    if(!$categoriesAreParsed) $this->personParser->setAvailableCategories($categories);

                    $personXml = simplexml_load_string($reader->readOuterXml());
                    $personData = $this->personParser->parse($personXml);

                    file_put_contents("output.csv", implode(',', $personData) . "\r\n", FILE_APPEND);

                    break;
            }
        }
    }
}
