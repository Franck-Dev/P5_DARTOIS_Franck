<?php
namespace App\src\Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    private $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }

    public function checkPath($url)
    {
        
        // $request->query->get($url);
        // // $response= new Response();
        // // $response=$request->getContent();
        // //var_dump($request->request);
        // return $request->query->get($url);
    }
}
