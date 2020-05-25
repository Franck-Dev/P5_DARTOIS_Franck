<?php
/**
 * @package Controller
 */
namespace App\src\controller;

/**
 * This file manage methods for return error template
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
class ErrorController
{
    /**
    * Return the template when the path router doesn't found
    */
    public function errorNotFound()
    {
        require '../templates/error/error_404.php';
    }

    /**
    * Return the template when one error is in template
    */
    public function errorServer()
    {
        require '../templates/error/error_301.php';
    }
}
