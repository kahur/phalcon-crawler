<?php

namespace AA\Controller;

use AA\Library\Phalcon\Http\JsonResponse;

class NotFoundController extends BaseController
{
    public function indexAction()
    {
        return new JsonResponse(['error' => 'Not found']);
    }
}