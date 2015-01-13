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
        // Check if there is an option set in the request:
        $requestOptions = Boom::getRequestOptions();
        $value = isset($requestOptions[$option['name']]) ? $requestOptions[$option['name']] : $option['default'];
        switch($option['type'])
        {
            case 'color' :
                return self::renderInputField($option['label'], $option['name'], $value);
                break;
        }
        return '';
    }

    /**
     * Render input field
     * @param $label
     * @param $name
     * @param $value
     * @return string
     */
    private static function renderInputField($label, $name, $value)
    {
        return sprintf('
            <fieldset>
                <label for="%2$s">%1$s</label>
                <input type="text" id="%2$s" name="option[%2$s]" value="%3$s"/>
            </fieldset>
        ', $label, $name, $value);
    }
}