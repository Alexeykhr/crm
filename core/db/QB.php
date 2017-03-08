<?php

namespace core\db;

/*
 * Development..
 */

class QB
{
    public static $query;

    private static $_instance = null;

    public function __construct()
    {
        // Disable creation of public instances.
    }

    public static function inst()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function select($q)
    {
        if ( is_array($q) ) {
            $q = implode(',', $q);
        }

        self::$query = 'SELECT ' . $q;

        return $this;
    }

    public function from($table)
    {
        self::$query .= ' FROM ' . $table;

        return $this;
    }

    public function getQuery()
    {
        return self::$query;
    }

    public function get()
    {
        self::$_instance = null;

        return self::$query;
    }
}
