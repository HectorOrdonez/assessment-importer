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

    const PREG_VISA = '/^4[0-9]{0,}$/';
    const PREG_MASTER = '/^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$/';
    const PREG_MAESTRO = '/^(5[06789]|6)[0-9]{0,}$/';

    /**
     * @param $creditCard
     * @return string
     */
    public function parse($creditCard)
    {
        $creditCard = str_replace(' ', '', $creditCard);

        if ($this->isVisa($creditCard)) {
            return self::TYPE_VISA;
        }

        if ($this->isMasterCard($creditCard)) {
            return self::TYPE_MASTERCARD;
        }

        if ($this->isMaestro($creditCard)) {
            return self::TYPE_MAESTRO;
        }

        return self::TYPE_UNKNOWN; //unknown for this system
    }

    /**
     * Returns whether this credit card is a visa or not
     *
     * @param $creditCard
     * @return int
     */
    private function isVisa($creditCard)
    {
        return preg_match(self::PREG_VISA, $creditCard);
    }

    /**
     * Returns whether this credit card is a master or not
     *
     * @param $creditCard
     * @return int
     */
    private function isMasterCard($creditCard)
    {
        return preg_match(self::PREG_MASTER, $creditCard);
    }

    /**
     * Returns whether this credit card is a maestro or not
     *
     * @param $creditCard
     * @return int
     */
    private function isMaestro($creditCard)
    {
        return preg_match(self::PREG_MAESTRO, $creditCard);
    }
}
