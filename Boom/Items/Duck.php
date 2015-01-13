<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 12-01-15
 * Time: 19:54
 * File: Duck.php
 */
 
namespace Boom\Items;

class Duck extends Base
{
    /**
     * Initialization
     */
    protected function init()
    {
        $this->code = 'duck';
        $this->name = 'Duck';
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
     * Render SVG document 
     * @param array $options
     * @return bool|string
     */
    public function renderSvg(array $options = array())
    {
        $file = BOOM_ROOT . '/Boom/Items/' . $this->name . '/svg/duck.svg';
        if(file_exists($file))
        {
            $svgData = file_get_contents($file);
            $svgData = $this->parseOptionsOnSvg($svgData, $options);
            return $svgData;
        } else {
            return false;
        }
    }

    /**
     * Parse options on SVG data
     * @param string $svgData
     * @param array $options
     * @return string
     */
    protected function parseOptionsOnSvg($svgData, array $options = array())
    {
        $options = array_merge($this->getDefaultOptions(), $options);
        foreach($options as $key => $value)
        {
            $svgData = str_replace('{{' . $key . '}}', $value, $svgData);
        }
        return $svgData;
    }

    /**
     * Get default options
     * @return array
     */
    protected function getDefaultOptions()
    {
        $options = array();
        foreach($this->getOptions() as $option)
        {
            $options[$option['name']] = $option['default'];
        }
        return $options;
    }
}