<?php

namespace Dapehe94\LaravelBookcover;

class BookCover
{
    /**
     * @var bool  Whether the ImageMagick image must be re-generated
     */
    protected $dirty = true;

    /**
     * @var \Imagick  The current ImageMagick image object
     */
    protected $image;

    /**
     * @var int  Canvas width in pixels
     */
    protected $pageWidth;

    /**
     * @var int  Canvas height in pixels
     */
    protected $pageHeight;

    /**
     * @var FontMetrics
     */
    protected $fontMetrics;

    /*
    |--------------------------------------------------------------------------
    | Cover text
    |--------------------------------------------------------------------------
    */

    protected $creators = '';
    protected $title = '';
    protected $subtitle = '';
    protected $edition = '';
    protected $publisher = '';
    protected $datePublished = '';
    protected $watermark = '';

    /*
    |--------------------------------------------------------------------------
    | Design options
    |--------------------------------------------------------------------------
    */

    /**
     * @var \ImagickPixel  Background color
     */
    protected $backgroundColor;

    /**
     * @var \ImagickPixel  Text color
     */
    protected $textColor;

    /**
     * @var string  Font name
     */
    protected $primaryFont = array(
                'public/vendor/laravel-bookcover/fonts/primary/AvantGarde-Book.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/Super-Salad.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/Invisible-ExtraBold.otf',
                'public/vendor/laravel-bookcover/fonts/primary/ScorchedEarth.otf',
                'public/vendor/laravel-bookcover/fonts/primary/White-On-Black.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/A-Love-of-Thunder.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/PantonRustHeavy-GrSh.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/MPonderosa.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/Crackvetica.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/Personal-Services.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/COCOGOOSELETTERPRESS.ttf',                              
                'public/vendor/laravel-bookcover/fonts/primary/Leander.ttf',
                'public/vendor/laravel-bookcover/fonts/primary/Cut-the-crap.ttf',         
                'public/vendor/laravel-bookcover/fonts/primary/Legend-Bold.otf',            
                'public/vendor/laravel-bookcover/fonts/primary/the-dark.ttf',                 
                'public/vendor/laravel-bookcover/fonts/primary/KGColdCoffee.ttf', 
                'public/vendor/laravel-bookcover/fonts/primary/The-Blue-Alert.ttf',                       
                'public/vendor/laravel-bookcover/fonts/primary/Dharma-Punk-2.ttf',                             
                'public/vendor/laravel-bookcover/fonts/primary/Bouncy-Black.otf',         
                'public/vendor/laravel-bookcover/fonts/primary/BADABB.ttf',
            );

    /**
     * @var string  Font name
     */
    protected $secondaryFont = array(
                'public/vendor/laravel-bookcover/fonts/secondary/Helvetica-Oblique.ttf',
                'public/vendor/laravel-bookcover/fonts/secondary/Hello-Valentina.ttf',
                'public/vendor/laravel-bookcover/fonts/secondary/coolvetica.otf',
                'public/vendor/laravel-bookcover/fonts/secondary/Nexa-Heavy.ttf',
                'public/vendor/laravel-bookcover/fonts/secondary/Louis-George-Cafe-Bold.ttf'
            );

    /**
     * @var string  Base cover filename
     */
    protected $baseCover;


    /**
     * @var array Text positions
     */
    protected $text_positions = array(
            \Imagick::GRAVITY_NORTHWEST => 'NorthWest',
            \Imagick::GRAVITY_NORTH => 'North',
            \Imagick::GRAVITY_NORTHEAST => 'NorthEast',
            \Imagick::GRAVITY_WEST => 'West',
            \Imagick::GRAVITY_CENTER => 'Center',
            \Imagick::GRAVITY_SOUTHWEST => 'SouthWest',
            \Imagick::GRAVITY_SOUTH => 'South',
            \Imagick::GRAVITY_SOUTHEAST => 'SouthEast',
            \Imagick::GRAVITY_EAST => 'East'
        );


