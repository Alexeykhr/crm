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
        $sql = QB::inst()->select('*')->from(self::TABLE_USER)->get();

        $query = $this->db->q1( $sql );
        print_r( $query );
        echo '<br>';
        die('ok');
//        echo $query . '<br>';

        return 0;
    }
}
