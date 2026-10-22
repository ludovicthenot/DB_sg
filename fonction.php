<?php
function compterquetesRestanctes(array $quetes): int
{
    $nombre = 0;

    foreach ($quetes as $quete) {
        if ($quete["etat"] === false) {
            $nombre++;
        }
    }

    return $nombre;
}

?>