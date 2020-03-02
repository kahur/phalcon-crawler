<?php

namespace AA\Library\SimpleHtml\Element;

use AA\Library\SimpleHtml\ElementInterface;

/**
 * Class BasicElement
 * @package AA\Library\SimpleHtml\Element
 */
class BasicElement implements ElementInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * BasicElement constructor.
     *
     * @param       $name
     * @param array $attributes
     */
    public function __construct($name, array $attributes = [])
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}