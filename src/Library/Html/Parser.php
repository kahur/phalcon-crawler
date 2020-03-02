<?php

namespace AA\Library\Html;

class Parser
{
    /**
     * @var string|string|null
     */
    protected $html;

    /**
     * @var ElementFactory
     */
    protected $elementFactory;

    /**
     * HtmlParser constructor.
     *
     * @param string|null $html
     */
    public function __construct(ElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    /**
     * @param string $html
     */
    public function setHtmlBody(string $html)
    {
        $this->html = $html;
    }

    /**
     * @param $name
     *
     * @return ElementInterface[]
     */
    public function getElementsByName($name)
    {
        $pattern = '/<('.$name.'|'.strtoupper($name).')(?=\s)(?!(?:[^>"\']|"[^"]*"|\'[^\']*\')*?(?<=\s)(?:term|range)\s*=)(?!\s*\/?>)\s+(?:" .*?"|\'.*?\'|[^>]*?)+>/gm';

        preg_match_all($pattern, $this->html, $matches);

        foreach($matches as $element) {
            yield $this->elementFactory->createElement($name, $element);
        }
    }
}