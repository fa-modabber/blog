<?php
session_start();
ob_start();

define("SERVERNAME","localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD","");
define("DB_NAME","php_course_blog");
define("DNS","mysql:host=".SERVERNAME.";dbname=".DB_NAME.";charset=utf8mb4");