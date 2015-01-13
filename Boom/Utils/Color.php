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
        $hexColor = self::normalize($hexColor);

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

    /**
     * Mix 2 colors
     * 
     * @param $color1
     * @param $color2
     * @return string
     */
    public static function mix($color1, $color2)
    {
        $color1 = self::normalize($color1);
        $color2 = self::normalize($color2);
        
        $c1_p1 = hexdec(substr($color1, 0, 2));
        $c1_p2 = hexdec(substr($color1, 2, 2));
        $c1_p3 = hexdec(substr($color1, 4, 2));

        $c2_p1 = hexdec(substr($color2, 0, 2));
        $c2_p2 = hexdec(substr($color2, 2, 2));
        $c2_p3 = hexdec(substr($color2, 4, 2));

        $m_p1 = sprintf('%02x', (round(($c1_p1 + $c2_p1) / 2)));
        $m_p2 = sprintf('%02x', (round(($c1_p2 + $c2_p2) / 2)));
        $m_p3 = sprintf('%02x', (round(($c1_p3 + $c2_p3) / 2)));

        return '#' . $m_p1 . $m_p2 . $m_p3;
    }

    /**
     * Normalize into a six character long hex string
     * 
     * @param $hexColor
     * @return string
     */
    public static function normalize($hexColor)
    {
        $hexColor = str_replace('#', '', $hexColor);
        if (strlen($hexColor) == 3) {
            $hexColor = str_repeat(substr($hexColor, 0, 1), 2) . str_repeat(substr($hexColor, 1, 1), 2) . str_repeat(substr($hexColor, 2, 1), 2);
        }
        return $hexColor;
    }

    /**
     * Get the brightness of a color
     * 
     * @param $hexColor
     * @return float
     */
    public static function getBrightness($hexColor)
    {
        $hexColor = self::normalize($hexColor);
        //break up the color in its RGB components
        $r = hexdec(substr($hexColor,0,2));
        $g = hexdec(substr($hexColor,2,2));
        $b = hexdec(substr($hexColor,4,2));
        
        return ($r + $g + $b) / (256 * 3);
    }
}