<?php
require __DIR__ . '/securite.php';
require_once __DIR__ . '/fonction.php';
require_once __DIR__ . '/connexion.php';

$requete = $pdo->query(
    'SELECT id_quete, nom, etat FROM quete'
);
$quetes = $requete->fetchAll();

$total = count($quetes);
$restantes = compterquetesRestanctes($quetes);
$terminer = $total - $restantes;

require __DIR__ . '/asset/header.php';
?>
    <h1>Quêtes :</h1>

    <form action="ajouter.php" method="post">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">

        <label for="nom">Nom de la quête</label>
        <input id="nom" name="nom" required maxlength="255">

        <label for="etat">État</label>
        <select id="etat" name="etat">
            <option value="0">À faire</option>
            <option value="1">Terminée</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>

    <p>
        Quêtes au total : <?php echo $total; ?><br>
        Quêtes a faire : <?php echo $restantes; ?><br>
        Quêtes terminées : <?php echo $terminer; ?>
    </p>
    <ul>
        <?php foreach ($quetes as $q): ?>
        <li><?php echo htmlspecialchars($q['nom']); ?>
            <?php if ((int) $q['etat'] === 1) { ?>
                <span style="color: green;">Terminée</span>
            <?php } else { ?>
                <span style="color: red;">À faire</span>
            <?php } ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php require __DIR__ . '/asset/footer.php'; ?>