    public function __construct()
    {
        $this->fontMetrics = new FontMetrics();
        $this->baseCover = dirname(__FILE__) . '/../assets/autocover5.png';
        $colortext = rand(0,1) ? 'white' : 'black';
        $this->setTextColor($colortext);
        $this->setBackgroundColor('#c10001');
    }

    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }

    public function __set($key, $value)
    {
        $method = 'get' . ucfirst($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    public function setTextColor($color)
    {
        if (is_object($color) && $color instanceof \ImagickPixel) {
            $this->textColor = $color;
        } else {
            $this->textColor = new \ImagickPixel($color);
        }
        $this->dirty = true;

        return $this;
    }

    public function setBackgroundColor($color)
    {
        $this->backgroundColor = new \ImagickPixel($color);
        $this->dirty = true;

        return $this;
    }

    public function randomizeBackgroundColor()
    {
        $colors[0] = '#c10001';
        $colors[1] = '#fc331c';
        $colors[2] = '#ff8f00';
        $colors[3] = '#ffd221';
        $colors[4] = '#edff5b';
        $colors[5] = '#c7e000';
        $colors[6] = '#52e000';
        $colors[7] = '#00b22c';
        $colors[8] = '#1a9391';
        $colors[9] = '#00c4da';
        $colors[10] = '#4643bb';
        $colors[11] = '#610c8c';
        $colors[12] = '#000000';

        shuffle($colors);
        $this->setBackgroundColor($colors[0]);

        $this->dirty = true;

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->dirty = true;

        return $this;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
        $this->dirty = true;

        return $this;
    }

    public function setEdition($edition)
    {
        $this->edition = $edition;
        $this->dirty = true;

        return $this;
    }

    public function setCreators($creators)
    {
        $this->creators = $creators;
        $this->dirty = true;

        return $this;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
        $this->dirty = true;

        return $this;
    }

    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;
        $this->dirty = true;

        return $this;
    }

    public function setWatermark($watermark)
    {
        $this->watermark = $watermark;
        $this->dirty = true;

        return $this;
    }

    public function getImage($maxWidth=0)
    {
        if ($this->dirty) {
            $this->make();
        }
        $image = clone $this->image;
        if ($maxWidth > 0) {
            $image->thumbnailImage($maxWidth, 0, false);
        }

        return $image;
    }

    public function getImageBlob($maxWidth=0)
    {
        if ($this->dirty) {
            $this->make();
        }

        return $this->getImage($maxWidth)->getImageBlob();
    }

    public function save($filename, $maxWidth=0)
    {
        $fp = fopen($filename, 'w');
        fwrite($fp, $this->getImageBlob($maxWidth));
        fclose($fp);

        return $this;
    }

    protected function getDraw($gravity = null)
    {
        $draw = new \ImagickDraw();
        $draw->setFillColor($this->textColor);
        $draw->setGravity($gravity ?: \Imagick::GRAVITY_NORTHWEST);
        
        $font = array_rand($this->primaryFont, 1);
        $draw->setFont($this->primaryFont[$font]);

        return $draw;
    }

    protected function drawTitle($text_position)
    {
        $text = rand(0,1) ? mb_strtoupper($this->title) : $this->title;
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw();
        list($fontSize, $text) = $this->fontMetrics->getFontDataForTitle($text, $this->pageWidth);
        $draw->setFontSize($fontSize);

        $north = array('NorthWest','North','NorthEast');
        $south = array('SouthWest','South','SouthEast');
        $west = array('NorthWest','West','SouthWest');
        $east = array('NorthEast','SouthEast','East');

        $draw->setGravity($text_position);

        $top = 0;
        $left = 0;
        if(in_array($this->text_positions[$text_position], $north)) $top = 50;
        if(in_array($this->text_positions[$text_position], $south)) $top = 50;
        if(in_array($this->text_positions[$text_position], $west)) $left = 30;
        if(in_array($this->text_positions[$text_position], $east)) $left = 30;

        $metrics = $this->image->queryFontMetrics($draw, $text);
        
        $this->image->annotateImage($draw, $left, $top, 0, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawSubtitle($top, $left, $right)
    {
        $text = mb_strtoupper($this->subtitle);
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw();
        list($fontSize, $text) = $this->fontMetrics->getFontDataForSubtitle($text, $this->pageWidth - $right - $left);
        $draw->setFontSize($fontSize);

        $this->image->annotateImage($draw, $left, $top, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawEdition($top, $right)
    {
        $text = mb_strtoupper($this->edition);
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw(\Imagick::GRAVITY_NORTHEAST);
        $draw->setFont($this->secondaryFont);
        $draw->setFontSize(20);

        $margin = 10;
        $this->image->annotateImage($draw, $right, $top + $margin, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawCreators($text_position)
    {
        $text = $this->creators;
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw();

        list($fontSize, $text) = $this->fontMetrics->getFontDataForCreators($text);

        $font = array_rand($this->secondaryFont, 1);
        $draw->setFont($this->secondaryFont[$font]);

        $draw->setFontSize($fontSize - 20);        
        
        $top = 50;
        $left = 0;

        switch ($this->text_positions[$text_position]) {
            case 'North':
                $author_position = \Imagick::GRAVITY_SOUTH;
                break;
            case 'South':
                $author_position = \Imagick::GRAVITY_NORTH;
                break;    
            case 'NorthWest':
                $author_position = \Imagick::GRAVITY_SOUTHWEST;
                $left = 30;
                break;          
            case 'NorthEast':
                $author_position = \Imagick::GRAVITY_SOUTHEAST;
                $left = 30;
                break;
            case 'West':
                $author_position = \Imagick::GRAVITY_SOUTHWEST;
                $left = 30;
                break;   
            case 'East':
                $author_position = \Imagick::GRAVITY_SOUTHEAST;
                $left = 30;
                break;    
            case 'SouthWest':
                $author_position = \Imagick::GRAVITY_NORTHWEST;
                $left = 30;
                break;   
            case 'SouthEast':
                $author_position = \Imagick::GRAVITY_NORTHEAST;
                $left = 30;
                break;       
            case 'Center':
                $author_position = \Imagick::GRAVITY_SOUTH;
                break;                                                                                                     
        }

        $draw->setGravity($author_position);

        $this->image->annotateImage($draw, $left, $top, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawPublisherDate($right, $bottom)
    {
        $text = $this->publisher . ' ' . $this->datePublished;
        if (empty($text)) {
            return 0;
        }

        $draw = new \ImagickDraw();
        $draw->setFillColor($this->textColor);
        $draw->setGravity(\Imagick::GRAVITY_SOUTHEAST);
        $draw->setFontSize(16);
        $metrics = $this->image->queryFontMetrics($draw, $text);
        $textheight = $metrics['textHeight'] - $metrics['descender'];

        $this->image->annotateImage($draw, $right, $bottom, 0, $text);

        return $textheight;
    }


    protected function make()
    {
        $left = 30;
        $right = 20;
        $top = 50;
        $bottom = 20;

        $background = new \Imagick($this->baseCover);
        
        list($width, $height) = array_values($background->getImageGeometry());
        $this->pageWidth = $width;
        $this->pageHeight = $height;

        $this->image = new \Imagick();
        $this->image->newImage($width, $height, $this->backgroundColor);
        $this->image->compositeImage($background, \imagick::COMPOSITE_OVER, 0, 0);

        if($this->watermark):
            $watermark = new \Imagick($this->watermark);
            $this->image->compositeImage($watermark, \imagick::COMPOSITE_COPYOPACITY, 0, 0);
        endif;

        $text_position = array_rand($this->text_positions, 1);

        $top += $this->drawTitle($text_position);
        $top += $this->drawSubtitle($top, $left, $right);
        $top += $this->drawEdition($top, $right);

        $this->drawCreators($text_position);
        $this->drawPublisherDate($right, $bottom);

        $this->image->setImageFormat('png');
        $this->dirty = false;
    }
}
