<?php

namespace AA\Library\Http\Transfer;

interface AdapterInterface
{
    public function setHeaders(array $headers): self;

    public function setMethod(string $method): self;

    public function setData($data): self;

    public function setUrl(string $url): self;

    public function send();

    public function getHttpCode();

    public function getErrorCode(): int;

    public function getErrorMessage(): string;
}
