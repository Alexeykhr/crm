<?php

namespace core\db;

use core\CRMException;

class DBC
{
    /**
     * DBC constructor.
     *
     * @param array $connect
     * @param array $config
     *
     * @throws CRMException
     */
    public function __construct($connect, $config = [])
    {
        try {
            return $this->pdo = new \PDO(
                'mysql:host' . $connect['db_host'] . ';dbname=' . $connect['db_name'],
                $connect['db_user'],
                $connect['db_pass'],
                $config
            );
        } catch (\PDOException $e) {
            throw new CRMException("Error to connect with Database");
        }
    }
}
