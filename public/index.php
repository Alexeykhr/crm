<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('CRM_DIR', __DIR__ . '/../');

/*
 * For test
 */

// Test PSR - 4
new \crm\app\models\common\MainModel();

// Test lib and render page
//$loader = new Twig_Loader_Filesystem(CRM_DIR . '/application/views/login');
//$twig = new Twig_Environment($loader);
//echo $twig->render('index.twig');

//echo $_SERVER['REQUEST_URI'];
