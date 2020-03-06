<?php

namespace System\Base;

use System\DB;
use Config\ConfigDB;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::getInstance(ConfigDB::get());
    }
}