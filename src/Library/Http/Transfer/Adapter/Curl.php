<?php

namespace AA\Library\Http\Transfer\Adapter;

use AA\Library\Http\Transfer\Adapter\Exception\InvalidMethodException;
use AA\Library\Http\Transfer\Adapter\Exception\TransferErrorException;
use AA\Library\Http\Transfer\AdapterInterface;

/**
 * Class Curl
 */
class Curl implements AdapterInterface
{
    /**
     *
     */
    const REQUEST_METHODS = [
        'POST',
        'GET',
        'DELETE',
        'PUT',
    ];

    /**
     * @var false|resource
     */
    private $resource;

    /**
     * @var array
     */
    protected $responseHeaders = [];

    /**
     * Curl constructor.
     *
     * @param array $options
     * as default all options are set to 0
     *
     * options:
     *  return_transfer: 1/0
     *  return_headers: 1/0
     */
    public function __construct(array $options = [])
    {
        $this->resource = curl_init();

        if (isset($options['return_transfer'])) {
            $returnTransfer = $options['return_transfer'] ? 1 : 0;
            curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, $returnTransfer);
        }

        if (isset($options['return_headers']) && $options['return_headers'] == 1) {
            $this->handleHeaders();
        }
    }

    /**
     * @param array $headres
     *
     * @return $this
     */
    public function setHeaders(array $headers): AdapterInterface
    {
        if (empty($headers)) {
            return $this;
        }

        $h = [];

        foreach ($headers as $name => $value) {
            $h[] = $name . ': ' . $value;
        }

        curl_setopt($this->resource, CURLOPT_HTTPHEADER, $h);

        return $this;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method): AdapterInterface
    {
        $method = strtoupper($method);

        if (!in_array($method, self::REQUEST_METHODS)) {
            throw new InvalidMethodException('Unsuported method for CURL');
        }

        curl_setopt($this->resource, CURLOPT_CUSTOMREQUEST, $method);

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data): AdapterInterface
    {
        if (empty($data)) {
            return $this;
        }

        curl_setopt($this->resource, CURLOPT_POSTFIELDS, $data);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url): AdapterInterface
    {
        curl_setopt($this->resource, CURLOPT_URL, $url);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return curl_getinfo($this->resource, CURLINFO_HTTP_CODE);
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return curl_errno($this->resource);
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return curl_error($this->resource);
    }

    /**
     * @return array
     */
    public function send()
    {
        $response = curl_exec($this->resource);
        // check cURL error
        $errorNumber = $this->getErrorCode();

        if ($errorNumber > 0) {
            throw new TransferErrorException($this->getErrorMessage(), $errorNumber);
        }

        return [
            'status'  => $this->getHttpCode(),
            'body'    => $response,
            'headers' => $this->getHttpHeaders(),
        ];
    }

    /**
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     *
     */
    protected function handleHeaders()
    {
        curl_setopt($this->resource, CURLOPT_HEADERFUNCTION, function($res, $header) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) // ignore invalid headers
                return $len;

            $this->responseHeaders[strtolower(trim($header[0]))] = trim($header[1]);

            return $len;
        });
    }
}
