<?php
/**
 * Checks if a directory exists and creates it if not.
 * @param string $dir
 * @return void
 */
function checkAndCreateDirectory(string $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true); // Create directory if it doesn't exist
    }
}

/**
 * Checks for upload errors in the file array.
 * @param array $file
 * @return string|null
 */
function checkUploadErrors(array $file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return UPLOAD_ERROR_MESSAGES[$file['error']]; // Return error message if any
    }
    return null;
}

/**
 * Validates the file type against allowed types.
 * @param array $file
 * @param array $validTypes
 * @return string|null
 */
function validateFileType(array $file, array $validTypes) {
    if (!in_array($file['type'], $validTypes)) {
        return 'Not available type'; // Invalid file type
    }
    return null;
}

/**
 * Validates the file size against the maximum allowed size.
 * @param array $file
 * @param int $maxSize
 * @return string|null
 */
function validateFileSize(array $file, int $maxSize) {
    if ($file['size'] > $maxSize) {
        return 'Photo is too large'; // File exceeds size limit
    }
    return null;
}

/**
 * Saves the uploaded file to the specified directory.
 * @param array $file
 * @param string $dir
 * @return string|null
 */
function saveUploadedFile(array $file, string $dir) {
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension; // Generate unique file name
    $fileName = $dir . DIRECTORY_SEPARATOR . $uniqueName; // Full file path
    if (!move_uploaded_file($file['tmp_name'], $fileName)) {
        return 'Photo was not uploaded'; // Upload failed
    }
    return null;
}

/**
 * Handles the file upload process, including validation and saving files.
 * @return array|null
 */
function handleFileUpload() {
    checkAndCreateDirectory(PHOTO_DIR); // Ensure photo directory exists
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['photos']['name'][0])) {
        $photoFiles = $_FILES['photos'];

        // Process each uploaded file
        foreach ($photoFiles['name'] as $key => $value) {
            $file = [
                'name' => $photoFiles['name'][$key],
                'type' => $photoFiles['type'][$key],
                'tmp_name' => $photoFiles['tmp_name'][$key],
                'error' => $photoFiles['error'][$key],
                'size' => $photoFiles['size'][$key]
            ];

            // Check for upload errors
            if ($error = checkUploadErrors($file)) {
                $errors[] = $error;
                continue; // Skip to next file
            }

            // Validate file type
            if ($error = validateFileType($file, PHOTO_AVAILABLE_TYPES)) {
                $errors[] = $error;
                continue; // Skip to next file
            }

            // Validate file size
            if ($error = validateFileSize($file, PHOTO_MAX_SIZE)) {
                $errors[] = $error;
                continue; // Skip to next file
            }

            // Save file and check for errors
            if ($error = saveUploadedFile($file, PHOTO_DIR)) {
                $errors[] = $error;
            }
        }

        // Redirect after processing to prevent re-upload
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit; // Stop script execution
    }

    return $errors; // Return errors if any
}
