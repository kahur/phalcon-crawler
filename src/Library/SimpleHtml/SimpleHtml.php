<?php

namespace AA\Library\SimpleHtml;

use AA\Library\SimpleHtml\Exception\ParserNotFound;
use AA\Library\SimpleHtml\Exception\UnsupportedParserException;
use AA\Library\SimpleHtml\Parser\RegexParser;

class SimpleHtml
{
    const DEFAULT_PARSER = RegexParser::class;

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var array
     */
    protected $options;

    /**
     * SimpleHtml constructor.
     *
     * @param array $options
     *  array('parser_class' => 'My\Custom\Parser')
     *
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['parser_class'])) {
            $options['parser_class'] = self::DEFAULT_PARSER;
        }

        $this->options = $options;
    }

    /**
     * @param string $html
     *
     * @return ParserInterface
     */
    public function parse(string $html): ParserInterface
    {
        return $this->getParser($html);
    }

    /**
     * @param string $className
     */
    protected function getParser(string $html)
    {
        $className = $this->options['parser_class'];

        if (!class_exists($className)) {
            throw new ParserNotFound('Parser not found');
        }

        $parser = new $className($html);

        if (!$parser instanceof ParserInterface) {
            throw new UnsupportedParserException('Unsupported parser implementation');
        }

        return $parser;
    }
}
