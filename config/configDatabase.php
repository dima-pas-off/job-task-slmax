<?php

    $host = '127.0.0.1';
    $dbName = 'task';
    $user = 'root';
    $password = '';
    $charset = 'utf8';


    $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
    $option = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];


    return [
        'dsn'      => $dsn,
        'user'     => $user,
        'password' => $password,
        'option'   => $option
    ];
?>