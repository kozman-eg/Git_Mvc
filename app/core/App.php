<?php

namespace Core;

defined('ROOTPATH') or exit('Access not allowed');

class App
{
    private $controller = 'Home';
    private $method = 'index';

    private function splitURL()
    {
        $URL = $_GET['url'] ?? 'Home';
        $URL = explode("/", filter_var(rtrim($URL, "/"), FILTER_SANITIZE_URL));
        return $URL;
    }

    public function loadController()
    {
        $URL = $this->splitURL();

        $controllerName = ucfirst($URL[0] ?? $this->controller);
        $controllerClass = "Controllers\\" . $controllerName;
        $controllerFile = "../app/controllers/" . $controllerName . ".php";

        if (file_exists($controllerFile)) {
            require $controllerFile;
            $this->controller = $controllerClass;
            unset($URL[0]);
        } else {
            $controllerFile = "../app/controllers/" . lcfirst($controllerName) . "/" . $controllerName . ".php";
            if (file_exists($controllerFile)) {
                require $controllerFile;
                $this->controller = $controllerClass;
                unset($URL[0]);
            } else {
                // تحميل كلاس 404
                $controllerClass = "Controllers\\_404";
                $controllerFile = "../app/controllers/_404.php";
                if (file_exists($controllerFile)) {
                    require $controllerFile;
                    $this->controller = $controllerClass;
                } else {
                    die("404 controller not found.");
                }
            }
        }

        // ✨ إنشاء الكائن هنا
        $controller = new $this->controller;

        // تحديد الميثود
        if (!empty($URL[1]) && method_exists($controller, $URL[1])) {
            $this->method = $URL[1];
            unset($URL[1]);
        }

        // تمرير المتبقي من الـ URL كـ arguments
        $params = array_values($URL);

        // استدعاء الدالة
        call_user_func_array([$controller, $this->method], $params);
    }
}
