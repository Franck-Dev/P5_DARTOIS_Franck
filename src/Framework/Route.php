<?php
/**
 * @package Framework
 */
namespace App\src\Framework;

/**
 * This file found differents routes by the name route:
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
Class Route
{
    private $name;
    public $module;
    public $action;
    public $params=[];

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
    public function __construct($name)
    {
        $this->name = $name[0];
    }

    /**
     * This function check the good controller by name route:
     * @param array $name [name of route]
     * @return string $controllerName [Name of controller by the name route]
     *
     */
    private function getModule($name)
    {
        $controllerName = $name['controller'];
        return $controllerName;
    }

    /**
     * This function check the good method in the controller by name route:
     * @param array $name [name of route]
     * @return string $action [Name of method by the name route]
     *
     */
    private function getAction($name)
    {
        $action = $name['action'];
        return $action;
    }
    
    /**
     * This method check the params by the name route
     *
     * @param  array $name [Name route]
     * @param  string $para [Url of the route]
     * @param  mixed $request [object instancié]
     * @return array[] $params [All  params by name route]
     */
    private function getParams($name, $para, $request)
    {
        $i=0;
        if (empty($name['params'])) {
            $params[$i] = '';
        }
        $rg = count($name['params']);
        foreach ($name['params'] as $att) {
            switch ($att) {
                case '$request':
                    $params[$i] = $request;
                break;
                case 'id':
                    if ($i === $rg-1) {
                        $params[$i] = end($para);
                    } else {
                        $params[$i] = $para[count($para)-2];
                    }
                break;
                case 'statut':
                    $params[$i] = end($para);
                break;
            }
            ++$i;
        }
        return $params;
    }
    
    /**
     * Get differents datas by url route
     *
     * @param  string $uri [Url route]
     * @param  mixed $request
     * @return void
     */
    public function matchRoutes($uri, $request)
    {
        $url = explode('/', trim($uri, "/"));
        $tr = $this->getRoutes();
        foreach ($tr as $route) {
            $d = explode('/', trim($route[0]['name'], "/"));
            if (count($url) == count($d) && strstr($uri, $route[0]['action']) ) {
                $this->module = $this->getModule($route[0]);
                $this->action = $this->getAction($route[0]);
                $this->params = $this->getParams($route[0], $url, $request);
                return true;
            break;                
            } elseif (count($url) == 1 && $url[0] == 'PyrTeck') {
                $this->module = $this->getModule($route[0]);
                $this->action = $this->getAction($route[0]);
                $this->params = ['1'];
                return true;
            break;
            } else {
                $this->module = 'errorController';
                $this->action = 'errorNotFound';
                $this->params = [];
            }
        }
        return false;
    }
    
    /**
     * Check differents routes in the config file
     *
     * @return array $routes [Differents routes with somes parameters themselves]
     */
    private function getRoutes()
    {
        $file_json = file_get_contents('../config/Routes.json');
        /* Array datas for routes */
        $routes = json_decode($file_json, true);
        return $routes;
    }
}