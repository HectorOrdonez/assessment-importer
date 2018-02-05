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
     * @param ImportReader|null $importReader
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
     *
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
     *
     * @return $this
     * @throws ImporterException
     */
    public function setOutputName($name)
    {
        $this->validateOutputName($name);

        $this->fileWriter->setFileName($name);

        return $this;
    }

    /**
     * @param $name
     *
     * @return bool
     * @throws ImporterException
     */
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
        $categories = [];
        $categoriesAreSet = false;

        $this->importReader->open($this->xmlPath);

        $this->fileWriter->writeHeaders();

        while ($this->importReader->nextElement()) {
            if ($this->importReader->name == 'category') {
                array_merge($categories, $this->categoryParser->parse($this->importReader->outerXmlToString()));
            } elseif ($this->importReader->name == 'person') {
                if (!$categoriesAreSet) {
                    $this->personParser->setAvailableCategories($categories);
                }
                $this->parseAndWritePerson();
            }
        }
    }

    /**
     * Uses the current person from the reader to parse it and print the results with the file writer
     */
    private function parseAndWritePerson()
    {
        $personData = $this->personParser->parse($this->importReader->outerXmlToString());

        $this->fileWriter->writeln(implode(',', $personData), FILE_APPEND);
    }
}
