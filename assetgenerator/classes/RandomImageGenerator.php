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
        $text = $this->config->getText();
        $squareSize = rand(1, 3) * 10 + strlen($text);
        $canvas = imagecreatetruecolor($width, $height);

        for ($y=0; $y < $height/$squareSize; $y++) {
            for ($x=0; $x < $width/$squareSize; $x++) {
                $color = imagecolorallocate($canvas, rand(0, 255), rand(0, 255), rand(0, 255));
                imagefilledrectangle($canvas, $x * $squareSize, $y * $squareSize, ($x * $squareSize)+$squareSize, ($y*$squareSize) + $squareSize, $color);
            }
        }

        $font = 'fonts/OpenSans-Regular.ttf';
        $black = imagecolorallocate($canvas, 0, 0, 0);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        $font_size = 72;
        $boundingBox = $this->imagettfbboxextended($font_size + 3, 0, $font, $text);
        while ($boundingBox['width'] > $width || $boundingBox['height'] > $height) {
            $font_size--;
            $boundingBox = $this->imagettfbboxextended($font_size, 0, $font, $text);
        }

        $this->imagettfstroketext($canvas, $font_size, 0, $boundingBox['x'], $boundingBox['y'], $white, $black, $font, $text, 2);
        $this->image = $canvas;
    }

    private function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px)
    {
        for ($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++) {
            for ($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++) {
                imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
            }
        }
        return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
    }

    private function imagettfbboxextended($size, $angle, $fontfile, $text)
    {
        $bbox = imagettfbbox($size, $angle, $fontfile, $text);

        if ($bbox[0] >= -1) {
            $bbox['x'] = abs($bbox[0] + 1) * -1;
        } else {
            $bbox['x'] = abs($bbox[0] + 2);
        }

        $bbox['width'] = abs($bbox[2] - $bbox[0]);
        if ($bbox[0] < -1) {
            $bbox['width'] = abs($bbox[2]) + abs($bbox[0]) - 1;
        }

        $bbox['y'] = abs($bbox[5] + 1);

        $bbox['height'] = abs($bbox[7]) - abs($bbox[1]);
        if ($bbox[3] > 0) {
            $bbox['height'] = abs($bbox[7] - $bbox[1]) - 1;
        }

        return $bbox;
    }
}
