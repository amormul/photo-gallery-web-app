<?php
include_once 'inc/config.php';
include_once 'inc/functions.php';

// Обработка загрузки
handleFileUpload();
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
                <input type="file" class="custom-file-input" name="photos[]" multiple  accept="image/jpeg,image/png,image/webp,image/heic" required>
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
            foreach ($photos as $photo): ?>
                <a href="<?= $photo ?>" data-pswp-width="600" data-pswp-height="600">
                    <img src="<?= $photo ?>" alt="photo">
                </a>
            <?php endforeach; ?>
        </div>
        <script type="module" src="js/script.js"></script>
    </body>
</html>
