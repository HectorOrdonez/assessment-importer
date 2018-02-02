<?php
namespace App\Importer;

class PersonParser
{
    public function parse(\SimpleXMLElement $element)
    {
        if(!$this->validateBasics($element)) return false;

        $data = [];
        $data['id'] = (string) $element['id'];
        $data['name'] = (string) $element->name;

        return $data;
    }

    private function validateBasics(\SimpleXMLElement $element)
    {
        return isset($element['id']);
    }


    /**
     * @param $mail
     *
     * @return bool
     */
    private function validateMail($mail)
    {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }
}
