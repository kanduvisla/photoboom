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
    public function __construct($filename, $attributes)
    {
        $this->id = 'image-'.md5($filename);

        // Merge the settings:
        $attributes = array_merge(
            array(
                'border-radius' => 20,
                'x' => 0,
                'y' => 0,
                'width' => 300,
                'height' => 300
            ),
            $attributes
        );

        parent::__construct($this->id,
            array(
                'transform' => 'translate(' . $attributes['x'] . ', ' . $attributes['y'].')'
            )
        );

        // Create the clip path:
        $clipPathAttributes = $this->merge_settings(
            array(
                'width' => $attributes['width'],
                'height' => $attributes['height'],
                'rx' => $attributes['border-radius'],
                'ry' => $attributes['border-radius']
            ),
            $attributes
        );
        $clipPath = new Svg_Element('clipPath', array('id' => 'clip-' . $this->id));
        $clipRect = new Svg_Element('rect', $clipPathAttributes);
        $this->sxml_append($clipPath->getSvgData(), $clipRect->getSvgData());
        $this->addElement($clipPath);

        // Get the image size:
        $info = getimagesize($filename);

        // Crop it on it's longest side:
        if($info[0] / $info[1] > $attributes['width'] / $attributes['height'])
        {
            // Height is leading
            $newHeight = $attributes['height'];
            $newWidth  = $info[0] * $attributes['height'] / $info[1];
        } else {
            // Width is leading
            $newHeight = $info[1] * $attributes['width'] / $info[0];
            $newWidth  = $attributes['width'];
        }

        // Create the image:
        $imageAttributes = array(
            'clip-path' => 'url(#clip-' . $this->id . ')',
            'width' => ceil($newWidth),
            'height' => ceil($newHeight),
            'x' => round(($attributes['width'] - $newWidth) / 2),
            'y' => round(($attributes['height'] - $newHeight) / 2)
        );
        $image = new Svg_Image($filename, $imageAttributes);
        $this->addElement($image);
    }
}