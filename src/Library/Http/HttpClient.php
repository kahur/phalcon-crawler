<?php

namespace AA\Library\Http;

use AA\Library\Http\Exception\InvalidUrlException;
use AA\Library\Http\HttpClientInterface;
use AA\Library\Http\Response\ResponseFactory;
use AA\Library\Http\Transfer\AdapterInterface;
use AA\Library\Http\Transfer\TransferFactory;

class HttpClient implements HttpClientInterface
{

    /**
     * @var TransferFactory
     */
    protected $transfer;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * HttpClient constructor.
     *
     * @param AdapterInterface $transfer
     * @param ResponseFactory $responseFactory
     */
    public function __construct(AdapterInterface $transfer, ResponseFactory $responseFactory)
    {
        $this->transfer = $transfer;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param       $type
     * @param       $url
     * @param array $data
     * @param array $headers
     *
     * @return HttpResponseInterface
     */
    public function request($type, $url, array $data = [], array $headers = []): HttpResponseInterface
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException('Invalid URL format');
        }

        $data = $this->transfer
            ->setMethod($type)
            ->setUrl($url)
            ->setData($data)
            ->setHeaders($headers)
            ->send();

        return $this->responseFactory->createResponse($data['status'], $data['body'], $data['headers']);
    }
}
