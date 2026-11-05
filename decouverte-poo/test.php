<?php

require __DIR__ . '/Quete.php';

$quete1 = new Quete('Explorer la forêt');
$quete2 = new Quete('Retrouver le grimoire');

$quete1->terminer();

$quetes = [$quete1, $quete2];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découverte des objets</title>
</head>
<body>
    <main>
        <h1>Journal de quêtes avec des objets</h1>

        <ul>
            <?php foreach ($quetes as $quete): ?>
                <li>
                    <?php echo htmlspecialchars($quete->nom, ENT_QUOTES, 'UTF-8'); ?>
                    — <?php echo $quete->terminee ? 'Terminée' : 'En cours'; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <p><a href="../index.php">Retour au journal</a></p>
    </main>
</body>
</html>
