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
    ]

    );
}

$quetes = $requete->fetchAll();

$total = count($quetes);
$restantes = compterquetesRestanctes($quetes);
$terminer = $total - $restantes;

$theme = $_COOKIE['theme'] ?? 'clair';

if (!in_array($theme, ['clair', 'sombre'], true)) {
    $theme = 'clair';
}

require __DIR__ . '/asset/header.php';
?>
    <main class="theme-<?php echo htmlspecialchars($theme, ENT_QUOTES, 'UTF-8'); ?>">
        <h1>Quêtes :</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
    
        <p>
            <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <p>Filtre choisi: <?php echo htmlspecialchars($filtre); ?></p>
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
    <form action="preference.php" method="post">
        <input type="hidden"
        name="csrf"
        value="<?php echo htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8');  ?>"
        >
        <label for="theme">Affichage :</label>
        <select name="theme" id="theme">
            <option value="clair" <?php echo $theme === 'clair' ? 'selected' : ''; ?>>Clair</option>
            <option value="sombre" <?php echo $theme === 'sombre' ? 'selected' : ''; ?>>Sombre</option>
        </select>
        <button type="submit">Enregistrer le theme</button>
    </form>
    <p>
        Quêtes affichées : <?php echo $total; ?><br>
        Quêtes a faire : <?php echo $restantes; ?><br>
        Quêtes terminées : <?php echo $terminer; ?>
    </p>
    <nav>
        <a href="index.php">Toute les quêtes</a>
        <a href="index.php?etat=a-faire">À faire</a>
        <a href="index.php?etat=terminee">Terminées</a>
    </nav>
    <?php if (empty($quetes)): ?>

        <p>Aucune quête ne correspond à ce filtre.</p>

    <?php else: ?>

        <ul>
            <?php foreach ($quetes as $q): ?>
                <li>
                    <?php echo htmlspecialchars($q['nom'], ENT_QUOTES, 'UTF-8'); ?>

                    <?php if ((int) $q['etat'] === 1) { ?>
                        <span>Terminée</span>
                    <?php } else { ?>
                        <span>À faire</span>
                    <?php } ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>
</main>

<?php require __DIR__ . '/asset/footer.php'; ?>
