<?php

namespace AA\Tests\Library\Http\Transfer\Adapter;

use AA\Library\Http\Transfer\Adapter\Curl;
use AA\Library\Http\Transfer\Adapter\Exception\InvalidMethodException;
use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase
{
    public function testSetIncorrectMethod()
    {
        $this->expectException(InvalidMethodException::class);
        $this->expectExceptionMessage('Unsuported method for CURL');
        $adapter = new Curl();

        $adapter->setMethod('test');
    }

    public function testSetMethod()
    {
        $adapter = new Curl();

        try {
            foreach(Curl::REQUEST_METHODS as $method) {
                $adapter->setMethod($method);
                $adapter->setMethod(strtolower($method));

                $this->assertTrue(true);
            }
        } catch (\RuntimeException $e) {
            $this->assertTrue(false, 'Error, while setting correct methods');
        }
    }
}