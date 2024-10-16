<?php
// Функция для проверки и создания папки
function checkAndCreateDirectory($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Функция для проверки ошибок загрузки файла
function checkUploadErrors($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return UPLOAD_ERROR_MESSAGES[$file['error']];
    }
    return null;
}

// Функция для проверки типа файла
function validateFileType($file, $validTypes) {
    if (!in_array($file['type'], $validTypes)) {
        return 'Not available type';
    }
    return null;
}

// Функция для проверки размера файла
function validateFileSize($file, $maxSize) {
    if ($file['size'] > $maxSize) {
        return 'Photo is too large';
    }
    return null;
}

// Функция для сохранения файла
function saveUploadedFile($file, $dir) {
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension;
    $fileName = $dir . DIRECTORY_SEPARATOR . $uniqueName;
    if (!move_uploaded_file($file['tmp_name'], $fileName)) {
        return 'Photo was not uploaded';
    }
    return null;
}

// Основная функция обработки загрузки
function handleFileUpload() {
    global $errors;
    checkAndCreateDirectory(PHOTO_DIR);
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_FILES['photo'])) {
            $errors[] = 'No POST data for file';
        } else {
            $photoFile = $_FILES['photo'];
            $error = checkUploadErrors($photoFile);
            if ($error) {
                $errors[] = $error;
            } else {
                $error = validateFileType($photoFile, PHOTO_AVAILABLE_TYPES);
                if ($error) {
                    $errors[] = $error;
                }
                $error = validateFileSize($photoFile, PHOTO_MAX_SIZE);
                if ($error) {
                    $errors[] = $error;
                }
                if (count($errors) === 0) {
                    $error = saveUploadedFile($photoFile, PHOTO_DIR);
                    if ($error) {
                        $errors[] = $error;
                    }
                }
            }
        }
    }
}