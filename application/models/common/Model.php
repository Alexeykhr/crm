<?php

namespace application\models\common;

use core\db\DBC;
use core\CRMException;

class Model
{
    protected $pdo;

    static protected $db_inst = null;

    /**
     * Model constructor.
     *
     * @throws CRMException
     */
    public function __construct()
    {
        if ( is_null(self::$db_inst) ) {
            if ( ! file_exists(__DIR__ . '/../../../config/db.php') ) {
                throw new CRMException("File db.php not found.");
            }

            $connected = require_once __DIR__ . '/../../../config/db.php';

            self::$db_inst = new DBC($connected, ['charset' => 'UTF8']);
        }

        $this->pdo = self::$db_inst;
    }

    /**
     * Model destructor.
     */
    public function __destruct()
    {
        $this->pdo = null;
        self::$db_inst = null;
    }
}
