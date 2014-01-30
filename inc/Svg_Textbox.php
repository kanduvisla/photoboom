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
        if(isset($this->attributes['stroke']) !== false && is_array($this->attributes['stroke']))
        {
            foreach($this->attributes['stroke'] as $stroke)
            {
                if(is_array($stroke))
                {
                    if(!isset($stroke['offset'])) { $stroke['offset'] = 0; }
                    $strokeAttributes = array(
                        'width'     => $this->attributes['width'] + ($stroke['offset'] * 2),
                        'height'    => $this->attributes['height'] + ($stroke['offset'] * 2),
                        'fill'      => 'none',
                        'rx'        => $this->attributes['border-radius'],
                        'ry'        => $this->attributes['border-radius'],
                        'x'         => -$stroke['offset'],
                        'y'         => -$stroke['offset']
                    );
                    if(isset($stroke['color']))     { $strokeAttributes['stroke'] = $stroke['color']; }
                    if(isset($stroke['width']))     { $strokeAttributes['stroke-width'] = $stroke['width']; }
                    if(isset($stroke['dasharray'])) { $strokeAttributes['stroke-dasharray'] = $stroke['dasharray']; }
                    if(isset($stroke['linecap']))   { $strokeAttributes['stroke-linecap'] = $stroke['linecap']; }

                    $strokeRect = new Svg_Element('rect', $strokeAttributes);
                    $this->addElement($strokeRect);
                }
            }
        }

        // Create the text:
        $textElement = new Svg_Element('text',
            array(
                'x' => $this->attributes['width'] / 2,
                'y' => $this->attributes['height'] / 2,
                'text-anchor' => 'middle'
            )
        );
        $textElement->svg[0] = '(placeholder)';
        $this->addElement($textElement);
    }
}