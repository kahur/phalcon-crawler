<?php

namespace AA\Controller;
use AA\Library\Http\HttpClientInterface;
use AA\Library\Http\Request;
use AA\Library\Phalcon\Http\JsonResponse;

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