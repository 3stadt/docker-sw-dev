<?php

class RandomImageGenerator
{
    private $config;
    private $image;

    public function __construct(ImageConfig $imageConfig)
    {
        $this->config = $imageConfig;
        $this->generateImageData();
        return $this;
    }

    public static function create(ImageConfig $imageConfig)
    {
        $generator = new self($imageConfig);
        return $generator->getImageData();
    }

    public function getImageData()
    {
        return $this->image;
    }

    private function generateImageData()
    {
        $width = $this->config->getImageWidth();
        $height = $this->config->getImageHeight();
        $img = imagecreate($width, $height);
        $lastRed = $lastGreen = $lastBlue = 127;

        $position = $linewidth = 0;
        while ($position < $width) {
            $currentRed = $this->getColor($lastRed);
            $currentGreen = $this->getColor($lastGreen);
            $currentBlue = $this->getColor($lastBlue);

            $lineColor = imagecolorallocate($img, $currentRed, $currentGreen, $currentBlue);
            $oldLineWidth = $linewidth;
            $linewidth = mt_rand(1, $this->config->getMaxLineWidth());
            $newPosition = $position + $oldLineWidth;
            imagefilledrectangle($img, $position, 0, $newPosition, $height, $lineColor);

            $position = $newPosition + $linewidth;
            $lastRed = $currentRed;
            $lastGreen = $currentGreen;
            $lastBlue = $currentBlue;
        }
        $font = 'fonts/OpenSans-Regular.ttf';
        $black = imagecolorallocate($img, 0, 0, 0);
        $text = $this->config->getText();
        $font_size = 72;
        $boundingBox = $this->imagettfbboxextended($font_size, 0, $font, $text);
        while ($boundingBox['width'] > $width || $boundingBox['height'] > $height) {
            $font_size--;
            $boundingBox = $this->imagettfbboxextended($font_size, 0, $font, $text);
        }

        imagettftext($img, $font_size, 0, $boundingBox['x'], $boundingBox['y'], $black, $font,$text);
        $this->image = $img;
    }

    private function getColor($x)
    {
        $min = $x - $this->config->getColorDeviation();
        $max = $x + $this->config->getColorDeviation();

        if ($min < 0) {
            $min = 0;
        }
        if ($max > 255) {
            $max = 255;
        }
        srand($this->config->getSeed());
        return rand($min, $max);
    }

    private function imagettfbboxextended($size, $angle, $fontfile, $text) {
        $bbox = imagettfbbox($size, $angle, $fontfile, $text);

        if($bbox[0] >= -1) {
            $bbox['x'] = abs($bbox[0] + 1) * -1;
        } else {
            $bbox['x'] = abs($bbox[0] + 2);
        }

        $bbox['width'] = abs($bbox[2] - $bbox[0]);
        if($bbox[0] < -1) {
            $bbox['width'] = abs($bbox[2]) + abs($bbox[0]) - 1;
        }

        $bbox['y'] = abs($bbox[5] + 1);

        $bbox['height'] = abs($bbox[7]) - abs($bbox[1]);
        if($bbox[3] > 0) {
            $bbox['height'] = abs($bbox[7] - $bbox[1]) - 1;
        }

        return $bbox;
    }
}
