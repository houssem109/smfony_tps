#!/usr/bin/env php
<?php
$dsn = 'pgsql:host=postgres;port=5432;dbname=symfony';
$user = 'symfony';
$password = 'symfony';

while (true) {
    try {
        $pdo = new PDO($dsn, $user, $password);
        echo "Database is ready!\n";
        exit(0);
    } catch (PDOException $e) {
        echo "Waiting for database...\n";
        sleep(2);
    }
}