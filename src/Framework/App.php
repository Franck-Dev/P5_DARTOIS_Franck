<?php
namespace App\src\Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class App
{
    /** @var Request $request */
    private $request;
    private $response;
    //private $name;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->response = new Response();
    }
}
