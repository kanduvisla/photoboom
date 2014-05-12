<?php

class Svg_Textbox extends Svg_Group
{
    protected $id;

    public function __construct($text, $attributes = array())
    {
        $this->id = 'text-' . md5($text) . '-' . rand(10000, 99999);

        if(!isset($attributes['x'])) { $attributes['x'] = 0; }
        if(!isset($attributes['y'])) { $attributes['y'] = 0; }

        parent::__construct($this->id);

        // Merge the attributes:
        $this->mergeAttributes(
            array(
                'fill'          => 'white',
                'border-radius' => 0,
                'width'         => 300,
                'height'        => 200,
            ),
            $attributes
        );

        // Set the rotation:
        $transforms = array();
        if(isset($attributes['rotation']))
        {
            $transforms[] = 'rotate(' . $attributes['rotation'] .' ' . round($this->attributes['width'] / 2) .
                ' ' . round($this->attributes['height'] / 2) . ')';
        }
        if(!($attributes['x'] == 0 && $attributes['y'] == 0))
        {
            $transforms[] = 'translate(' . $attributes['x'] . ', ' . $attributes['y'].')';
        }

        // Merge the transforms:
        if(!empty($transforms))
        {
            $this->svg['transform'] = implode(' ', $transforms);
        }

        // Create the label:
        $labelRect = new Svg_Element('rect',
            array(
                'fill'      => $this->attributes['fill'],
                'rx'        => $this->attributes['border-radius'],
                'ry'        => $this->attributes['border-radius'],
                'width'     => $this->attributes['width'],
                'height'    => $this->attributes['height'],
            )
        );
        $this->addElement($labelRect);

        // Stroke:
        $this->stroke();

        // Create the text:
        $textElement = new Svg_Element('text',
            array(
                'x' => $this->attributes['width'] / 2,
                'y' => $this->attributes['height'] / 2,
                'text-anchor' => 'middle',
                'font-size' => $attributes['font-size'],
                'font-family' => $attributes['font-family']
            )
        );
        $textElement->svg[0] = $text;
        $this->addElement($textElement);
    }
}