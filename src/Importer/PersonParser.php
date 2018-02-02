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
        $data['mail'] = $this->parseMail((string) $element->emailaddress);

        return $data;
    }

    private function validateBasics(\SimpleXMLElement $element)
    {
        return isset($element['id']);
    }

    private function parseMail($mail)
    {
        $mail = substr($mail, strpos($mail,':') + 1);

        return $this->validateMail($mail) ? $mail : '';
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
