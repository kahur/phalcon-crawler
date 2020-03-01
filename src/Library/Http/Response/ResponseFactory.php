<?php

namespace AA\Library\Http\Response;

use AA\Library\Http\HttpResponseInterface;
use AA\Library\Http\Response;

class ResponseFactory
{
    /**
     * @param int    $status
     * @param string $body
     * @param array  $headers
     *
     * @return HttpResponseInterface
     */
    public function createResponse(int $status, string $body, array $headers = []): HttpResponseInterface
    {
        return new Response($status, $body, $headers);
    }

    /**
     * @param int   $status
     * @param       $body
     * @param array $headers
     *
     * @return HttpResponseInterface
     */
    public function createJsonResponse(int $status, $body, array $headers): HttpResponseInterface
    {
        $data = [
            'status' => $status,
            'body' => $body,
            'headers' => $headers
        ];

        return new Response(200, json_encode($data), ['Content-Type', 'application/json']);
    }
}