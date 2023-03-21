<?php

$config = [
    'db' => [
        'host' => 'localhost',
        'name' => 'parserphp_db',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf-8',
    ],
];

$db = new PDO ('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'], $config['db']['user'], $config['db']['password']);
$db->query('SET character_set_connection = ' . $config['db']['charset'] . ';');
$db->query('SET character_set_client = ' . $config['db']['charset'] . ';');
$db->query('SET character_set_results = ' . $config['db']['charset'] . ';');