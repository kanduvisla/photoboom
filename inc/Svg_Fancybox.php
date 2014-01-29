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
        parent::__construct($filename, $attributes);

        // Temp solution:
        if(!isset($attributes['border-radius'])) { $attributes['border-radius'] = 0; }

        // Rotation:
        if(isset($attributes['rotation']))
        {
            $rotateStr = 'rotate(' . $attributes['rotation'] .' ' . round($attributes['width'] / 2) .
                ' ' . round($attributes['height'] / 2) . ')';
            $svgAttributes = $this->svg->attributes();
            if(isset($svgAttributes['transform']))
            {
                $this->svg['transform'] .= ' ' . $rotateStr;
            } else {
                $this->svg->addAttribute('transform', $rotateStr);
            }
        }

        // Strokes:
        if(isset($attributes['stroke']) && is_array($attributes['stroke']))
        {
            foreach($attributes['stroke'] as $stroke)
            {
                if(is_array($stroke))
                {
                    if(!isset($stroke['offset'])) { $stroke['offset'] = 0; }
                    $strokeAttributes = array(
                        'width'     => $attributes['width'] + ($stroke['offset'] * 2),
                        'height'    => $attributes['height'] + ($stroke['offset'] * 2),
                        'fill'      => 'none',
                        'rx'        => $attributes['border-radius'],
                        'ry'        => $attributes['border-radius'],
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