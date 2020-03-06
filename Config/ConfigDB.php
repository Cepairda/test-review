<?php

namespace Config;

use System\Base\Config;

class ConfigDB implements Config
{
    static public function get()
    {
        return [
            'dsn' => 'mysql:host=mysql;dbname=YYY',
            'user_db' => 'root',
            'password_db' => '',
            'options' => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"],
        ];
    }
}