<?php

class Svg_Image extends Svg_Element
{
    /**
     * Create image SVG Element
     *
     * @param string $filename
     * @param array $attributes
     */
    public function __construct($filename, $attributes = array())
    {
        $info = getimagesize($filename);

        // Construct the element:
        $attributes = array_merge(
            array(
                'xlink:xlink:href' => $filename,
                'width' => $info[0].'px',
                'height' => $info[1].'px'
            ),
            $attributes
        );
        // Append suffix if needed:
        if(is_int($attributes['width'])) { $attributes['width'] .= 'px'; }
        if(is_int($attributes['height'])) { $attributes['height'] .= 'px'; }

        parent::__construct('image', $attributes);
    }
}