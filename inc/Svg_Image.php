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

        if($info != false)
        {
            $arr = array(
                'xlink:xlink:href' => $filename,
                'width' => $info[0],
                'height' => $info[1]
            );
        } else {
            $arr = array(
                'xlink:xlink:href' => $filename,
            );
        }

        // Merge attributes:
        $this->mergeAttributes(
            $arr,
            $attributes
        );

        // Construct the element:
        parent::__construct('image');
    }
}