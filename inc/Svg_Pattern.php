<?php

class Svg_Pattern extends Svg_Group
{
    private $id;

    public function __construct($attributes = array())
    {
        $this->id = 'pattern-' . md5(rand(0, 1000000)) . '-' . rand(10000, 99999);
        parent::__construct($this->id, $attributes);

        // Merge the attributes:
        $this->mergeAttributes(
            array(
                'width'     => $GLOBALS['page_width'],
                'height'    => $GLOBALS['page_height'],
                'colors'    => array('blue'),
                'type'      => 'lines', // Can be 'lines', 'lines45' or 'dots'
                'fill'      => 'none',
                'size'      => 10,
                'offset'    => 10,
                'opacity'   => 1,
                'rotation'  => 0,
                'xAxis'     => true,
                'yAxis'     => true
            ),
            $attributes
        );

        // Create a fill:
        $fill = new Svg_Element('rect',
            array(
                'width' => $this->attributes['width'],
                'height' => $this->attributes['height'],
                'stroke' => 'none',
                'fill' => $this->attributes['fill']
            )
        );
        $this->addElement($fill);

        // Create a pattern:
        switch($this->attributes['type'])
        {
            case 'lines' :
                $i = 0;
                $c = count($this->attributes['colors']);
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
                $c = count($this->attributes['colors']);
                for($y = 0; $y < $this->attributes['height']; $y+= (($this->attributes['size'] + $this->attributes['offset']) * 0.85 ))
                {
                    $j++;
                    for($x = 0; $x < $this->attributes['width'] + ($this->attributes['size'] + $this->attributes['offset']); $x+= ($this->attributes['size'] + $this->attributes['offset']))
                    {
                        $offset = ($this->attributes['offset'] + $this->attributes['size']) / 2;
                        $circle = new Svg_Element('circle',
                            array(
                                'cx' => $x - (($j%2) * $offset),
                                'cy' => $y,
                                'stroke' => 'none',
                                'fill' => $this->attributes['colors'][$i / 1 % $c],
                                'fill-opacity' => $this->attributes['opacity'],
                                'r' => $this->attributes['size'] / 2
                            )
                        );

                        $this->addElement($circle);
                        $i ++;
                    }
                }
                break;
        }
    }
}
 
