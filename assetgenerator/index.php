<?php

$filename = $_GET['file'];

if (empty($filename)) {
    throw new Exception("No filename in url.");
}

$allowedExtensions = ['png', 'jpg', 'gif'];

$filenameExploded = explode('.', $filename);

if (count($filenameExploded) == 1) {
    throw new Exception("No filename extension in url.");
}

$extension = strtolower($filenameExploded[count($filenameExploded) - 1]);

if ($extension == 'jpeg') {
    $extension = 'jpg';
}

if (!in_array($extension, $allowedExtensions)) {
    throw new Exception("Filename extension not allowed/unknown");
}

require_once 'classes/ImageConfig.php';
require_once 'classes/RandomImageGenerator.php';

$config = new ImageConfig();

$path = explode('/', $filename);

$size = [];

if (count($path) > 1) {
    $size = explode('x', strtolower($path[0]));
}

if (count($size) == 2 && (int)$size[0] > 0 && (int)$size[1] > 0) {
    $config->setImageWidth((int)$size[0]);
    $config->setImageHeight((int)$size[1]);
}

$config->setText($bareFilename = explode('.',$path[count($path) - 1])[0]);

$generator = new RandomImageGenerator($config);
$imgRes = $generator->getImageData();

switch ($extension) {
    case 'png':
        header('Content-Type: image/png');
        imagepng($imgRes);
        break;
    case 'jpg':
        header('Content-Type: image/jpeg');
        imagejpeg($imgRes);
        break;
    case 'gif':
        header('Content-Type: image/gif');
        imagegif($imgRes);
        break;
}

imagedestroy($imgRes);