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

class Bee extends SvgFile
{
    /**
     * Initialization
     */
    protected function init()
    {
        $this->code = 'bee';
        $this->name = 'Bee';
    }

    /**
     * Get the options available
     * @param array $defaults
     * @return array|void
     */
    public function getOptions(array $defaults = array())
    {
        return array(
            'color' => array(
                'label' => 'Color',
                'name' => 'color',
                'type' => 'color',
                'default' => '#F9B233'
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
        $options['dark-color'] = Color::darken($options['color'], 10);
        return $options;
    }
    
}