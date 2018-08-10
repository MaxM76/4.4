<?php

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=mmarkelov",
        "mmarkelov",
        "neto1755",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch (PDOException $e) {
    echo 'Невозможно установить соединение с базой данных';
}

$pdo->exec('SET NAMES utf8');