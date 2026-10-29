<?php
function compterquetesRestanctes(array $quetes): int
{
    $nombre = 0;

    foreach ($quetes as $quete) {
        if ((int) $quete['etat'] === 0) {
            $nombre++;
        }
    }

    return $nombre;
}

?>