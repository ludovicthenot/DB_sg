<?php
$config = require __DIR__ . '/config.php';
$dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port']
    . ';dbname=' . $config['base'] . ';charset=utf8mb4';
try {
    $pdo = new PDO($dsn, $config['utilisateur'], $config['mot_de_passe'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    exit('Connexion impossible. Vérifiez le serveur MySQL, la base et config.php.');
}