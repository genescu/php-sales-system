<?php
define('DB_HOST', '172.17.0.1');
define('DB_NAME', 'crm_bookshop');
define('DB_USER', 'root_1');
define('DB_PASS', 'Password123#@!');

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}