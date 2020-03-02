<?php

namespace AA\Library\SimpleHtml;

interface ParserInterface
{
    /**
     * @param $name
     *
     * @return ElementInterface[]|null
     */
    public function find($name);
}