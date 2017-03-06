<?php

namespace application\models\orm;

use application\models\common\Model;
use core\db\QB;

class Users extends Model
{
    const TABLE_USER = 'users';

    /**
     * For tests.
     *
     * @return int
     */
    public function getCount()
    {
        $query = QB::table(self::TABLE_USER)->select('*')->run();
        echo $query . '<br>';

        $query = QB::table(self::TABLE_USER)->select('1')->run();
        echo $query . '<br>';

        $query = QB::table('people')->select('*')->run();
        echo $query . '<br>';

        $query = QB::table('s')->run();
        echo $query . '<br>';

        return 0;
    }
}
