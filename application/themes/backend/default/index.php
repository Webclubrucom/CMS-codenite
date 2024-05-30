<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="<?php echo THEME_BACK_URL ?>/default/assets/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo THEME_BACK_URL ?>/default/assets/css/style.css">
    <link rel="icon" href="<?php echo UPLOADS_URL ?>/logo.webp" type="image/*">
</head>
<body>

    <?php $this->template->header(); ?>
    <?= $content; ?>
    <script src="<?php echo THEME_BACK_URL ?>/default/assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>