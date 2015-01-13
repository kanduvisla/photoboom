<?php

namespace Boom;

// Cannot be accessed directly:
if(!defined('BOOM_ROOT')) { die ('naughty boy...'); }

spl_autoload_register(function ($class) {    
    $filename = BOOM_ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if(file_exists($filename)) {
        require_once($filename);
    }
});

// Requirements:
// require_once(BOOM_ROOT . '/Boom/Items/Base.php');

/**
 * PhotoBoom specific functions
 */
class Boom
{
    /**
     * Import SVG Document as definition
     *
     * @param Svg_Document $svg
     * @param array|string $files
     */
    public static function importDefinitions(&$svg, $files)
    {
        if(!is_array($files)) { $files = array($files); }
        foreach($files as $svgFile)
        {
            $svg->importSvgAsDefinition($svgFile, str_replace('.svg', '', basename($svgFile)));
        }
    }

    /**
     * Helper function to sort array
     *
     * @param $a
     * @param $b
     * @return bool
     */
    public static function sortByOrientation($a, $b)
    {
        return $a['orientation'] < $b['orientation'];
    }

    /**
     * Create a layout according to an image array
     *
     * @param $positions array
     * @param $svg Svg_Element
     * @param $clipArtNr int
     */
    public static function magicLayout($positions, &$svg, $clipArtNr = 0)
    {
        for($i = 0; $i < count($positions); $i ++)
        {
            // Each position has an X and an Y, defining their center in percent.
            // The pictures are put on the page is big as possible with the following conditions:
            // - Their outer boundaries may never exceed the pages' margin.
            // - The photos must have at least this padding between them.

            $currentPosition    = $positions[$i];
            $imgInfo            = getimagesize($currentPosition['file']);
            $imgRatio           = $imgInfo[0] / $imgInfo[1];
            $x                  = $currentPosition['x'] * $GLOBALS['page_width'];
            $y                  = $currentPosition['y'] * $GLOBALS['page_height'];
            // Calculate the max width and height, or override them if a scale parameter is set:
            if(!isset($currentPosition['scale']))
            {
                $maxWidth  = $GLOBALS['page_width'] * (($currentPosition['x'] * 2) - (max(0, $currentPosition['x'] - 0.5) * 4)) - ($GLOBALS['margin'] * 2);
                $maxHeight = $GLOBALS['page_height'] * (($currentPosition['y'] * 2) - (max(0, $currentPosition['y'] - 0.5) * 4)) - ($GLOBALS['margin'] * 2);
            } else {
                $maxWidth  = ($GLOBALS['page_width'] * $currentPosition['scale'] ) - ($GLOBALS['margin'] * 2);
                $maxHeight = ($GLOBALS['page_height'] * $currentPosition['scale'] ) - ($GLOBALS['margin'] * 2);
            }
            // Set image size:
            if($imgRatio < 1)
            {
                // Landscape
                $imgWidth = $maxWidth;
                $imgHeight = $maxWidth * $GLOBALS['ratio'];
                if($imgHeight > $maxHeight) {
                    $imgWidth = $imgWidth * ($maxHeight / $imgHeight);
                    $imgHeight = $maxHeight;
                }
            } else {
                // Portrait
                $imgHeight = $maxHeight;
                $imgWidth = $maxHeight * $GLOBALS['ratio'];
                if($imgWidth > $maxWidth) {
                    $imgHeight = $imgHeight * ($maxWidth / $imgWidth);
                    $imgWidth  = $maxWidth;
                }
            }

            // Debug:
            if(isset($GLOBALS['args']['debug']))
            {
                $rect = new Svg_Element('rect', array(
                    'fill' => 'none',
                    'stroke' => 'blue',
                    'x' => $x - $maxWidth / 2,
                    'y' => $y - $maxHeight / 2,
                    'width' => $maxWidth,
                    'height' => $maxHeight
                ));
                $svg->addElement($rect);

                $rect = new Svg_Element('rect', array(
                    'fill' => 'none',
                    'stroke' => 'red',
                    'x' => $x - $imgWidth / 2,
                    'y' => $y - $imgHeight / 2,
                    'width' => $imgWidth,
                    'height' => $imgHeight
                ));
                $svg->addElement($rect);
                // End debug
            }

            // Add photo:
            $extra = array(
                0 => false,
                1 => 'tack',
                2 => 'tape'
            );

            $image = new Svg_Fancybox($currentPosition['file'],
                array(
                    'x' => $x - $imgWidth / 2,
                    'y' => $y - $imgHeight / 2,
                    'width' => $imgWidth,
                    'height' => $imgHeight,
                    'border-radius' => 20,
                    'stroke' => array(
                        array(
                            'width' => 3, 'dasharray' => '5, 5', 'color' => Boom::randomColor(), 'linecap' => 'round', 'offset' => 5
                        ),
                        array(
                            'width' => 3, 'dasharray' => '5, 5', 'color' => Boom::randomColor(), 'linecap' => 'round', 'offset' => -5
                        )
                    ),
                    'rotation' => rand(-10, 10),
                    'extra' => $extra[$clipArtNr]
                )
            );
            $svg->addElement($image);
        }
    }

    /**
     * Return an array with all available items
     * 
     * @return array
     */
    public static function getItems()
    {
        $items = glob(BOOM_ROOT . '/Boom/Items/*', GLOB_ONLYDIR);
        $arr   = array();
        foreach($items as $item) {
            $className = '\\Boom\\Items\\' . basename($item);
            $class = new $className();
            /* @var $class \Boom\Items\Base; */
            $arr[$class->getCode()] = $class;
        }
        return $arr;
    }

    /**
     * Return a single item
     * @param String $code
     * @return \Boom\Items\Base;
     */
    public static function getItemByCode($code)
    {
        $className = '\\Boom\\Items\\' . ucfirst($code);
        if(class_exists($className)) {
            return new $className();
        } else {
            return false;
        }
    }

    /**
     * Get options from the request parameter
     * @return array
     */
    public static function getRequestOptions()
    {
        return isset($_GET['option']) ? $_GET['option'] : array();
    }
}