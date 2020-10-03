<?php
/**
 * @package Framework
 */
namespace App\src\Framework;

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

/**
 * This file can regroup parameters usually use
 * for the others controller in this App:
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
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
    protected $parameters;

    /**
     * This file can charging differents object using in this App
     *
     */
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

        /* Array datas for config mail parameters */
        $file_json = file_get_contents('../config/Config.json');
        $parameters = json_decode($file_json, true);
        $this->parameters = $parameters;
        // Add extension SwiftMailer with yours parameters
        $this->mail = new Swift_SmtpTransport();
        $transport = (new Swift_SmtpTransport($parameters['mail'][0]['domain'], $parameters['mail'][0]['port']))
        ->setUsername($parameters['mail'][0]['username'])
        ->setPassword($parameters['mail'][0]['password']);
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * This function will be to use for resize a pictures in controllers:
     * @param string $image_path [path of source image]
     * @param string $image_dest [path of destination image]
     * @param int $width [With Dimension of destination image]
     * @param int $height [Height Dimension of destination image]
     * @param int $qualite [Qualite for request result]
     * @param string|null $type []
     * @return string
     *
     */
    public function resize_img($image_path, $image_dest, $width, $height, $qualite, $type)
    {
        // Check existing files
        if (!file_exists($image_path)) :
            return 'wrong_path';
        endif;
      
        if ($image_dest == "") :
            $image_dest = $image_path;
        endif;
        // Extensions rules
        $extensions = array('jpg','jpeg','png','gif');
        $mimes = array('image/jpeg','image/gif','image/png');
      
        // Found the extension file of image
        $tab_ext = explode('.', $image_path);
        $extension  = strtolower($tab_ext[count($tab_ext)-1]);
        
        // Found the informations for image
        $image_data = getimagesize($image_path);
     
        // Extension's test to validate
        if (in_array($extension, $extensions) && in_array($image_data['mime'], $mimes)) :
          
            // Chech origin's dimensions
            $img_width = $image_data[0];
            $img_height = $image_data[1];

            //Replace a new dimensions
            $new_height = $height;
            $new_width =$width;

            // Create the new image ressource
            $dest = imagecreatetruecolor($new_width, $new_height);
        
            // Prepare extension
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $src = imagecreatefromjpeg($image_path); // Pour les jpg et jpeg
                    break;
        
                case 'png':
                    $src = imagecreatefrompng($image_path); // Pour les png
                    break;
        
                case 'gif':
                    $src = imagecreatefromgif($image_path); // Pour les gif
                    break;
            }
            // Create the new image
            if (imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height)):
        
                // Replace image switch extension
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($dest , $image_dest, $qualite); // Pour les jpg et jpeg
                        break;
                    case 'png':
                        imagepng($dest , $image_dest, $qualite); // Pour les png
                        break;
                    case 'gif':
                        imagegif($dest , $image_dest, $qualite); // Pour les gif
                        break;
                }
                return 'success';
            else :
                return 'resize_error';
            endif;
        else :
            return 'no_img';
        endif;
    }
}
