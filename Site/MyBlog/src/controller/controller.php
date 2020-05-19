<?php
namespace App\src\controller;

use Twig\Environment;
use App\src\model\postManager;
use App\src\model\userManager;
use App\src\model\commentManager;
use Twig\Loader\FilesystemLoader;
use App\src\model\categoryManager;
use Swift_Mailer;
use Swift_SmtpTransport;
use Twig\Extension\DebugExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class Controller
{
    protected $categoryManager;
    protected $postManager;
    protected $commentManager;
    protected $userManager;
    protected $twig;
    protected $session;
    protected $validator;
    protected $mail;

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
        $this->validator = Validation::createValidator();

        // Add extension SwiftMailer with yours parameters
        $this->mail = new Swift_SmtpTransport();
        $transport = (new Swift_SmtpTransport('smtp.orange.fr', 25))
        ->setUsername('franck.pyren')
        ->setPassword('Tristan2008');
        $this->mailer = new Swift_Mailer($transport);
    }
}
