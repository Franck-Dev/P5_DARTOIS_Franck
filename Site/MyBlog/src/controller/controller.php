<?php
namespace App\src\controller;

use Twig\Environment;
use App\src\model\postManager;
use App\src\model\userManager;
use App\src\model\commentManager;
use Twig\Loader\FilesystemLoader;
use App\src\model\categoryManager;
use Twig\Extension\DebugExtension;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class Controller
{
    protected $categoryManager;
    protected $postManager;
    protected $commentManager;
    protected $userManager;
    protected $twig;
    protected $session;

    public function __construct()
    {
        $this->categoryManager=new categoryManager;
        $this->postManager=new postManager;
        $this->commentManager=new commentManager;
        $this->userManager=new userManager;
        $loader=new FilesystemLoader('../templates');
        $this->twig = new Environment($loader, [
            'debug' => true,
            'cache' => false //'../tmp',
        ]);
        $this->twig->addExtension(new DebugExtension);
        $this->session=new Session();
        $this->twig->addGlobal('app', new Session());
    }
}
