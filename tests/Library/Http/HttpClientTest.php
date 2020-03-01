<?php

namespace AA\Tests\Library\Http;

use AA\Library\Http\Exception\InvalidUrlException;
use AA\Library\Http\HttpClient;
use AA\Library\Http\HttpResponseInterface;
use AA\Library\Http\Response\ResponseFactory;
use AA\Library\Http\Transfer\AdapterInterface;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    public function testRequest()
    {
        $url = 'https://google.com';
        $method = 'get';
        

        $data = [
            'status' => 200,
            'body' => 'this is body',
            'headers' => [
                'Content-Type' => 'text/html'
            ]
        ];

        $adapter = $this->getMockBuilder(AdapterInterface::class)
            ->setMethods(['setMethod', 'setUrl', 'setData', 'setHeaders', 'getHttpCode', 'getErrorCode', 'getErrorMessage', 'send'])
            ->getMock();

        $response = $this->getMockBuilder(HttpResponseInterface::class)
            ->setMethods(['getStatusCode', 'getBody', 'getHeaders', 'getHeader'])
            ->getMock();

        $responseFactory = $this->getMockBuilder(ResponseFactory::class)
            ->setMethods(['createResponse'])
            ->getMock();
        
        $adapter
            ->expects($this->once())
            ->method('setMethod')
            ->with($method)
            ->willReturn($adapter);

        $adapter
            ->expects($this->once())
            ->method('setUrl')
            ->with($url)
            ->willReturn($adapter);

        $adapter
            ->expects($this->once())
            ->method('setData')
            ->with([])
            ->willReturn($adapter);

        $adapter
            ->expects($this->once())
            ->method('setHeaders')
            ->with([])
            ->willReturn($adapter);

        $adapter
            ->expects($this->once())
            ->method('send')
            ->willReturn($data);
        
        $responseFactory
            ->expects($this->once())
            ->method('createResponse')
            ->willReturn($response);

        $client = new HttpClient($adapter, $responseFactory);

        $result = $client->request('get', 'https://google.com');
        $this->assertInstanceOf(HttpResponseInterface::class, $result);

        try {
            $client->request('get', 'google.com');
            $this->assertTrue(false, 'Exception excpected');
        } catch (InvalidUrlException $e) {
            $this->assertTrue(true);
        }
    }
}