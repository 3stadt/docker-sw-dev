<?php

class ImageConfig
{
    /** @var  int */
    private $imageWidth;
    /** @var  int */
    private $imageHeight;
    /** @var  int */
    private $maxLineWidth;
    /** @var  int */
    private $colorDeviation;
    /** @var  string */
    private $text;

    /**
     * @return int
     */
    public function getImageWidth()
    {
        if (!is_int($this->imageWidth)) {
            return 640;
        }
        return $this->imageWidth;
    }

    /**
     * @param int $imageWidth
     */
    public function setImageWidth($imageWidth)
    {
        $this->imageWidth = $imageWidth;
    }

    /**
     * @return int
     */
    public function getImageHeight()
    {
        if (!is_int($this->imageHeight)) {
            return 480;
        }
        return $this->imageHeight;
    }

    /**
     * @param int $imageHeight
     */
    public function setImageHeight($imageHeight)
    {
        $this->imageHeight = $imageHeight;
    }

    /**
     * @return int
     */
    public function getMaxLineWidth()
    {
        if (!is_int($this->maxLineWidth)) {
            return 15;
        }
        return $this->maxLineWidth;
    }

    /**
     * @param int $maxLineWidth
     */
    public function setMaxLineWidth($maxLineWidth)
    {
        $this->maxLineWidth = $maxLineWidth;
    }

    /**
     * @return int
     */
    public function getColorDeviation()
    {
        if (!is_int($this->colorDeviation)) {
            return 20;
        }

        return $this->colorDeviation;
    }

    /**
     * @param int $colorDeviation
     */
    public function setColorDeviation($colorDeviation)
    {
        $this->colorDeviation = $colorDeviation;
    }

    /**
     * @return float
     */
    public function getSeed()
    {
        return (double)microtime() * 1000000;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text ?: '';
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = (string)$text;
    }
}
