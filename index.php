<?php
require __DIR__ . '/securite.php';
require_once __DIR__ . '/fonction.php';
require_once __DIR__ . '/connexion.php';

$filtre = $_GET['etat'] ?? 'toutes';

if (!in_array($filtre, ['toutes', 'a-faire', 'terminee'], true)) {
    $filtre = 'toutes';
}

if ($filtre === 'toutes') {
    $requete = $pdo->query(
        'SELECT id_quete, nom, etat
         FROM quete
         ORDER BY id_quete DESC'
    );
} else {
    $etat = 0;

    if ($filtre === 'terminee') {
        $etat = 1;
    }

    $requete = $pdo->prepare(
        'SELECT id_quete, nom, etat
         FROM quete
         WHERE etat = :etat
         ORDER BY id_quete DESC'
    );

    $requete->execute([
        'etat' => $etat
    ]);
}

$quetes = $requete->fetchAll();

$total = count($quetes);
$restantes = compterquetesRestanctes($quetes);
$terminer = $total - $restantes;

$theme = $_COOKIE['theme'] ?? 'clair';

if (!in_array($theme, ['clair', 'sombre'], true)) {
    $theme = 'clair';
}

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

require __DIR__ . '/vues/liste.php';
