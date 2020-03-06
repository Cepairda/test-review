<?php

namespace System;

class DB
{
    private static $_instance = null;

	private function __construct ($config) {

        self::$_instance = new \PDO(
            $config['dsn'],
            $config['user_db'],
            $config['password_db'],
            $config['options']
        );
    }

	public static function getInstance($config)
    {
        if (self::$_instance === null) {
            new self($config);
        }

        return self::$_instance;
    }

    private function __clone () {}
	private function __wakeup () {}
}