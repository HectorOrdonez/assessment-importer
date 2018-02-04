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
    const ERROR_OUTPUT_EXTENSION = 'The output file should be a csv type of file.';
    const ERROR_OUTPUT_NAME = 'Output name is not valid.';

    private $xmlPath;
    private $outputName;
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

        $this->validateXmlPath($path);

        $this->xmlPath = $path;

        return $this;
    }

    private function validateXmlPath($path)
    {
        if (!file_exists($path)) {
            throw new ImporterException(sprintf(self::ERROR_XML_PATH, $path));
        }

        return true;
    }

    /**
     * Tells the importer the name of the output file
     *
     * @param string $name
     * @return $this
     * @throws ImporterException
     */
    public function setOutputName($name)
    {
        $this->validateOutputName($name);

        $this->outputName = $name;

        return $this;
    }

    private function validateOutputName($name)
    {
        list($fileName, $extension) = explode('.', $name);

        if ($extension != 'csv')
        {
            throw new ImporterException(self::ERROR_OUTPUT_EXTENSION);
        }

        if (empty($fileName))
        {
            throw new ImporterException(self::ERROR_OUTPUT_NAME);
        }

        return true;
    }

    public function run()
    {
        $reader = new XMLReader();

        if (!$reader->open($this->xmlPath)) {
            throw new ImporterException();
        }

        $categoriesAreParsed = false;
        $categories = [];
        $this->writeHeader();

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

                    $this->write(implode(',', $personData), FILE_APPEND);

                    break;
            }
        }
    }

    /**
     * Writes the header of the output file
     * @return int
     */
    private function writeHeader()
    {
        $columns = [
            'name',
            'email',
            'phone',
            'dob',
            'credit card type',
            'interests space separated',
        ];

        return $this->write(implode(',', $columns));
    }

    /**
     * Writes the given line in the output file
     * @param string $line
     * @param int $flag
     * @return int
     */
    private function write($line, $flag = 0)
    {
        return file_put_contents($this->outputName, "{$line}\r\n", $flag);
    }
}
