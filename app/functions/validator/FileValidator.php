<?php
/**
 * Checks for upload errors in the file array
 *
 * @param array $file The uploaded file array
 * @return string|null Error message if error occurred, null otherwise
 */
function checkUploadErrors(array $file): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return UPLOAD_ERROR_MESSAGES[$file['error']];
    }
    return null;
}

/**
 * Validates the file type against allowed types
 *
 * @param array $file The uploaded file array
 * @param array $validTypes Array of valid MIME types
 * @return string|null Error message if validation fails, null otherwise
 */
function validateFileType(array $file, array $validTypes): ?string {
    if (!in_array($file['type'], $validTypes)) {
        return 'Not available type';
    }
    return null;
}

/**
 * Validates the file size against maximum allowed size
 *
 * @param array $file The uploaded file array
 * @param int $maxSize Maximum allowed file size in bytes
 * @return string|null Error message if validation fails, null otherwise
 */
function validateFileSize(array $file, int $maxSize): ?string {
    if ($file['size'] > $maxSize) {
        return 'Photo is too large';
    }
    return null;
}