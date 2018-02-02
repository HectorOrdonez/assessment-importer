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
        $data['phone'] = $this->parsePhone((string) $element->phone);

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

    private function parsePhone($phone)
    {
        return $this->validatePhone($phone) ? $phone : '';
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

    /**
     * We can both agree that this does not really validate much. But since the Product Owner (you :D) did not
     * specify further requirements, I am guessing the minimum validation is the same as the old code.
     *
     * @param string $phone
     *
     * @return bool
     */
    private function validatePhone($phone)
    {
        return strlen($phone) > 9;
    }
}
