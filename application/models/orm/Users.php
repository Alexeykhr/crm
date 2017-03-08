<?php

namespace crm\application\models\orm;

use crm\application\models\common\Model;

class Users extends Model
{
    const TABLE_USER = 'users';

    public function test()
    {
        $query = \QB::table(self::TABLE_USER)->where('id', '=', '2');

        print_r( $query->get() );
    }
}
