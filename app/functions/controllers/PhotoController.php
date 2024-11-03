<?php
/**
 * Handles file upload process including validation and saving
 *
 * @return array Array of error messages if any occurred during upload
 */
function handleFileUpload(): array {
    checkAndCreateDirectory(PHOTO_DIR);
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['photos']['name'][0])) {
        $photoFiles = $_FILES['photos'];

        foreach ($photoFiles['name'] as $key => $value) {
            $file = [
                'name' => $photoFiles['name'][$key],
                'type' => $photoFiles['type'][$key],
                'tmp_name' => $photoFiles['tmp_name'][$key],
                'error' => $photoFiles['error'][$key],
                'size' => $photoFiles['size'][$key]
            ];

            if ($error = checkUploadErrors($file)) {
                $errors[] = $error;
                continue;
            }

            if ($error = validateFileType($file, PHOTO_AVAILABLE_TYPES)) {
                $errors[] = $error;
                continue;
            }

            if ($error = validateFileSize($file, PHOTO_MAX_SIZE)) {
                $errors[] = $error;
                continue;
            }

            if ($error = saveUploadedFile($file, PHOTO_DIR)) {
                $errors[] = $error;
            }
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    return $errors;
}