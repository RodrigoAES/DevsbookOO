<?php
session_start();
$base = 'https://localhost/devsbookoo';

$db_name = 'devsbookoo';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'yrden.quietus616';

$pdo = new PDO("mysql:dbname=$db_name; dbhost=$db_host", $db_user, $db_pass);