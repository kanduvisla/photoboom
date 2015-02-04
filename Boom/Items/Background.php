<?php
/**
 * Created by PhpStorm.
 * User: Giel
 * Date: 04-02-15
 * Time: 19:48
 * File: Background.php
 */

namespace Boom\Items;

use Boom\Svg\Document;
use Boom\Svg\Element;

class Background extends Base implements Base_Interface
{
    /**
     * @var Document
     */
    protected $svg;
    
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
            ),
            'param1' => array(
                'label' => 'Parameter #1',
                'name' => 'param1',
                'type' => 'text',
                'default' => 10
            ),
            'param2' => array(
                'label' => 'Parameter #2',
                'name' => 'param2',
                'type' => 'text',
                'default' => 30
            ),
        );
    }
    
    /**
     * Render SVG document 
     * @param array $options
     * @return bool|string
     */
    public function renderSvg(array $options = array())
    {
        $options = array_merge($this->getDefaultOptions(), $options);
        $this->svg = new Document($options['width'], $options['height']);
        $this->createSvg($options);
        $this->svg->parse();
        return $this->svg->getSvgData()->asXML();
    }

    /**
     * Function that does all the magic
     * @param array $options
     */
    private function createSvg(array $options = array())
    {
        // Create a fill:
        $fill = new Element('rect',
            array(
                'width' => $options['width'],
                'height' => $options['height'],
                'stroke' => 'none',
                'fill' => $options['color1']
            )
        );
        $this->svg->addElement($fill);
        
        // Create a pattern:
        switch($options['type'])
        {
            case 'lines' :
                $i = 0;
                $c = count($options['colors']);
                if($this->attributes['xAxis'])
                {
                    for($x = 0; $x < $this->attributes['width']; $x+= ($this->attributes['size'] + $this->attributes['offset']))
                    {
                        $line = new Svg_Element('line',
                            array(
                                'x1' => $x,
                                'y1' => 0,
                                'x2' => $x,
                                'y2' => $this->attributes['height'],
                                'stroke-width' => $this->attributes['size'],
                                'stroke' => $this->attributes['colors'][$i / 1 % $c],
                                'stroke-opacity' => $this->attributes['opacity']
                            )
                        );
                        $this->addElement($line);
                        $i ++;
                    }
                }
                if($this->attributes['yAxis'])
                {
                    for($y = 0; $y < $this->attributes['height']; $y += ($this->attributes['size'] + $this->attributes['offset']))
                    {
                        $line = new Svg_Element('line',
                            array(
                                'x1' => 0,
                                'y1' => $y,
                                'x2' => $this->attributes['width'],
                                'y2' => $y,
                                'stroke-width' => $this->attributes['size'],
                                'stroke' => $this->attributes['colors'][$i / 1 % $c],
                                'stroke-opacity' => $this->attributes['opacity']
                            )
                        );
                        $this->addElement($line);
                        $i ++;
                    }
                }
                break;
            case 'lines45' :
                $i = 0;
                $c = count($this->attributes['colors']);
                // $this->attributes['offset'] *= 1; //($this->attributes['size'] / 5); // because of the 45 degree angle
                $this->attributes['offset'] *= 1.5;
                $this->attributes['offset'] += $this->attributes['size'] / 2.5;
                if($this->attributes['yAxis'])
                {
                    for($y=0; $y < $this->attributes['height'] + $this->attributes['width']; $y += ($this->attributes['size'] + $this->attributes['offset']))
                    {
                        // Y-Axis
                        $line = new Svg_Element('line',
                            array(
                                'x1' => 0,
                                'y1' => $y,
                                'x2' => $y,
                                'y2' => 0,
                                'stroke-width' => $this->attributes['size'],
                                'stroke' => $this->attributes['colors'][$i / 1 % $c],
                                'stroke-opacity' => $this->attributes['opacity']
                            )
                        );
                        $this->addElement($line);
                        $i ++;
                    }
                }
                if($this->attributes['xAxis'])
                {
                    for($y=$this->attributes['height']; $y > -$this->attributes['width']; $y -= ($this->attributes['size'] + $this->attributes['offset']))
                    {
                        // X-Axis
                        $line = new Svg_Element('line',
                            array(
                                'x1' => 0,
                                'y1' => $y,
                                'x2' => ($this->attributes['height'] - $y),
                                'y2' => $this->attributes['height'],
                                'stroke-width' => $this->attributes['size'],
                                'stroke' => $this->attributes['colors'][$i / 1 % $c],
                                'stroke-opacity' => $this->attributes['opacity']
                            )
                        );
                        $this->addElement($line);
                        $i ++;
                    }
                }
                break;
            case 'dots' :
                $i = 0;
                $j = 0;
                $options['size'] = $options['param1'];
                $options['offset'] = $options['param2'];
                for($y = 0; $y < $options['height']; $y+= (($options['size'] + $options['offset']) * 0.85 ))
                {
                    $j++;
                    for($x = 0; $x < $options['width'] + ($options['size'] + $options['offset']); $x+= ($options['size'] + $options['offset']))
                    {
                        $offset = ($options['offset'] + $options['size']) / 2;
                        $circle = new Element('circle',
                            array(
                                'cx' => $x - (($j%2) * $offset),
                                'cy' => $y,
                                'stroke' => 'none',
                                'fill' => $options['color2'],
                                // 'fill-opacity' => $options['opacity'],
                                'r' => $options['size'] / 2
                            )
                        );

                        $this->svg->addElement($circle);
                        $i ++;
                    }
                }
                break;
        }
        
    }
}