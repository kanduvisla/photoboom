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

        // Merg settings:
        $attributes = $this->merge_settings(
            array(
                'size' => 20,
                'border-radius' => 20
            ),
            $attributes
        );

        // Set the settings for the outer border:
        $outerBorderAttributes = $this->merge_settings(
            array(
                'width' => $GLOBALS['page_width'],
                'height' => $GLOBALS['page_height'],
                'fill' => 'yellow',
                 'mask' => 'url(#border-mask)'
            ),
            $attributes
        );

        // Create the mask:
        $maskAttributes = array(
            'width' => $outerBorderAttributes['width'] - $attributes['size'] * 2,
            'height' => $outerBorderAttributes['height'] - $attributes['size'] * 2,
            'rx' => $attributes['border-radius'],
            'ry' => $attributes['border-radius'],
            'x' => $attributes['size'],
            'y' => $attributes['size'],
            'fill' => 'black'
        );
        $mask           = new Svg_Element('mask', array('id' => 'border-mask'));
        $maskForeground = new Svg_Element('rect', $maskAttributes);
        $maskBackground = new Svg_Element('rect',
            array(
                'width' => $outerBorderAttributes['width'],
                'height' => $outerBorderAttributes['height'],
                'fill' => 'white'
            )
        );
        $this->sxml_append($mask->getSvgData(), $maskBackground->getSvgData());
        $this->sxml_append($mask->getSvgData(), $maskForeground->getSvgData());
        $this->addElement($mask);

        // Create the border rectangle:
        $outerBorder = new Svg_Element('rect', $outerBorderAttributes);
        $this->addElement($outerBorder);

        // Create the stroke:
        $strokeAttributes = $this->merge_settings(
            array(
                'stroke' => 'black',
                'stroke-width' => 3,
            ),
            $attributes
        );
        $strokeAttributes['width'] = $maskAttributes['width'];
        $strokeAttributes['height'] = $maskAttributes['height'];
        $strokeAttributes['rx'] = $maskAttributes['rx'];
        $strokeAttributes['ry'] = $maskAttributes['ry'];
        $strokeAttributes['x'] = $maskAttributes['x'];
        $strokeAttributes['y'] = $maskAttributes['y'];
        $strokeAttributes['fill'] = 'transparent';
        $stroke = new Svg_Element('rect', $strokeAttributes);
        $this->addElement($stroke);
    }
}