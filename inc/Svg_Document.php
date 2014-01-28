<?php

class Svg_Document extends Svg_Element
{
    private $defs;
    private $elements;

    /**
     * Create a new SVG file
     *
     * @param $width int
     * @param $height int
     */
    public function __construct($width, $height)
    {
        parent::__construct('svg',
            array(
                'xmlns' => 'http://www.w3.org/2000/svg',
                'xmlns:xmlns:xlink' => 'http://www.w3.org/1999/xlink',
                'version' => '2.0',
                'width' => $width,
                'height' => $height
            )
        );
        $this->defs = new SimpleXMLElement('<defs />');
        $this->elements = array();

        // For debugging purposes:
        $rect = new Svg_Element('rect',
            array(
                'width' => $width,
                'height' => $height,
                'fill' => '#cccccc'
            )
        );
        $this->addElement($rect);
        // End debugging purposes
    }

    /**
     * Add a SVG Element
     *
     * @param Svg_Element|SimpleXMLElement $element
     */
    public function addElement($element)
    {
        if($element instanceof SimpleXMLElement)
        {
            $this->elements[] = $element;
        } else {
            $this->elements[] = $element->getSvgData();
        }
    }

    /**
     * Create SVG File
     *
     * @param $fileName
     */
    public function parse($fileName)
    {
        // Add definitions first:
        $this->sxml_append($this->svg, $this->defs);
        // Then add all elements:
        foreach($this->elements as $element)
        {
            $this->sxml_append($this->svg, $element);
        }
        // Parse the SVG data:
        $this->svg->asXML($fileName);
    }

    /**
     * Add a definition
     *
     * @param Svg_Element $element
     */
    public function addDefinition($element)
    {
        $this->sxml_append($this->defs, $element->getSvgData());
    }

    /**
     * Add a 'use'
     *
     * @param string $id
     * @param array $attributes
     */
    public function addUse($id, $attributes = array())
    {
        $use = new SimpleXMLElement('<use />');
        $use->addAttribute('xlink:xlink:href', '#'.$id);
        foreach($attributes as $key => $value)
        {
            $use->addAttribute($key, $value);
        }
        $this->addElement($use);
    }
}

