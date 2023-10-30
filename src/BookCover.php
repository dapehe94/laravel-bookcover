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
    protected $style = '';

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
                'vendor/laravel-bookcover/fonts/primary/AvantGarde-Book.ttf',
                'vendor/laravel-bookcover/fonts/primary/Super-Salad.ttf',
                'vendor/laravel-bookcover/fonts/primary/Invisible-ExtraBold.otf',
                'vendor/laravel-bookcover/fonts/primary/ScorchedEarth.otf',
                'vendor/laravel-bookcover/fonts/primary/White-On-Black.ttf',
                'vendor/laravel-bookcover/fonts/primary/A-Love-of-Thunder.ttf',
                'vendor/laravel-bookcover/fonts/primary/MPonderosa.ttf',
                'vendor/laravel-bookcover/fonts/primary/Crackvetica.ttf',
                'vendor/laravel-bookcover/fonts/primary/Personal-Services.ttf',
                'vendor/laravel-bookcover/fonts/primary/COCOGOOSELETTERPRESS.ttf',                              
                'vendor/laravel-bookcover/fonts/primary/Cut-the-crap.ttf',         
                'vendor/laravel-bookcover/fonts/primary/Legend-Bold.otf',            
                'vendor/laravel-bookcover/fonts/primary/the-dark.ttf',                 
                'vendor/laravel-bookcover/fonts/primary/The-Blue-Alert.ttf',                       
                'vendor/laravel-bookcover/fonts/primary/Dharma-Punk-2.ttf',                             
                'vendor/laravel-bookcover/fonts/primary/Bouncy-Black.otf'
            );

    /**
     * @var string  Font name
     */
    protected $secondaryFont = array(
                'vendor/laravel-bookcover/fonts/secondary/Helvetica-Oblique.ttf',
                'vendor/laravel-bookcover/fonts/secondary/Hello-Valentina.ttf',
                'vendor/laravel-bookcover/fonts/secondary/coolvetica.otf',
                'vendor/laravel-bookcover/fonts/secondary/Nexa-Heavy.ttf',
                'vendor/laravel-bookcover/fonts/secondary/Louis-George-Cafe-Bold.ttf'
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
        $this->baseCover = public_path('vendor/laravel-bookcover') . '/basecover.png';
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
        $colors[0]='#ffe4e1';
        $colors[1]='#d8f8e1';
        $colors[2]='#fcb7af';
        $colors[3]='#b0f2c2';
        $colors[4]='#b0c2f2';
        $colors[5]='#fabfb7';
        $colors[6]='#fdf9c4';
        $colors[7]='#ffda9e';
        $colors[8]='#c5c6c8';
        $colors[9]='#b2e2f2';
        $colors[10]='#cce5ff';
        $colors[11]='#a3ffac';
        $colors[12]='#ffca99';
        $colors[13]='#eaffc2';
        $colors[14]='#ff8097';
        $colors[15]='#ff85d5';
        $colors[16]='#e79eff';
        $colors[17]='#b8e4ff';
        $colors[18]='#ff94a2';
        $colors[19]='#ffe180';
        $colors[20]='#F7CAC9';
        $colors[21]='#F8EDEB';
        $colors[22]='#FFE5B4';
        $colors[23]='#C5E0DC';
        $colors[24]='#A2C8CC';
        $colors[25]='#FFD1DC';
        $colors[26]='#FFB7C5';
        $colors[27]='#FFC0CB';
        $colors[28]='#F6A5C0';
        $colors[29]='#F9B7FF';
        $colors[30]='#FFCBA4';
        $colors[31]='#FDD7E4';
        $colors[32]='#FFB7C3';
        $colors[33]='#FFE4E1';
        $colors[34]='#FFDAB9';
        $colors[35]='#F0D8D9';
        $colors[36]='#E6E6FA';
        $colors[37]='#D3D3D3';
        $colors[38]='#FFC0CB';
        $colors[39]='#FFF0F5';
        $colors[40]='#AFEEEE';
        $colors[41]='#E0FFFF';
        $colors[42]='#B0E0E6';
        $colors[43]='#87CEFA';
        $colors[44]='#ADD8E6';
        $colors[45]='#FFB6C1';
        $colors[46]='#FFD700';
        $colors[47]='#98FB98';
        $colors[48]='#FFA07A';
        $colors[49]='#FFC0CB';
        $colors[50]='#98f6a9';
        $colors[51]='#9bd3ae';
        $colors[52]='#ffeebc';
        $colors[53]='#e2e3e7';
        $colors[54]='#4afde7';
        $colors[55]='#a0d995';
        $colors[56]='#a2edce';
        $colors[57]='#dcd9f8';
        $colors[58]='#c5d084';
        $colors[59]='#d2bead';
        $colors[60]='#b0f2c2';
        $colors[61]='#c7f6d4';
        $colors[62]='#ededaf';
        $colors[63]='#fcfcda';
        $colors[64]='#ffb6af';
        $colors[65]='#95b8f6';
        $colors[66]='#add5fa';
        $colors[67]='#f9d99a';
        $colors[68]='#f9a59a';
        $colors[69]='#fa5f49';
        $colors[70]='#84b6f4';
        $colors[71]='#a0d2f3';
        $colors[72]='#95fab9';
        $colors[73]='#f4fab4';
        $colors[74]='#f7cae4';
        $colors[75]='#95b8f6';
        $colors[76]='#c3f8ff';
        $colors[77]='#e1b1bc';
        $colors[78]='#9b9b9b';
        $colors[79]='#f15fff';
        $colors[80]='#bc98f3';
        $colors[81]='#d3bcf6';
        $colors[82]='#f79ae5';
        $colors[83]='#f47edd';
        $colors[84]='#f47e8e';
        $colors[85]='#bc98f3';
        $colors[86]='#d3bcf6';
        $colors[87]='#bae0f5';
        $colors[88]='#d1eaf9';
        $colors[89]='#d8f79a';
        $colors[90]='#fa34df';
        $colors[91]='#7ff9c7';
        $colors[92]='#b7fadf';
        $colors[93]='#b186f1';
        $colors[94]='#caacf9';
        $colors[95]='#ff6565';
        $colors[96]='#ff6565';
        $colors[97]='#d1052a';
        $colors[98]='#ff9c9c';
        $colors[99]='#ff9c9c';
        $colors[100]='#f45572';
        $colors[101]='#888a8a';
        $colors[102]='#bdbfbf';
        $colors[103]='#c3dff9';
        $colors[104]='#ff6961';
        $colors[105]='#77dd77';
        $colors[106]='#fdfd96';
        $colors[107]='#84b6f4';
        $colors[108]='#fdcae1';

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

    public function setStyle($style)
    {
        $this->style = $style;
        $this->dirty = true;

        return $this;
    }
    
    public function randomFile($dir) 
    {
                    
        $files = scandir($dir);            
        if (($key = array_search('.', $files)) !== false) {
            unset($files[$key]);
        }
            
        if (($key = array_search('..', $files)) !== false) {
            unset($files[$key]);
        }
              
        $file = array_rand($files);        
        return $dir . $files[$file];            
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

        switch ($this->style) {
            case 'image':
                $this->watermark = $this->randomFile( public_path('vendor/laravel-bookcover') .'/backgrounds/image/' );
                $watermark = new \Imagick($this->watermark);
                $consts = array('\imagick::COMPOSITE_COPYOPACITY','\imagick::COMPOSITE_ATOP','\imagick::COMPOSITE_COPY');
                $this->image->compositeImage($watermark, (int)$consts[rand(0,2)], 0, 0);
                break;
            case 'cover':
                $this->watermark = $this->randomFile( public_path('vendor/laravel-bookcover') .'/backgrounds/cover/' );
                $watermark = new \Imagick($this->watermark);
                $consts = array('\imagick::COMPOSITE_COPYOPACITY','\imagick::COMPOSITE_ATOP','\imagick::COMPOSITE_COPY');
                $this->image->compositeImage($watermark, (int)$consts[rand(0,2)], 0, 0);
                break;
            case 'texture':
                $this->watermark = $this->randomFile( public_path('vendor/laravel-bookcover') .'/backgrounds/texture/' );
                $watermark = new \Imagick($this->watermark);
                $consts = array('\imagick::COMPOSITE_COPYOPACITY','\imagick::COMPOSITE_ATOP','\imagick::COMPOSITE_COPY');
                $this->image->compositeImage($watermark, (int)$consts[rand(0,2)], 0, 0);
                break;                            
        }

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
