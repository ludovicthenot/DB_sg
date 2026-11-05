<?php
require __DIR__ . '/securite.php';
require __DIR__ . '/connexion.php';

verifierFormulaire();

$nom = $_POST['nom'] ?? '';
$etat = $_POST['etat'] ?? '';

if (!is_string($nom) || !is_string($etat)) {
    exit('Données invalides.');
}

$nom = trim($nom);
$etat = trim($etat);

if ($nom === '') {
    exit('Le nom est obligatoire.');
}

if (mb_strlen($nom, 'UTF-8') > 150) {
    http_response_code(400);
    exit('Le nom ne doit pas dépasser 150 caractères.');
}

if ($etat !== '0' && $etat !== '1') {
    exit('L\'état doit être 0 ou 1.');
}

$requete = $pdo->prepare(
    'INSERT INTO quete (nom, etat)
     VALUES (:nom, :etat)'
);

$requete->execute([
    'nom' => $nom,
    'etat' => $etat
]);

$_SESSION['message'] = 'La quete a bien été ajoutée.';

header('Location: index.php', true, 303);
exit;
