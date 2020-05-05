<?php

namespace App\src\controller;

class errorController
{
    public function errorNotFound()
    {
        require '../templates/error_404.php';
    }

    public function errorServer()
    {
        require '../templates/error_301.php';
    }
}