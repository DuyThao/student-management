<?php

namespace Core\Http;

use App\Config\Smarty\SmartyTemplate;

class Route
{
    function __construct()
    {
        $this->__routes = [];
        $this->tpl = new SmartyTemplate;
    }

    public function get(string $url, $action)
    {
        $this->__request($url, 'GET', $action);
    }

    public function post(string $url, $action)
    {
        $this->__request($url, 'POST', $action);
    }

    private function __request(string $url, string $method, $action)
    {
        if (preg_match_all('/({([a-zA-Z]+)})/', $url, $params)) {
            $url = preg_replace('/({([a-zA-Z]+)})/', '(.+)', $url);
        }
        $url = str_replace('/', '\/', $url);

        $route = [
            'url' => $url,
            'method' => $method,
            'action' => $action,
            'params' => $params[2]
        ];
        array_push($this->__routes, $route);
    }

    public function map(string $url, string $method)
    {
        foreach ($this->__routes as $route) {

            if ($route['method'] == $method) {

                $reg = '/^' . $route['url'] . '$/';
                if (preg_match($reg, $url, $params)) {
                    array_shift($params);
                    $this->__call_action_route($route['action'], $params);
                    return;
                }
            }
        }

        echo '404 - Not Found';
        return;
    }

    private function __call_action_route($action, $params)
    {
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }
        if (is_string($action)) {
            $action = explode('@', $action);
            $controller_name = 'App\\Controllers\\' . $action[0];
            $controller = new $controller_name();
            $a = $action[1];
            if ($action[1] == 'loginPage') {
                if (isset($_SESSION['loggedin'])) {
                    if ($_SESSION['loggedin'] == true) {
                        header('Location: ' . 'student-list');
                    }
                } else {
                    $this->tpl->display('users/login.tpl');
                    exit();
                }
            } else {
                if (isset($_SESSION['loggedin']) || $action[1] == 'login') {
                    if ($action[1] == 'login') {
                        call_user_func_array([$controller, $action[1]], $params);
                        return;
                    }
                    if ($_SESSION['loggedin'] == true) {
                        call_user_func_array([$controller, $action[1]], $params);
                    }
                } else {
                    header('Location: ' . 'login');
                }
            }
            return;
        }
    }
    public function middleware()
    {
        if (isset($_SESSION['loggedin'])) {
            if ($_SESSION['loggedin'] == true)
                header('Location: ' . 'student-list');
            die();
            exit();
        } else {
            header('Location: ' . 'login');
            die();
            exit();
        }
    }

    // public function redirect($uri=''){
    //     if (preg_match('~^(http|https)~is', $uri)){
    //         $url = $uri;
    //     }else{
    //         $url = _WEB_ROOT.'/'.$uri;
    //     }

    //     header("Location: ".$url);
    //     exit;
    // }
}
