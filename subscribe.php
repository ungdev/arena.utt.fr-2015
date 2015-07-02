<?php

// Needed by jQuery :-)
header('Content-Type: application/json');

if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $cleaned = trim($_POST['email']);
    file_put_contents('./subscription-emails.txt', $cleaned . "\n", FILE_APPEND | LOCK_EX);
    die(json_encode(['status' => 'success', 'message' => 'Merci, tu as bien été ajouté !']));
}

die(json_encode(['status' => 'error', 'message' => 'Adresse invalide !']));
