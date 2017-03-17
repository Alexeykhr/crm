<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('CRM_DIR', __DIR__ . '/../');

/*
 * For test
 */

//$user = new \crm\application\models\orm\Users();
//$user->test();

$loader = new Twig_Loader_Filesystem(CRM_DIR . '/application/views/login');
$twig = new Twig_Environment($loader);
echo $twig->render('index.twig');
