<?php

namespace crm\app\models\common;

use crm\core\exceptions\ConfigException;
use Pixie\Connection;

class MainModel
{
    protected $db;

    static protected $db_inst = null;

    /**
     * Model constructor.
     *
     * @throws ConfigException
     */
    public function __construct()
    {
        if ( is_null(self::$db_inst) ) {
            if ( ! file_exists(CRM_DIR . '/conf2ig/db.php') ) {
                throw new ConfigException("File db.php not found.");
            }

            $config = require CRM_DIR . '/config/db.php';

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
