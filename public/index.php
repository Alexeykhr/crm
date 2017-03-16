<?php

require_once __DIR__ . '/../vendor/autoload.php';

$configDB = require __DIR__ . '/../config/db.php';
new \Pixie\Connection($configDB['driver'], $configDB);
define('CRM_DIR', __DIR__ . '/../');

// For test
$user = new \crm\application\models\orm\Users();
$user->test();
