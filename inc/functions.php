<?php
/**
 * @param string $dir
 * @return void
 */
// Функция для проверки и создания папки
function checkAndCreateDirectory(string $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

/**
 * @param array $file
 * @return string|null
 */
// Функция для проверки ошибок загрузки файла
function checkUploadErrors(array $file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return UPLOAD_ERROR_MESSAGES[$file['error']];
    }
    return null;
}

/**
 * @param array $file
 * @param array $validTypes
 * @return string|null
 */
// Функция для проверки типа файла
function validateFileType(array $file, array $validTypes) {
    if (!in_array($file['type'], $validTypes)) {
        return 'Not available type';
    }
    return null;
}

/**
 * @param array $file
 * @param int $maxSize
 * @return string|null
 */
// Функция для проверки размера файла
function validateFileSize(array $file, int $maxSize) {
    if ($file['size'] > $maxSize) {
        return 'Photo is too large';
    }
    return null;
}

/**
 * @param array $file
 * @param string $dir
 * @return string|null
 */
// Функция для сохранения файла
function saveUploadedFile( array $file, string $dir) {
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension;
    $fileName = $dir . DIRECTORY_SEPARATOR . $uniqueName;
    if (!move_uploaded_file($file['tmp_name'], $fileName)) {
        return 'Photo was not uploaded';
    }
    return null;
}

/**
 * @return void
 */
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