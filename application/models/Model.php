<?php

namespace crmapp\models;

use crm\db\DBC;
use crmapp\Exception;

class Model
{
    protected $pdo;

    static protected $db_inst = null;

    /**
     * Model constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        if ( is_null(self::$db_inst) ) {
            if ( ! file_exists(__DIR__ . '/../../config/db.php') ) {
                throw new Exception("File db.php not found.");
            }

            $connected = require_once __DIR__ . '/../../config/db.php';
            
            self::$db_inst = new DBC($connected, ['charset' => 'UTF8']);
        }

        $this->pdo = self::$db_inst;
    }
}
