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

        $this->mergeAttributes($attributes);

        // Rotation:
        if(isset($this->attributes['rotation']))
        {
            $rotateStr = 'rotate(' . $this->attributes['rotation'] .' ' . round($this->attributes['width'] / 2) .
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
    }
}