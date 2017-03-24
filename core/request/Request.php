<?php

namespace crm\core\request;

class Request
{
    public function __construct()
    {

    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function clean($data)
    {
        return strip_tags( htmlspecialchars($data) );
    }
}
