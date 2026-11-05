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