<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 13-01-15
 * Time: 21:26
 * File: SvgFile.php
 */

namespace Boom\Items;

abstract class SvgFile extends Base
{
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
    
    /**
     * Parse options on SVG data
     * @param string $svgData
     * @param array $options
     * @return string
     */
    protected function parseOptionsOnSvg($svgData, array $options = array())
    {
        $options = array_merge($this->getDefaultOptions(), $options);
        $options = $this->manipulateOptionsBeforeRendering($options);
        foreach($options as $key => $value)
        {
            $svgData = str_replace('{{' . $key . '}}', $value, $svgData);
        }
        return $svgData;
    }

    /**
     * Way of changing options prior before rendering the SVG
     * @param $options
     * @return array
     */
    protected function manipulateOptionsBeforeRendering(array $options = array())
    {
        // Stub
        return $options;
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