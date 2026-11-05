<?php

$quetesExportees = [];
$messageErreur = null;

try {
    require __DIR__ . '/stockage.php';

    if (!is_file($fichierExport)) {
        throw new RuntimeException('Aucun export disponible.');
    }

    $contenu = file_get_contents($fichierExport);

    if ($contenu === false) {
        throw new RuntimeException('Impossible de lire le fichier.');
    }

    $donnees = json_decode($contenu, true, 512, JSON_THROW_ON_ERROR);

    if (!is_array($donnees) || !array_is_list($donnees)) {
        throw new RuntimeException('Le fichier ne contient pas une liste de quêtes.');
    }

    foreach ($donnees as $quete) {
        if (
            !is_array($quete)
            || !isset($quete['nom'])
            || !is_string($quete['nom'])
        ) {
            throw new RuntimeException('Une quête possède un nom invalide.');
        }
    }

    $quetesExportees = $donnees;
} catch (JsonException $erreur) {
    $messageErreur = 'Le fichier contient un JSON invalide.';
} catch (RuntimeException $erreur) {
    $messageErreur = $erreur->getMessage();
}

require __DIR__ . '/asset/header.php';
?>

<main>
    <h1>Dernier export des quêtes</h1>

    <?php if ($messageErreur !== null): ?>
        <p>
            <?php echo htmlspecialchars($messageErreur, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php elseif (empty($quetesExportees)): ?>
        <p>L’export ne contient aucune quête.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($quetesExportees as $quete): ?>
                <li>
                    <?php echo htmlspecialchars($quete['nom'], ENT_QUOTES, 'UTF-8'); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="index.php">Retour au journal</a>
</main>

<?php require __DIR__ . '/asset/footer.php'; ?>