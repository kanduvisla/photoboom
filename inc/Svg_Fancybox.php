<?php

/*
 * A fancy extension on the default imagebox. Parameters:
 * - rotation
 * - stroke[
 *   - offset
 *   - width
 *   - dasharray
 *   - linecap
 *   - color
 * ]
 */

class Svg_Fancybox extends Svg_Imagebox
{
    public function __construct($filename, $attributes = array())
    {
        // Pick out the stroke:
        if(isset($attributes['stroke'])) {
            $strokeArr = $attributes['stroke'];
            unset($attributes['stroke']);
        } else {
            $strokeArr = false;
        }

        // Pick out the rotation
        if(isset($attributes['rotation'])) {
            $rotation = $attributes['rotation'];
            unset($attributes['rotation']);
        } else {
            $rotation = false;
        }

        parent::__construct($filename, $attributes);

        // Rotation:
        if($rotation !== false)
        {
            $rotateStr = 'rotate(' . $rotation .' ' . round($this->attributes['width'] / 2) .
                ' ' . round($this->attributes['height'] / 2) . ')';
            $svgAttributes = $this->svg->attributes();
            if(isset($svgAttributes['transform']))
            {
                $this->svg['transform'] .= ' ' . $rotateStr;
            } else {
                $this->svg->addAttribute('transform', $rotateStr);
            }
        }

        // Strokes:
        if($strokeArr !== false && is_array($strokeArr))
        {
            foreach($strokeArr as $stroke)
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
    }
}