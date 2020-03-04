<?php

namespace Javid\DependentFields\Model\Example;

/**
 * Class AttributeScope
 * @package Javid\DependentFields\Model\Example
 */
class AttributeScope
{
    /** @var array */
    private $codessss;

    /**
     * @param array $codessss
     */
    public function __construct(
        $codessss = []
    ) {
        $this->codessss = $codessss;
    }

    /**
     * Get attribute codes to scope in form select element
     *
     * @return array
     */
    public function getCodes()
    {
        return $this->codessss;
    }
}
