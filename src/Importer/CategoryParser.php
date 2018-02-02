<?php
namespace App\Importer;

class CategoryParser
{
    /**
     * We will make sure the given element has an id and a name.
     *
     * @param \SimpleXMLElement $element
     * @return bool
     */
    public function parse(\SimpleXMLElement $element)
    {
        return isset($element['id']) && isset($element->name) ?
            [(string)$element['id'], (string)$element->name] :
            false;
    }
}
