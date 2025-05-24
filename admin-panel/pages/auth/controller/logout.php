<?php

require_once 'C:/xampp/htdocs/weblog-project/init.php';

session_unset();
session_destroy();

header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/login.php");
exit;
