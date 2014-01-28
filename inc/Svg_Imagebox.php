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
        parent::__construct($this->id);
        // Get the image size:

        // Merge the settings:
        $attributes = array_merge(
            array(
                'border-radius' => 20
            ),
            $attributes
        );

        // Create the clip path:
        $clipPathAttributes = $this->merge_settings(
            array(
                'x' => 0,
                'y' => 0,
                'width' => 300,
                'height' => 300,
                'rx' => $attributes['border-radius'],
                'ry' => $attributes['border-radius']
            ),
            $attributes
        );
        $clipPath = new Svg_Element('clipPath', array('id' => 'clip-' . $this->id));
        $clipRect = new Svg_Element('rect', $clipPathAttributes);
        $this->sxml_append($clipPath->getSvgData(), $clipRect->getSvgData());
        $this->addElement($clipPath);

        // Create the image:
        $imageAttributes = $this->merge_settings(
            array(
                'x' => 0,
                'y' => 0,
                'clip-path' => 'url(#clip-' . $this->id . ')'
            ),
            $attributes
        );
        $image = new Svg_Image($filename, $imageAttributes);
        $this->addElement($image);
    }
}