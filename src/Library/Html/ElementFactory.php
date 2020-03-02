<?php


namespace AA\Library\Html;

class ElementFactory
{
    public function createElement($name, $elementHtml): ElementInterface
    {
        return new Element($name, $elementHtml);
    }
}