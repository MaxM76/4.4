<?php

$nameDB = 'mmarkelov';

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=$nameDB",
        "mmarkelov",
        "neto1755",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec('SET NAMES utf8');
}
catch (PDOException $e) {
    echo 'Невозможно установить соединение с базой данных';
}



