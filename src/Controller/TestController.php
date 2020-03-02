<?php

namespace AA\Controller;
use AA\Library\Http\HttpClient;
use AA\Library\Http\HttpClientInterface;
use AA\Library\Http\Request;
use AA\Library\Phalcon\Http\JsonResponse;
use AA\Library\SimpleHtml\SimpleHtml;

/**
 * @RoutePrefix('/api/test')
 */
class TestController extends BaseController
{
    /**
     * @Get(
     *     '/'
     * )
     */
    public function indexAction()
    {
        return new JsonResponse(['hello']);
    }
}