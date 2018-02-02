<?php
namespace App\Importer;

use App\Importer\Exception\ImporterException;
use Symfony\Component\Finder\Finder;

/**
 * Class Importer
 *
 * This object will handle
 * @package App\Importer
 */
class Importer implements ImporterInterface
{
    const ERROR_XML_PATH = 'Xml not found in [%s]. Are you sure it is located in the xml folder?';

    private $xml;

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

        $this->xml = file_get_contents($path);

        return $this;
    }
}
