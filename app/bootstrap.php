<?php
// Use const instead of define
define('PROJECT_ROOT', __DIR__ . '/..');

// Include configuration
include_once __DIR__ . '/config.php';

// Include core functionality
include_once __DIR__ . '/functions/controllers/PhotoController.php';
include_once __DIR__ . '/functions/validator/FileValidator.php';
include_once __DIR__ . '/functions/storages/FileStorage.php';
include_once __DIR__ . '/functions/response/JsonResponse.php';

// Initialize required directories
if (!file_exists(PROJECT_ROOT . '/' . PHOTO_DIR)) {
    mkdir(PROJECT_ROOT . '/' . PHOTO_DIR, 0777, true);
}