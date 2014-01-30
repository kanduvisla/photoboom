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

        // Dropshadow:


        // Strokes:
        $this->stroke();

        // Check for extra's:
        if(isset($this->attributes['extra']))
        {
            switch($this->attributes['extra'])
            {
                case 'tack' :
                    $tack = new Svg_Image('./clipart/tack1.svg',
                        array(
                            'width' => 30,
                            'height' => 30,
                            'x' => ($this->attributes['width'] / 2) - 15,
                            'y' => -15
                        )
                    );
                    $this->addElement($tack);
                    break;
            }
        }
    }
}