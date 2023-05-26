<?php
require_once('config.php');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $con = new PDO($dsn, DB_USER, DB_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
