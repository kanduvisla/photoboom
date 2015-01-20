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
                return self::renderColorField($option['label'], $option['name'], $value);
                break;
            case 'dropdown' :
                return self::renderDropdownField($option['label'], $option['name'], $value, $option['values']);
                break;
            default :
                return self::renderInputField($option['label'], $option['name'], $value);
                break;
        }
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
    
    /**
     * Render color field
     * @param $label
     * @param $name
     * @param $value
     * @return string
     */
    private static function renderColorField($label, $name, $value)
    {
        return sprintf('
            <fieldset>
                <label for="%2$s">%1$s</label>
                <input type="color" id="%2$s" name="option[%2$s]" value="%3$s"/>
            </fieldset>
        ', $label, $name, $value);
    }
    
    /**
     * Render input field
     * @param $label
     * @param $name
     * @param $value
     * @param array $values
     * @return string
     */
    private static function renderDropdownField($label, $name, $value, $values)
    {
        $options = '';
        foreach($values as $val)
        {
            $options .= sprintf('<option value="%1$s" %3$s>%2$s</option>', 
                $val, 
                ucfirst(str_replace('_', ' ', $val)),
                ($val == $value ? 'selected="selected"' : '')
            );
        }
        return sprintf('
            <fieldset>
                <label for="%2$s">%1$s</label>
                <select id="%2$s" name="option[%2$s]">
                    %3$s
                </select>
            </fieldset>
        ', $label, $name, $options);
    }
}