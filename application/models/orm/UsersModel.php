<?php

namespace crm\app\models\orm;

use crm\app\models\common\MainModel;

class UsersModel extends MainModel
{
    const TABLE_USER = 'users';

    public function getUser()
    {
        $query = \QB::table(self::TABLE_USER)->where('id', '=', '2');

        print_r( $query->get() );
    }
}
