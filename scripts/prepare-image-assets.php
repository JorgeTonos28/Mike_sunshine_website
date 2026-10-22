<?php

// Lossless layout decisions remain in source assets; this only creates delivery derivatives.
// Run from repository root: php scripts/prepare-image-assets.php
$root = dirname(__DIR__).'/public/images/';
foreach (['wizard', 'cat', 'mandrill'] as $name) {
    $source = imagecreatefrompng($root.'art/'.$name.'.png');
    $width = 800;
    $height = (int) round(imagesy($source) * $width / imagesx($source));
    $target = imagecreatetruecolor($width, $height);
    imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, imagesx($source), imagesy($source));
    imagewebp($target, $root.'art/'.$name.'.webp', 88);
    imagedestroy($target);
    imagedestroy($source);
}
$source = imagecreatefrompng($root.'design/canvas.png');
imagewebp($source, $root.'design/canvas.webp', 72);
imagedestroy($source);
// Use the exact supplied sun for the small favicon rather than inventing a brand symbol.
// Trim only empty transparent margins. Preserve the generated logo pixels.
foreach (['brand/logo-dark.png', 'design/brush-gold.png'] as $path) {
    $source = imagecreatefrompng($root.$path);
    $left = imagesx($source); $top = imagesy($source); $right = 0; $bottom = 0;
    for ($y = 0; $y < imagesy($source); $y++) {
        for ($x = 0; $x < imagesx($source); $x++) {
            if ((imagecolorat($source, $x, $y) >> 24) < 90) {
                $left = min($left, $x); $top = min($top, $y); $right = max($right, $x); $bottom = max($bottom, $y);
            }
        }
    }
    if ($right > $left && $bottom > $top) {
        $target = imagecrop($source, ['x' => $left, 'y' => $top, 'width' => $right - $left + 1, 'height' => $bottom - $top + 1]);
        imagesavealpha($target, true);
        imagepng($target, $root.str_replace('.png', '-trimmed.png', $path));
        imagedestroy($target);
    }
    imagedestroy($source);
}
