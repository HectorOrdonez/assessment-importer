<?php
namespace App\Importer;

/**
 * Class InterestsParser
 * @package App\Importer
 */
class InterestsParser
{
    /**
     * List of categories available for parsing in a key to value manner
     * @var array
     */
    private $availableCategories;

    /**
     * @param array $availableCategories
     * @return $this
     */
    public function setAvailableCategories(array $availableCategories)
    {
        $this->availableCategories = $availableCategories;

        return $this;
    }

    /**
     * @param \SimpleXMLElement $personElement
     * @return string
     */
    public function parse(\SimpleXMLElement $personElement)
    {
        if (!$this->validate($personElement)) {
            return '';
        }

        $interests = [];

        foreach ($personElement->profile->interest as $interest) {
            $categoryId = (string) $interest['category'];

            if ($this->isValidCategoryId($categoryId)) {
                $interests[] = $this->getCategoryNameById($categoryId);
            }
        }

        return implode(' ', $interests);
    }

    /**
     * Returns whether given category id exists in the available categories
     *
     * @param $categoryId
     *
     * @return bool
     */
    private function isValidCategoryId($categoryId)
    {
        return array_key_exists($categoryId, $this->availableCategories);
    }

    /**
     * Returns the name of the category given its id
     *
     * @param $categoryId
     *
     * @return string
     */
    private function getCategoryNameById($categoryId)
    {
        return $this->availableCategories[$categoryId];
    }

    /**
     * This parser only works after setting the categories
     *
     * A given person element is considered valid for interests parsing if it has a profile and interests on it
     *
     * @param \SimpleXMLElement $element
     * @return bool
     */
    private function validate(\SimpleXMLElement $element)
    {
        return
            isset($this->availableCategories) &&
            isset($element->profile) &&
            isset($element->profile->interest);
    }
}
