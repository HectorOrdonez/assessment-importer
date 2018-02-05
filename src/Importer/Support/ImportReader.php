<?php
namespace App\Importer\Support;

use App\Importer\Exception\ImporterException;

class ImportReader extends \XMLReader
{
    const ERROR_CANNOT_OPEN_XML = 'Critical error: could not open specified xml after validation.';

    /**
     * We will throw an exception if this does not work
     *
     * @param string $URI
     * @param null $encoding
     * @param int $options
     * @return bool
     * @throws ImporterException
     */
    public function open ($URI, $encoding = null, $options = 0)
    {
        if(!self::open($URI, $encoding, $options))
        {
            throw new ImporterException(self::ERROR_CANNOT_OPEN_XML);
        }
    }

    /**
     * Keeps reading the document until it finds the next element
     */
    public function nextElement()
    {
        while($this->read())
        {
            if($this->nodeType != ImportReader::ELEMENT) {
                continue;
            }

            return true;
        }

        return false;
    }
}
