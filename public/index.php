<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('CRM_DIR', __DIR__ . '/..');

// Test PSR - 4
new \crm\app\models\common\MainModel();

// Test lib and render page
$loader = new Twig_Loader_Filesystem(CRM_DIR . '/application/views/');
$twig = new Twig_Environment($loader);
echo $twig->render('auth/authorization.twig');
//echo $twig->render('auth/registration.twig');
//echo $twig->render('auth/reset.twig');

//echo $_SERVER['REQUEST_URI'];
