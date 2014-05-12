<?php

require_once(dirname(__FILE__) . '/Svg_Imagebox.php');

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
        if(isset($this->attributes['dropshadow']) && $this->attributes['dropshadow'] == 1)
        {
            $this->svg['filter'] = 'url(#dropshadow)';
        }

        // Strokes:
        $this->stroke();

        // Check for extra's:
        if(isset($this->attributes['extra']))
        {
            switch($this->attributes['extra'])
            {
                case 'tack' :
                    $tack = new SimpleXMLElement('<use />');
                    $tack->addAttribute('xlink:xlink:href', '#tack' . rand(1, 3));
                    $tack->addAttribute('transform', 'translate(' . (($this->attributes['width'] / 2) - 15) . ', 0)');
                    $this->sxml_append($this->getSvgData(), $tack);
                    break;

                case 'tape' :
                    $x = rand(0, 10) > 5 ? -50 : $this->attributes['width'] - 100;
                    // Tape #1
                    $tape = new SimpleXMLElement('<use />');
                    $tape->addAttribute('xlink:xlink:href', '#tape' . rand(1, 4));
                    $tape->addAttribute('transform', 'translate(' . $x . ', -40) rotate(' . rand(-20, 20) . ')');
                    $this->sxml_append($this->getSvgData(), $tape);

                    $x = $x > 0 ? -50 : $this->attributes['width'] - 100;

                    // Tape #2
                    $tape = new SimpleXMLElement('<use />');
                    $tape->addAttribute('xlink:xlink:href', '#tape' . rand(1, 4));
                    $tape->addAttribute('transform', 'translate(' . ( $x ) . ', ' .
                            ($this->attributes['height'] - 50) . ') rotate(' . rand(-20, 20) . ')');
                    $this->sxml_append($this->getSvgData(), $tape);
                    break;
            }
        }
    }
}