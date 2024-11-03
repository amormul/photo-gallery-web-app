<?php
include_once './app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = handleFileUpload();
    if (!empty($errors)) {
        sendJsonResponse(['errors' => $errors], 400);
    }
    sendJsonResponse(['success' => true]);
}