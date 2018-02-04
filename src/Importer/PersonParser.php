<?php
namespace App\Importer;

class PersonParser
{
    /**
     * List of available categories that people can take an interest in
     * @var array
     */
    private $availableCategories = [];

    /**
     * @var CreditCardParser
     */
    private $creditCardParser;

    public function __construct(CreditCardParser $creditCardParser)
    {
        $this->creditCardParser = $creditCardParser;
    }

    public function setAvailableCategories(array $categories)
    {
        $this->availableCategories = $categories;
    }

    public function parse(\SimpleXMLElement $personData)
    {
        if (!$this->validateBasics($personData)) {
            return false;
        }

        $data = [];
        $data['id'] = (string) $personData['id'];
        $data['name'] = (string) $personData->name;
        $data['mail'] = $this->parseMail((string) $personData->emailaddress);
        $data['phone'] = $this->parsePhone((string) $personData->phone);
        $data['credit_card_type'] = $this->parseCreditCard((string) $personData->creditcard);
        $data['interests'] = $this->parseInterests($personData);

        return $data;
    }

    /**
     * @param string $creditCard
     * @return string
     */
    private function parseCreditCard($creditCard)
    {
        if (empty($creditCard)) {
            return '';
        }

        return $this->creditCardParser->parse($creditCard);
    }

    private function parseInterests(\SimpleXMLElement $personData)
    {
        if (!isset($personData->profile) || !isset($personData->profile->interest)) {
            return '';
        }

        $interests = [];

        foreach ($personData->profile->interest as $interest) {
            $categoryId = (string) $interest['category'];

            if (array_key_exists($categoryId, $this->availableCategories)) {
                $interests[] = $this->availableCategories[$categoryId];
            }
        }

        return implode(' ', $interests);
    }

    private function validateBasics(\SimpleXMLElement $element)
    {
        return isset($element['id']);
    }

    private function parseMail($mail)
    {
        $mail = substr($mail, strpos($mail, ':') + 1);

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
