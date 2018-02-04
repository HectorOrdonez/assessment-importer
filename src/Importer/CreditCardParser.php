<?php
namespace App\Importer;

/**
 * This CreditCardParser is quite incomplete: ideally we would add here all the possible credit cards we expect
 *
 * @source https://stackoverflow.com/questions/72768/how-do-you-detect-credit-card-type-based-on-number
 */
class CreditCardParser
{
    const TYPE_VISA = 'visa';
    const TYPE_UNKNOWN = 'unknown';
    const TYPE_MASTERCARD = 'mastercard';
    const TYPE_MAESTRO = 'maestro';

    /**
     * @param $creditCard
     * @return string
     */
    public function parse($creditCard)
    {
        $creditCard = str_replace(' ', '', $creditCard);

        if ($this->isVisa($creditCard)) return self::TYPE_VISA;

        if ($this->isMasterCard($creditCard)) return self::TYPE_MASTERCARD;

        if ($this->isMaestro($creditCard)) return self::TYPE_MAESTRO;

        return self::TYPE_UNKNOWN; //unknown for this system
    }

    private function isVisa($creditCard)
    {
        return preg_match('/^4[0-9]{0,}$/', $creditCard);
    }

    private function isMasterCard($creditCard)
    {
        return preg_match('/^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$/', $creditCard);
    }

    private function isMaestro($creditCard)
    {
        return preg_match('/^(5[06789]|6)[0-9]{0,}$/', $creditCard);
    }
}
