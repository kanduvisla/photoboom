<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 13-01-15
 * Time: 21:31
 * File: Color.php
 */

namespace Boom\Utils;

class Color
{
    /**
     * Return a random color
     *
     * @return string
     */
    public static function randomColor()
    {
        return '#' . $GLOBALS['colors'][rand(0, count($GLOBALS['colors']) - 1)];
    }

    /**
     * Return a darker color
     * 
     * @param $hexColor
     * @param $percent
     * @return string
     */
    public static function darken($hexColor, $percent)
    {
        return self::adjustBrightness($hexColor, round($percent * 2.55) * -1);
    }

    /**
     * Return a lighter color
     * 
     * @param $hexColor
     * @param $percent
     * @return string
     */
    public static function lighten($hexColor, $percent)
    {
        return self::adjustBrightness($hexColor, round($percent * 2.55));
    }

    /**
     * Adjust brightness of a color
     * 
     * @param $hexColor
     * @param $steps
     * @return string
     */
    public static function adjustBrightness($hexColor, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hexColor = str_replace('#', '', $hexColor);
        if (strlen($hexColor) == 3) {
            $hexColor = str_repeat(substr($hexColor, 0, 1), 2) . str_repeat(substr($hexColor, 1, 1), 2) . str_repeat(substr($hexColor, 2, 1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hexColor, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}