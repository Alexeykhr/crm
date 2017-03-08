<?php

namespace crm\application\models\common;

use crm\core\CRMException;
use Pixie\Connection;

class Model
{
    protected $db;

    static protected $db_inst = null;

    /**
     * Model constructor.
     *
     * @see https://github.com/usmanhalalit/pixie
     *
     * @throws CRMException
     */
    public function __construct()
    {
        if ( is_null(self::$db_inst) ) {
            if ( ! file_exists(__DIR__ . '/../../../config/db.php') ) {
                throw new CRMException("File db.php not found.");
            }

            $config = require __DIR__ . '/../../../config/db.php';

            self::$db_inst = new Connection($config['driver'], $config, 'QB');
        }

        $this->db = self::$db_inst;
    }

    /**
     * Model destructor.
     */
    public function __destruct()
    {
        $this->db = null;
        self::$db_inst = null;
    }
}
