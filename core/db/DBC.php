<?php

namespace core\db;

use core\CRMException;

class DBC
{
    private $_pdo = null;

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
            $this->_pdo = new \PDO(
                'mysql:host=' . $connect['db_host'] . ';dbname=' . $connect['db_name'],
                $connect['db_user'],
                $connect['db_pass'],
                $config
            );

            // Temporary!
            $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            throw new CRMException("Error to connect with Database");
        }
    }

    public function q1($query)
    {
        $dbh = $this->_pdo->prepare($query);
        $dbh->execute();

        return $dbh->fetch();
    }
}
