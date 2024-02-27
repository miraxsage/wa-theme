<?php
    $settings = WaThemeSettings::get();
    ?>
<!doctype html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="<?= bloginfo("charset") ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php the_title(); ?></title>
    <link rel="stylesheet" href="<?= WA_CSS_URI ?>style.css">
    <?php wp_head() ?>
    <?php echo WaThemeSettings::get()->common__code_at_head_end; ?>
</head>
<body<?= schema_item("body", " ") ?>>
