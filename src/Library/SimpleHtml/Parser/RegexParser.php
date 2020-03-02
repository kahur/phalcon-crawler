<?php

namespace AA\Library\SimpleHtml\Parser;

use AA\Library\SimpleHtml\Element\BasicElement;
use AA\Library\SimpleHtml\ElementInterface;
use AA\Library\SimpleHtml\ParserInterface;

class RegexParser implements ParserInterface
{
    /**
     * @var string
     */
    protected $html;

    /**
     * RegexParser constructor.
     *
     * @param string $html
     */
    public function __construct(string $html)
    {
        $this->html = $html;
    }

    /**
     * @param $html
     * @param $name
     *
     * @return ElementInterface[]
     */
    public function find($name)
    {
        $pattern = '/<('.$name.'|'.strtoupper($name).')(?=\s)(?!(?:[^>"\']|"[^"]*"|\'[^\']*\')*?(?<=\s)(?:term|range)\s*=)(?!\s*\/?>)\s+(?:" .*?"|\'.*?\'|[^>]*?)+>/m';

        if (preg_match_all($pattern, $this->html, $matches)) {
            foreach ($matches[0] as $match) {
                yield $this->getElement($name, $match);
            }
        }

        return [];
    }

    /**
     * @param string $name
     * @param string $htmlElement
     *
     * @return BasicElement
     */
    protected function getElement(string $name, string $htmlElement)
    {
        $attributes = $this->getAttributes($htmlElement);

        return new BasicElement($name, $attributes);
    }

    /**
     * @param string $htmlElement
     *
     * @return array
     */
    protected function getAttributes(string $htmlElement)
    {
        $pattern = '/([A-Za-z]+)=["|\']{1}(.*?)["|\']{1}/';
        $attributes = [];

        if (preg_match_all($pattern, $htmlElement, $matches)) {
            $names = $matches[1] ?? [];
            $values = $matches[2] ?? [];

            foreach ($names as $index => $name) {
                $attributes[$name] = $values[$index];
            }
        }

        return $attributes;
    }
}