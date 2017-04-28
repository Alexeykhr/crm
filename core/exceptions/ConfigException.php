<?php

namespace crm\core\exceptions;

use crm\core\traits\Files;

class ConfigException extends \Exception
{
    use Files;

    public function __construct($message)
    {
        parent::__construct($message);

        // Write to file.
    }
}
