<?php

require_once __DIR__ . '/securite.php';

verifierFormulaire();

$theme = $_POST['theme'] ?? "";

if (!in_array($theme, ['clair', 'sombre'], true)) {
   http_response_code(400);
   exit('Le theme choisi et invalide');

}

$connexionHttps = isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] !== 'off';

setcookie('theme', $theme, [
   'expires' => time() + 7 * 24 * 60 * 60,
   'path' => '/',
   'secure' => $connexionHttps,
   'httponly' => true,
   'samesite' => 'Lax',
]);

header('location: index.php', true, 303);
exit;