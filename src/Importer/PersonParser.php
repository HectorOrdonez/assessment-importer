<?php
namespace App\Importer;

class PersonParser
{
    /**
     * @var CreditCardParser
     */
    private $creditCardParser;

    /**
     * @var InterestsParser
     */
    private $interestsParser;

    /**
     * PersonParser constructor.
     *
     * @param CreditCardParser $creditCardParser
     * @param InterestsParser $interestsParser
     */
    public function __construct(CreditCardParser $creditCardParser, InterestsParser $interestsParser)
    {
        $this->creditCardParser = $creditCardParser;
        $this->interestsParser = $interestsParser;
    }

    /**
     * @param array $categories
     *
     * @return $this
     */
    public function setAvailableCategories(array $categories)
    {
        $this->interestsParser->setAvailableCategories($categories);

        return $this;
    }

    /**
     * @param \SimpleXMLElement $personData
     *
     * @return array|bool
     */
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
        $data['interests'] = $this->interestsParser->parse($personData);

        return $data;
    }

    /**
     * @param string $creditCard
     *
     * @return string
     */
    private function parseCreditCard($creditCard)
    {
        if (empty($creditCard)) {
            return '';
        }

        return $this->creditCardParser->parse($creditCard);
    }

    /**
     * @param \SimpleXMLElement $element
     * @return bool
     */
    private function validateBasics(\SimpleXMLElement $element)
    {
        return isset($element['id']);
    }

    /**
     * @param $mail
     *
     * @return string
     */
    private function parseMail($mail)
    {
        $mail = substr($mail, strpos($mail, ':') + 1);

        return $this->validateMail($mail) ? $mail : '';
    }

    /**
     * @param $phone
     *
     * @return string
     */
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
