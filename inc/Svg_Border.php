<?php

/**
 * The SVG Border is a border around the whole page.
 * It has the following parameters:
 * - width
 * - height
 * - fill
 * - stroke
 * - stroke-style
 * - stroke-width
 * - border-radius
 * - size
 */
class Svg_Border extends Svg_Group
{
    /**
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        parent::__construct('border');

        // Merge attributes:
        $this->mergeAttributes(
            array(
                'size'          => 20,
                'border-radius' => 20,
                'width'         => $GLOBALS['page_width'],
                'height'        => $GLOBALS['page_height'],
                'fill'          => 'yellow',
                'stroke'        => 'black',
                'stroke-width'  => 3,
            ),
            $attributes
        );

        // Set the settings for the outer border:
        $outerBorderAttributes = array(
            'width'     => $this->attributes['width'],
            'height'    => $this->attributes['height'],
            'fill'      => $this->attributes['fill'],
            'mask'      => 'url(#border-mask)'
        );

        // Create the mask:
        $maskAttributes = array(
            'width'     => $outerBorderAttributes['width'] - $this->attributes['size'] * 2,
            'height'    => $outerBorderAttributes['height'] - $this->attributes['size'] * 2,
            'rx'        => $this->attributes['border-radius'],
            'ry'        => $this->attributes['border-radius'],
            'x'         => $this->attributes['size'],
            'y'         => $this->attributes['size'],
            'fill'      => 'black'
        );
        $mask           = new Svg_Element('mask', array('id' => 'border-mask'));
        $maskForeground = new Svg_Element('rect', $maskAttributes);
        $maskBackground = new Svg_Element('rect',
            array(
                'width'     => $outerBorderAttributes['width'],
                'height'    => $outerBorderAttributes['height'],
                'fill'      => 'white'
            )
        );
        $this->sxml_append($mask->getSvgData(), $maskBackground->getSvgData());
        $this->sxml_append($mask->getSvgData(), $maskForeground->getSvgData());
        $this->addElement($mask);

        // Create the border rectangle:
        $outerBorder = new Svg_Element('rect', $outerBorderAttributes);
        $this->addElement($outerBorder);

        // Create the stroke:
        $strokeAttributes = array(
            'stroke'        => $this->attributes['stroke'],
            'stroke-width'  => $this->attributes['stroke-width'],
        );
        $strokeAttributes['width']  = $maskAttributes['width'];
        $strokeAttributes['height'] = $maskAttributes['height'];
        $strokeAttributes['rx']     = $maskAttributes['rx'];
        $strokeAttributes['ry']     = $maskAttributes['ry'];
        $strokeAttributes['x']      = $maskAttributes['x'];
        $strokeAttributes['y']      = $maskAttributes['y'];
        $strokeAttributes['fill']   = 'none';
        $stroke = new Svg_Element('rect', $strokeAttributes);
        $this->addElement($stroke);
    }
}