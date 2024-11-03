<?php
$errors = [];
if (function_exists('handleFileUpload')) {
    $errors = handleFileUpload();
}
$photos = function_exists('getPhotos') ? getPhotos(PROJECT_ROOT . '/' . PHOTO_DIR) : [];
?>

<div class="upload-container">
    <h2>Upload Image</h2>
    <form action="/index.php" method="POST" enctype="multipart/form-data">
        <input type="file" class="custom-file-input" name="photos[]" multiple accept="image/jpeg,image/png,image/webp,image/heic" required>
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

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="gallery" id="gallery">
    <?php foreach ($photos as $photo): ?>
        <a href="<?= htmlspecialchars($photo) ?>" data-pswp-width="600" data-pswp-height="600">
            <img src="<?= htmlspecialchars($photo) ?>" alt="photo">
        </a>
    <?php endforeach; ?>
</div>