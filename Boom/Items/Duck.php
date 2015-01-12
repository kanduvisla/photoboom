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
     * Render SVG document 
     */
    public function renderSvg()
    {
        $file = BOOM_ROOT . '/Boom/Items/' . $this->name . '/svg/duck.svg';
        if(file_exists($file))
        {
            $svgData = file_get_contents($file);
            return $svgData;
        } else {
            return false;
        }
    }
}