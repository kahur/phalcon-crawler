<?php

namespace AA\Library\Http;

use AA\Library\Http\HttpResponseInterface;

class Response implements HttpResponseInterface
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var
     */
    protected $headers;

    /**
     * Response constructor.
     *
     * @param int    $code
     * @param string $body
     * @param array  $headers
     */
    public function __construct(int $code, string $body, array $headers = [])
    {
        $this->setCode($code);
        $this->setBody($body);
        $this->setHeaders($headers);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param $name
     *
     * @return string|null
     */
    public function getHeader($name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param int $code
     */
    protected function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $body
     */
    protected function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param mixed $headers
     */
    protected function setHeaders($headers)
    {
        $this->headers = $headers;
    }
}