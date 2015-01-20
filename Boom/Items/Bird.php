<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 12-01-15
 * Time: 19:54
 * File: Duck.php
 */

namespace Boom\Items;

use Boom\Utils\Color;

class Bird extends SvgFile
{
    /**
     * Initialization
     */
    protected function init()
    {
        $this->code = 'bird';
        $this->name = 'Bird';
    }

    /**
     * Get the options available
     * @param array $defaults
     * @return array|void
     */
    public function getOptions(array $defaults = array())
    {
        return array(
            array(
                'label' => 'Color bird',
                'name' => 'color-bird',
                'type' => 'color',
                'default' => '#E30613'
            ),
            array(
                'label' => 'Color eye',
                'name' => 'color-eye',
                'type' => 'color',
                'default' => '#662483'
            ),
            array(
                'label' => 'Accessories',
                'name' => 'accessories',
                'type' => 'dropdown',
                'default' => 'none',
                'values' => array(
                    'none',
                    'doctor',
                    'halo',
                    'black_pete'
                )
            )
        );
    }

    /**
     * Way of changing options prior before rendering the SVG
     * @param $options
     * @return array
     */
    protected function manipulateOptionsBeforeRendering(array $options = array())
    {
        $options['color-beak'] = Color::mix($options['color-bird'], $options['color-eye']);
        if(Color::getBrightness($options['color-bird']) < .5) {
            $options['color-wing'] = Color::lighten($options['color-bird'], 25);
        } else {
            $options['color-wing'] = Color::darken($options['color-bird'], 25);
        }
        return $options;
    }

}