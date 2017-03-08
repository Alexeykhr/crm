<?php

require_once __DIR__ . '/../autoload.php';

$model = new application\models\orm\Users(); // Test
$model->getCount();
