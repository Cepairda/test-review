<?php
// Включаем режим строгой типизации
declare(strict_types=1);

error_reporting(E_ALL);

// Подключаем файл реализующий автозагрузку
require_once __DIR__ . '/System/autoload.php';

// Запускаем приложение
System\App::run();