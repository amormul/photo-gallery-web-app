<?php
const UPLOAD_ERROR_MESSAGES = [
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
];

const PHOTO_MAX_SIZE = 2 * 1024 * 1024; // 2 MB
const PHOTO_AVAILABLE_TYPES = [
    'image/jpeg',
    'image/png',
    'image/webp',
    'image/heic'
];
const PHOTO_DIR = 'images';

if (!is_dir(PHOTO_DIR)) {
    mkdir(PHOTO_DIR, 0777, true);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['photo'])) {
        $errors[] = 'No POST data for file';
    } else {
        $photoFile = $_FILES['photo'];
        if ($photoFile['error'] !== UPLOAD_ERR_OK) {
            $errors[] = UPLOAD_ERROR_MESSAGES[$photoFile['error']];
        } else {
            if (!in_array($photoFile['type'], PHOTO_AVAILABLE_TYPES)) {
                $errors[] = 'Not available type';
            }
            if ($photoFile['size'] > PHOTO_MAX_SIZE) {
                $errors[] = 'Photo is too large';
            }
            if (count($errors) === 0) {
                $extension = pathinfo($photoFile['name'], PATHINFO_EXTENSION);
                $uniqueName = uniqid() . '.' . $extension;
                $fileName = PHOTO_DIR . DIRECTORY_SEPARATOR . $uniqueName;
                if (!move_uploaded_file($photoFile['tmp_name'], $fileName)) {
                    $errors[] = 'Photo was not uploaded';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Фотогалерея</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css">
    </head>
    <body>
        <div class="upload-container">
            <h2>Upload Image</h2>
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <input type="file" class="custom-file-input" name="photo" accept="image/jpeg,image/png,image/webp,image/heic" required>
                <button type="submit" class="upload-btn">Завантажити</button>
            </form>
            <p>Виберіть зображення</p>
            <div class="formats">
                <span>png</span>
                <span>jpeg</span>
                <span>jpg</span>
                <span>webp</span>
                <span>heic</span>
            </div>
        </div>
        <!-- Виведення помилок -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <!-- Галерея -->
        <div class="gallery" id="gallery">
            <?php
            $photos = glob(PHOTO_DIR . '/*.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);
            foreach ($photos as $photo): ?>
                <a href="<?= $photo ?>" data-pswp-width="600" data-pswp-height="600">
                    <img src="<?= $photo ?>" alt="photo">
                </a>
            <?php endforeach; ?>
        </div>
        <script type="module" src="js/script.js"></script>
    </body>
</html>
