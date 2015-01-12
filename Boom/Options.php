<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 12-01-15
 * Time: 20:27
 * File: Options.php
 */

namespace Boom;

class Options
{
    /**
     * Render an option controller
     * @param $option
     * @return String
     */
    public static function renderOptionController($option)
    {
        return self::renderInputField($option['type'], $option['name'], $option['default']);
    }

    /**
     * Render input field
     * @param $type
     * @param $name
     * @param $default
     * @return string
     */
    private static function renderInputField($type, $name, $default)
    {
        return sprintf('
            <fieldset>
                <label for="%2$s">%1$s</label>
                <input type="text" id="%2$s"/>
            </fieldset>   
        ');
    }
}