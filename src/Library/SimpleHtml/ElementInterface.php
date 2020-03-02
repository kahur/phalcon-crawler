<?php

namespace AA\Library\SimpleHtml;

interface ElementInterface
{
    public function getName();
    public function getAttribute($name);
    public function getAttributes();
}