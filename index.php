<?php
require_once __DIR__ . '/fonction.php';
require_once __DIR__ . '/donnees.php';

$total = count($quetes);
$restantes = compterquetesRestanctes($quetes);
$terminer = $total - $restantes;

require __DIR__ . '/asset/header.php';
?>
    <h1>Quêtes :</h1>
    <p>
        Quêtes au total : <?php echo $total; ?><br>
        Quêtes a faire : <?php echo $restantes; ?><br>
        Quêtes terminées : <?php echo $terminer; ?>
    </p>
    <ul>
        <?php foreach ($quetes as $q): ?>
        <li><?php echo $q['nomquete']; ?>
            <span style="color: <?php echo $q['etat'] ? 'green' : 'red'; ?>;">
                <?php echo $q['etat'] ? 'Terminée' : 'À faire'; ?>
            </span>
        </li>
        <?php endforeach; ?>
    </ul>
<?php require __DIR__ . '/asset/footer.php'; ?>
