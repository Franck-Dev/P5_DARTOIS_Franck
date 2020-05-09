<?php
namespace App\src\controller;

use App\src\model\postManager;
use App\src\model\userManager;
use App\src\model\commentManager;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

abstract class Controller
{
    protected $postManager;
    protected $commentManager;
    protected $userManager;
    protected $twig;

    public function __construct()
    {
        $this->postManager=new postManager;
        $this->commentManager=new commentManager;
        $this->userManager=new userManager;
        $loader=new FilesystemLoader('../templates');
        $this->twig = new Environment($loader, [
            'debug' => true,
            'cache' => false //'../tmp',
        ]);
    }
}
