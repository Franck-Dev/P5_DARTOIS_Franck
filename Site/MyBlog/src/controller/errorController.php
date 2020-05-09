<?php

namespace App\src\controller;

class ErrorController
{
    public function errorNotFound()
    {
        require '../templates/error/error_404.php';
    }

    public function errorServer()
    {
        require '../templates/error/error_301.php';
    }
}
