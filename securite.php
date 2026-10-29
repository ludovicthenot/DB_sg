<?php

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
function verifierFormulaire(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); header('Allow: POST'); exit('Utilisez un formulaire POST.');
    }
    $jeton = $_POST['csrf'] ?? '';
    if (!is_string($jeton) || !hash_equals($_SESSION['csrf'], $jeton)) {
        http_response_code(403); exit('Formulaire expiré ou invalide. Rechargez la page.');
    }
}