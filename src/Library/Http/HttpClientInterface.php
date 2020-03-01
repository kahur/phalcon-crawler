<?php

namespace AA\Library\Http;

interface HttpClientInterface
{
    /**
     * @param            $type
     * @param array|null $headers
     *
     * @return ResponseInterface
     */
    public function request($type, $url, array $data = [], array $headers = []): HttpResponseInterface;

}