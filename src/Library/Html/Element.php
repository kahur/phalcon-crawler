<?php

namespace AA\Library\Html;

class Element implements ElementInterface
{
    protected $name;
    protected $attributes;
    protected $element;

    public function __construct(string $name, string $element)
    {
        $this->name = $name;
        $this->element = $element;

        $this->parseAttributes($element);
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function getAttribute($name)
    {
        // TODO: Implement getAttribute() method.
    }

    protected function parseAttributes()
    {

    }
}