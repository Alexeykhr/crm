<?php

namespace core\db;

/*
 * Development..
 */

class QB
{
    public static $query;
    public static $table;

    private static $_instance = null;

    public function __construct()
    {
        // Disable creation of public instances
    }

    public static function table($table)
    {
        if (self::$_instance === null) {
            self::$table = $table;
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function select($q)
    {
        self::$query = 'SELECT ';

        return $this;
    }

    public function where()
    {
        self::$query = 'WHERE ';

        return $this;
    }

    public function run() {
        self::$_instance = null;

        return self::$query;
    }
}
