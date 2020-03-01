<?php

namespace AA\Library\Http;

interface HttpResponseInterface
{
    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param $name
     *
     * @return string
     */
    public function getHeader($name): ?string;

    /**
     * @return mixed
     */
    public function getBody();
}