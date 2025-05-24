<?php
define("SERVERNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "php-blog");
define("DNS", "mysql:host=" . SERVERNAME . ";dbname=" . DB_NAME . ";charset=utf8mb4");
try {
    $db = new PDO(DNS, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "not connected to database";
    exit;
}
