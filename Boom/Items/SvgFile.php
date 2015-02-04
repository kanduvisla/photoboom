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
        // Check for if-statements:
        preg_match_all('/\{\{if (.*)\}\}(.*)\{\{\/if\}\}/mUis', $svgData, $matches);
        if(count($matches[1]) > 0)
        {
            // We have matches!
            foreach($matches[1] as $index => $match)
            {
                $operation = explode('=', $match);
                if(isset($options[$operation[0]]) && $options[$operation[0]] == $operation[1])
                {
                    // Replace if-statement with emptyness:
                    $replacement = str_replace(array('{{if ' . $match . '}}', '{{/if}}'), '', $matches[0][$index]);
                    $svgData = str_replace($matches[0][$index], $replacement, $svgData);
                } else {
                    // Replace everything with emptyness:
                    $svgData = str_replace($matches[0][$index], '', $svgData);
                }
            }
        }
        return $svgData;
    }
}