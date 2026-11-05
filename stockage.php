<?php

$dossierPrive = 'C:/MAMP/donnees-privees-quetes';

$dossierUploads = $dossierPrive . '/uploads';
$fichierExport = $dossierPrive . '/quetes.json';

foreach ([$dossierPrive, $dossierUploads] as $dossier) {
    if (!is_dir($dossier)) {
        if (!mkdir($dossier, 0700, true) && !is_dir($dossier)) {
            throw new RuntimeException(
                'Impossible de créer le dossier de stockage.'
            );
        }
    }

    if (!is_writable($dossier)) {
        throw new RuntimeException(
            'Le dossier de stockage n’est pas accessible en écriture.'
        );
    }
}
