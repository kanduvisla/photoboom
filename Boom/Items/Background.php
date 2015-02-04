<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 04-02-15
 * Time: 19:48
 * File: Background.php
 */

namespace Boom\Items;

class Background extends Base implements Base_Interface
{
    /**
     * Initialization
     */
    protected function init()
    {
        $this->code = 'background';
        $this->name = 'Background';
    }
    
    /**
     * Get the options available
     * @param array $defaults
     * @return array|void
     */
    public function getOptions(array $defaults = array())
    {
        return array(
            'width' => array(
                'label' => 'Width',
                'name' => 'width',
                'type' => 'text',
                'default' => 300
            ),
            'height' => array(
                'label' => 'Height',
                'name' => 'height',
                'type' => 'text',
                'default' => 300
            ),
            'color1' => array(
                'label' => 'Color #1',
                'name' => 'color1',
                'type' => 'color',
                'default' => '#FFCCCC'
            ),
            'color2' => array(
                'label' => 'Color #2',
                'name' => 'color2',
                'type' => 'color',
                'default' => '#CCCCFF'
            ),
            'type' => array(
                'label' => 'Type',
                'name' => 'type',
                'type' => 'dropdown',
                'default' => 'dots',
                'values' => array(
                    'lines',
                    'lines45',
                    'dots'
                )
            )
        );
    }
    
    /**
     * Render SVG document 
     * @param array $options
     * @return bool|string
     */
    public function renderSvg(array $options = array())
    {
        $file = BOOM_ROOT . '/Boom/Items/' . $this->name . '/svg/' . $this->code . '.svg';
        if(file_exists($file))
        {
            $svgData = file_get_contents($file);
            $svgData = $this->parseOptionsOnSvg($svgData, $options);
            return $svgData;
        } else {
            return false;
        }
    }    
}