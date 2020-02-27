<?php

namespace AA\Library\Phalcon\Http;

use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;

class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     *
     * @param array|null $content
     * @param null       $code
     * @param null       $status
     */
    public function __construct(array $content = null, $code = null, $status = null)
    {
        parent::__construct(json_encode($content), $code, $status);
    }

    /**
     * @return ResponseInterface
     */
    public function send(): ResponseInterface
    {
        $this->setHeader('Content-type', 'application/json; charset=utf-8');

        return parent::send();
    }
}