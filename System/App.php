<?php

namespace System;

class App
{
    public static function run()
    {
        // Получаем URL запроса
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Разбиваем URL на части
        //die($path);
        $pathParts = explode('/', $path);
        // Получаем имя контроллера
        $controller = !empty($pathParts[1]) ? $pathParts[1] : 'index';
        // Получаем имя действия
        $action = !empty($pathParts[2]) ? $pathParts[2] : 'index';
        // Формируем пространство имен для контроллера
        $controller = 'Application\\Controllers\\' . $controller . 'Controller';
        // Формируем наименование действия
        $action = 'action' . ucfirst($action);
        
        // Если класса не существует, выбрасывем исключение
        if (!class_exists($controller)) {
            throw new \ErrorException('Controller does not exist');
        }
        
        // Создаем экземпляр класса контроллера
        $objController = new $controller;
        
        // Если действия у контроллера не существует, выбрасываем исключение
        if (!method_exists($objController, $action)) {
            throw new \ErrorException('action does not exist');
        }
        
        // Вызываем действие контроллера
        $objController->$action();
    }
}

