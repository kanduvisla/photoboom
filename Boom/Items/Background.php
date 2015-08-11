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
use Boom\Utils\Color;

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
                    'dots',
                    'stars'
                )
            ),
            'axis' => array(
                'label' => 'Axis',
                'name' => 'axis',
                'type' => 'dropdown',
                'default' => 'x',
                'values' => array(
                    'x', 'y'
                )
            ),
            'size' => array(
                'label' => 'Size',
                'name' => 'size',
                'type' => 'text',
                'default' => 10
            ),
            'offset' => array(
                'label' => 'Offset',
                'name' => 'offset',
                'type' => 'text',
                'default' => 30
            ),
            'opacity' => array(
                'label' => 'Opacity',
                'name' => 'opacity',
                'type' => 'text',
                'default' => 0.5
            ),
            'radial' => array(
                'label' => 'Radial',
                'name' => 'radial',
                'type' => 'checkbox',
                'default' => false
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
        if(!$options['radial'])
        {
            $fill = new Element('rect',
                array(
                    'width' => $options['width'],
                    'height' => $options['height'],
                    'stroke' => 'none',
                    'fill' => $options['color1']
                )
            );
            $this->svg->addElement($fill);
        } else {
            $gradient = new Element(
                'radialGradient',
                array(
                    'id' => 'grad1',
                    'cx' => '50%',
                    'cy' => '50%',
                    'r' => '50%',
                    'fx' => '50%',
                    'fy' => '50%'
                )
            );
            $gradient->addElement(
                new Element(
                    'stop',
                    array(
                        'offset' => '0%',
                        'style' => 'stop-color:' . $options['color1']
                    )
                )
            );
            $gradient->addElement(
                new Element(
                    'stop',
                    array(
                        'offset' => '100%',
                        'style' => 'stop-color:' . Color::darken($options['color1'], 10)
                    )
                )
            );
            $this->svg->addDefinition($gradient);
            $fill = new Element('rect',
                array(
                    'width' => $options['width'],
                    'height' => $options['height'],
                    'stroke' => 'none',
                    'fill' => 'url(#grad1)'
                )
            );
            $this->svg->addElement($fill);
        }
        // Create a pattern:
        switch($options['type'])
        {
            case 'lines' :
                $i = 0;
                if($options['axis'] == 'x')
                {
                    for($x = 0; $x < $options['width']; $x+= ($options['size'] + $options['offset']))
                    {
                        $line = new Element('line',
                            array(
                                'x1' => $x,
                                'y1' => 0,
                                'x2' => $x,
                                'y2' => $options['height'],
                                'stroke-width' => $options['size'],
                                'stroke' => $options['color2'],
                                'stroke-opacity' => $options['opacity']
                            )
                        );
                        $this->svg->addElement($line);
                        $i ++;
                    }
                }
                if($options['axis'] == 'y')
                {
                    for($y = 0; $y < $options['height']; $y += ($options['size'] + $options['offset']))
                    {
                        $line = new Element('line',
                            array(
                                'x1' => 0,
                                'y1' => $y,
                                'x2' => $options['width'],
                                'y2' => $y,
                                'stroke-width' => $options['size'],
                                'stroke' => $options['color2'],
                                'stroke-opacity' => $options['opacity']
                            )
                        );
                        $this->svg->addElement($line);
                        $i ++;
                    }
                }
                break;
            case 'lines45' :
                $i = 0;
                // $options['offset'] *= 1; //($options['size'] / 5); // because of the 45 degree angle
                $options['offset'] *= 1.5;
                $options['offset'] += $options['size'] / 2.5;
                if($options['axis'] == 'y')
                {
                    for($y=0; $y < $options['height'] + $options['width']; $y += ($options['size'] + $options['offset']))
                    {
                        // Y-Axis
                        $line = new Element('line',
                            array(
                                'x1' => 0,
                                'y1' => $y,
                                'x2' => $y,
                                'y2' => 0,
                                'stroke-width' => $options['size'],
                                'stroke' => $options['color2'],
                                'stroke-opacity' => $options['opacity']
                            )
                        );
                        $this->svg->addElement($line);
                        $i ++;
                    }
                }
                if($options['axis'] == 'x')
                {
                    for($y=$options['height']; $y > -$options['width']; $y -= ($options['size'] + $options['offset']))
                    {
                        // X-Axis
                        $line = new Element('line',
                            array(
                                'x1' => 0,
                                'y1' => $y,
                                'x2' => ($options['height'] - $y),
                                'y2' => $options['height'],
                                'stroke-width' => $options['size'],
                                'stroke' => $options['color2'],
                                'stroke-opacity' => $options['opacity']
                            )
                        );
                        $this->svg->addElement($line);
                        $i ++;
                    }
                }
                break;
            case 'dots':
            case 'stars':
                $i = 0;
                $j = 0;
                $pointCount = 5;
                $piPart = (pi() / $pointCount);
                $innerRadius = $options['size'] / 2;
                $outerRadius = $options['size'];
                for($y = 0; $y < $options['height']; $y+= (($options['size'] + $options['offset']) * 0.85 ))
                {
                    $j++;
                    for($x = 0; $x < $options['width'] + ($options['size'] + $options['offset']); $x+= ($options['size'] + $options['offset']))
                    {
                        $offset = ($options['offset'] + $options['size']) / 2;
                        if ($options['type'] === 'dots') {
                            $element = new Element('circle',
                                array(
                                    'cx' => $x - (($j%2) * $offset),
                                    'cy' => $y,
                                    'stroke' => 'none',
                                    'fill' => $options['color2'],
                                    'fill-opacity' => $options['opacity'],
                                    'r' => $options['size'] / 2
                                )
                            );
                        }
                        if ($options['type'] === 'stars') {
                            $starX = $x - (($j%2) * $offset);
                            $starY = $y;
                            $points = array();
                            // $piOffset = pi() / rand(1, 30);

                            $piOffset = pi();
                            for($i = 0; $i < $pointCount * 2; $i+=2)
                            {
                                $points[] = ($starX + (sin($piOffset + $piPart * $i) * $outerRadius)) . ',' .
                                    ($starY + (cos($piOffset + $piPart * $i)) * $outerRadius);
                                $points[] = ($starX + (sin($piOffset + $piPart * ($i + 1)) * $innerRadius)) . ',' .
                                    ($starY + (cos($piOffset + $piPart * ($i + 1))) * $innerRadius);
                            }
                            $element = new Element('polygon',
                                array(
                                    'cx' => $starX,
                                    'cy' => $starY,
                                    'stroke' => 'black',
                                    'fill' => 'none',
//                                    'fill' => $options['color2'],
//                                    'fill-opacity' => $options['opacity'],
                                    // 'r' => $options['size'] / 2
                                    'points' => implode(' ', $points)
                                )
                            );
                        }
                        $this->svg->addElement($element);
                        $i ++;
                    }
                }
                break;
        }
        
    }
}