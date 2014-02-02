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

                break;
            case 'dots' :

                break;
        }
    }
}
 
