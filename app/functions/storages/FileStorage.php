<?php
/**
 * Checks if directory exists and creates it if not
 *
 * @param string $dir Directory path to check/create
 * @return void
 */
function checkAndCreateDirectory(string $dir): void {
    $fullPath = dirname(__DIR__, 3) . '/' . $dir;
    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0777, true);
    }
}

/**
 * Saves uploaded file to specified directory
 *
 * @param array $file The uploaded file array
 * @param string $dir Target directory for file storage
 * @return string|null Error message if save fails, null on success
 */
function saveUploadedFile(array $file, string $dir): ?string {
    $fullPath = dirname(__DIR__, 3) . '/' . $dir;
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid() . '.' . $extension;
    $fileName = $fullPath . DIRECTORY_SEPARATOR . $uniqueName;

    if (!move_uploaded_file($file['tmp_name'], $fileName)) {
        return 'Photo was not uploaded';
    }
    return null;
}

/**
 * Gets list of photos from specified directory
 *
 * @param string $directory Directory to scan for photos
 * @return array Array of photo file paths
 */
function getPhotos(string $directory): array {
    $photos = [];
    if (!is_dir($directory)) {
        return $photos;
    }

    $iterator = new DirectoryIterator($directory);

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'heic'])) {
                $photos[] = PHOTO_DIR . '/' . $file->getFilename();
            }
        }
    }

    return $photos;
}