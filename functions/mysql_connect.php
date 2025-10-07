<?php
session_start ();
define('ROOT_DIR', realpath(__DIR__.'/..'));

if ($_SERVER["SERVER_ADDR"]=="127.0.0.1" OR $_SERVER["SERVER_ADDR"]=="::1"):
    $config = parse_ini_file(ROOT_DIR."/ini/config_local.ini");
    $host = $config['host'];
    $db   = $config['dbname'];
    $user = $config['user'];
    $pass = $config['password'];;
    $charset = 'utf8mb4';
else:
    $config = parse_ini_file(ROOT_DIR."/ini/config.ini");
    $host = $config['host'];
    $db   = $config['dbname'];
    $user = $config['user'];
    $pass = $config['password'];
    $charset = 'utf8mb4';
endif;

//pripojeni k databazovemu systemu
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
