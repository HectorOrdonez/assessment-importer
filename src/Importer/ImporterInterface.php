<?php
namespace App\Importer;

/**
 * Interface ImporterInterface
 * @package App\Importer
 */
interface ImporterInterface
{
    /**
     * Tells the importer where to look for the xml file
     *
     * @param string $xml
     * @return $this
     */
    public function setXmlPath($xml);
}
