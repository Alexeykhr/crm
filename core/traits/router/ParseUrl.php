<?php

namespace crm\core\traits\router;

class ParseUrl
{
    /**
     * @param string $key
     *
     * @return array
     */
    public static function parse(string $key)
    {
        $result = explode('/', $key);

        if ( empty($result[0]) ) {
            unset($result[0]);
        }

        return $result;
    }
}
