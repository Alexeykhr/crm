<?php

namespace crm\db;

use crmapp\Exception;

class DBC
{
    /**
     * DBC constructor.
     *
     * @param $connect
     * @param $config
     *
     * @throws Exception
     */
    public function __construct($connect, $config)
    {
        try {
            return $this->pdo = new \PDO(
                'mysql:host' . $connect['db_host'] . ';dbname=' . $connect['db_name'],
                $connect['db_user'],
                $connect['db_pass'],
                $config
            );
        } catch (\PDOException $e) {
            throw new Exception("Error to connect with Database");
        }
    }
}
