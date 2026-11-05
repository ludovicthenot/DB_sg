<?php

require_once __DIR__ . '/securite.php';
require __DIR__ . '/asset/header.php';

?>

<main>
    <h1>Envoyer un fichier texte</h1>

    <p>Format accepté : .txt. Taille maximale : 100 Ko.</p>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input
            type="hidden"
            name="csrf"
            value="<?php echo htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8'); ?>"
        >

        <label for="document">Fichier texte :</label>

        <input
            type="file"
            id="document"
            name="document"
            accept=".txt,text/plain"
        >

        <button type="submit">Envoyer</button>
    </form>

    <p>
        <a href="index.php">Retour au journal</a>
    </p>
</main>

<?php require __DIR__ . '/asset/footer.php'; ?>