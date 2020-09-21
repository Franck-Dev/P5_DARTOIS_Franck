<?php
/**
 * @package Controller
 */
namespace App\src\controller;

use App\src\Framework\Controller;

/**
 * This file manage methods for return error template
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
class ErrorController extends Controller
{
    /**
    * Return the template when the path router doesn't found
    */
    public function errorNotFound()
    {
        echo $this->twig->render('error/error_404.html.twig');
        //require '../templates/error/error_404.php';
    }

    /**
    * Return the template when one error is in template
    */
    public function errorServer()
    {
        require '../templates/error/error_301.php';
    }
}
