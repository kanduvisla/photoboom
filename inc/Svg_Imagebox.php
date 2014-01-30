<?php

/**
 * The SVG Image box creates a clipped box containing an image. Attributes are:
 * - width
 * - height
 * - border-radius
 * - x
 * - y
 */
class Svg_Imagebox extends Svg_Group
{
    private $id;

    /**
     * Create a new imagebox
     *
     * @param string $filename
     * @param array $attributes
     */
    public function __construct($filename, $attributes = array())
    {
        $this->id = 'image-'.md5($filename).'-'.rand(10000, 99999);

        if(!isset($attributes['x'])) { $attributes['x'] = 0; }
        if(!isset($attributes['y'])) { $attributes['y'] = 0; }

        // Merge the attributes:
        $this->mergeAttributes(
            array(
                'transform' => 'translate(' . $attributes['x'] . ', ' . $attributes['y'].')'
            ),
            $attributes
        );

        // Construct the group element:
        parent::__construct($this->id);

        // Merge the rest of the settings:
        $this->mergeAttributes(
            array(
                'border-radius' => 0,
                'x'             => 0,
                'y'             => 0,
                'width'         => 300,
                'height'        => 300
            ),
            $attributes
        );

        // Get the image size:
        $info = getimagesize($filename);

        // Crop it on it's longest side:
        if($info[0] / $info[1] > $this->attributes['width'] / $this->attributes['height'])
        {
            // Height is leading
            $newHeight = $this->attributes['height'];
            $newWidth  = $info[0] * $this->attributes['height'] / $info[1];
        } else {
            // Width is leading
            $newHeight = $info[1] * $this->attributes['width'] / $info[0];
            $newWidth  = $this->attributes['width'];
        }

        // Create the clip path:
        $clipPathAttributes = array(
            'width' => $this->attributes['width'],
            'height' => $this->attributes['height'],
            'rx' => $this->attributes['border-radius'],
            'ry' => $this->attributes['border-radius'],
            'transform' => 'translate(' . -round(($this->attributes['width'] - $newWidth) / 2) .
                ', ' . -round(($this->attributes['height'] - $newHeight) / 2) . ')'
        );
        $clipPath = new Svg_Element('clipPath', array('id' => 'clip-' . $this->id));
        $clipRect = new Svg_Element('rect', $clipPathAttributes);
        $this->sxml_append($clipPath->getSvgData(), $clipRect->getSvgData());
        $this->addElement($clipPath);

        // Create the image:
        $imageAttributes = array(
            'clip-path' => 'url(#clip-' . $this->id . ')',
            'width' => ceil($newWidth),
            'height' => ceil($newHeight),
            'transform' => 'translate(' . round(($this->attributes['width'] - $newWidth) / 2) .
                ', ' . round(($this->attributes['height'] - $newHeight) / 2) . ')'
        );
        $image = new Svg_Image($filename, $imageAttributes);
        $this->addElement($image);
    }
}