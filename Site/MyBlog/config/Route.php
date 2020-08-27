<?php

namespace App\config;

Class Route
{
    private $name;
    public $module;
    public $action;
    public $params=[];

    public function __construct($name)
    {
        $this->name = $name[0];
    }

    private function getModule($name)
    {
        $controllerName = $name['controller'];
        return $controllerName;
    }

    private function getAction($name)
    {
        $action = $name['action'];
        return $action;
    }

    private function getParams($name, $para, $request)
    {
        $i=0;
        //var_dump($name);
        if (empty($name['params'])) {
            $params[$i] = '';
        }
        foreach ($name['params'] as $att) {
            switch ($att) {
                case '$request':
                    $params[$i] = $request;
                break;
                case 'id':
                    $params[$i] = end($para);
                break;
            }
            ++$i;
        }
        return $params;
    }

    public function matchRoutes($uri, $request)
    {
        $url = explode('/', trim($uri, "/"));
        //var_dump($url);
        $tr = $this->getRoutes();
        foreach ($tr as $route) {
            $d = explode('/', trim($route[0]['name'], "/"));
            if (count($url) == count($d) && stristr($uri, $route[0]['action']) ) {
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

    private function getRoutes()
    {
        $file_json = file_get_contents('../config/Routes.json');
        /* Array datas for routes */
        $routes = json_decode($file_json, true);
        return $routes;
    }
}