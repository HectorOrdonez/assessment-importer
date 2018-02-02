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
class Importer implements ImporterInterface
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

        if(!$reader->open($this->xmlPath))
        {
            throw new ImporterException();
        }

        $categories = [];
        $people = [];

        while($reader->read())
        {
            if ($reader->nodeType != XMLReader::ELEMENT) continue;

            switch ($reader->name)
            {
                case 'category':
                    $category = simplexml_load_string($reader->readOuterXml());
                    $categories[(string) $category['id']] = $category->name;
                    break;
                case 'person';
                    $person = simplexml_load_string($reader->readOuterXml());

                    $name = (string) $person->name;
                    $mail = (string) $person->emailaddress;
                    $phone = (string) $person->phone;
                    $homepage = (string) $person->homepage;
                    $creditCard = (string) $person->creditcard;

                    echo "#" . count($people) . " {$name} - {$mail} - $phone - $homepage - $creditCard \n";
                    $people[] = (string) $person->name;
                    break;
            }
        }
    }
}
