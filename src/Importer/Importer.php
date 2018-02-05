<?php
namespace App\Importer;

use App\Importer\Exception\ImporterException;
use App\Importer\Support\FileWriter;
use App\Importer\Support\ImportReader;

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

    /**
     * @var string
     */
    private $xmlPath;

    /**
     * @var CategoryParser
     */
    private $categoryParser;

    /**
     * @var PersonParser
     */
    private $personParser;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var  ImportReader
     */
    private $importReader;

    /**
     * Importer constructor.
     * @param CategoryParser|null $categoryParser
     * @param PersonParser|null $personParser
     * @param FileWriter|null $fileWriter
     * @param XmlReader|null $importReader
     */
    public function __construct($categoryParser = null, $personParser = null, $fileWriter = null, $importReader = null)
    {
        $this->categoryParser = $categoryParser ? $categoryParser : new CategoryParser();
        $this->personParser = $personParser ? $personParser :
            new PersonParser(new CreditCardParser(), new InterestsParser());
        $this->fileWriter = $fileWriter ? $fileWriter : new FileWriter();
        $this->importReader = $importReader ? $importReader : new ImportReader();
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

        $this->fileWriter->setFileName($name);

        return $this;
    }

    private function validateOutputName($name)
    {
        list($fileName, $extension) = explode('.', $name);

        if ($extension != 'csv') {
            throw new ImporterException(self::ERROR_OUTPUT_EXTENSION);
        }

        if (empty($fileName)) {
            throw new ImporterException(self::ERROR_OUTPUT_NAME);
        }

        return true;
    }

    /**
     * @throws ImporterException
     */
    public function run()
    {
        $this->importReader->open($this->xmlPath);

        $categoriesAreParsed = false;
        $categories = [];
        $this->fileWriter->writeHeaders();

        while ($this->importReader->nextElement()) {
            switch ($this->importReader->name) {
                case 'category':
                    $categoryXml = simplexml_load_string($this->importReader->readOuterXml());
                    list($key, $value) = $this->categoryParser->parse($categoryXml);
                    $categories[$key] = $value;
                    break;
                case 'person':
                    if (!$categoriesAreParsed) {
                        $this->personParser->setAvailableCategories($categories);
                    }

                    $personXml = simplexml_load_string($this->importReader->readOuterXml());
                    $personData = $this->personParser->parse($personXml);

                    $this->fileWriter->writeln(implode(',', $personData), FILE_APPEND);

                    break;
            }
        }
    }
}
