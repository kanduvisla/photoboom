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
                'version' => '1.1',
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
     * Set the default drop shadow
     *
     * @param array $parameters
     */
    public function setDropshadow($parameters = array())
    {
        // Set the filter:
        $filter = new Svg_Element('filter',
            array(
                'id' => 'dropshadow',
                'x' => 0,
                'y' => 0,
                'width' => '200%',
                'height' => '200%'
            )
        );

        // Set the offset:
        $feOffset = new Svg_Element('feOffset',
            array(
                'result' => 'offOut',
                'in' => 'SourceAlpha',
                'dx' => 2,
                'dy' => 2
            )
        );

        // Set the blur:
        $feGaussianBlur = new Svg_Element('feGaussianBlur',
            array(
                'result' => 'blurOut',
                'in' => 'offOut',
                'stdDeviation' => 3
            )
        );

        // Set the blend:
        $feBlend = new Svg_Element('feBlend',
            array(
                'in' => 'SourceGraphic',
                'in2' => 'blurOut',
                'mode' => 'normal'
            )
        );

        $filter->addElement($feOffset);
        $filter->addElement($feGaussianBlur);
        $filter->addElement($feBlend);
        $this->addDefinition($filter);
    }

    /**
     * Import an SVG file as a definition
     *
     * @param $filename
     * @param $id
     */
    public function importSvgAsDefinition($filename, $id)
    {
        $svgXML = simplexml_load_file($filename);
        $group  = $svgXML->g;
        $group['id'] = $id;
        $g = new SimpleXMLElement($group->asXML());
        $this->sxml_append($this->defs, $g);
    }

    /**
     * Add a 'use'
     *
     * @param string $id
     * @param array $attributes
     * @param boolean $copy
     */
    public function addUse($id, $attributes = array(), $copy = false)
    {

        if(!$copy)
        {
            $use = new SimpleXMLElement('<use />');
            $use->addAttribute('xlink:xlink:href', '#'.$id);
            foreach($attributes as $key => $value)
            {
                $use->addAttribute($key, $value);
            }
            $this->addElement($use);
        } else {

            $g = new Svg_Group($id.rand(100000, 999999));
            foreach($attributes as $key => $value)
            {
                $g->getSvgData()->addAttribute($key, $value);
            }
            $results = $this->defs->xpath('g[@id="'.$id.'"]/path');
            $group   = $results[0];
            $group->attributes()->fill = null;
            $this->sxml_append($g->getSvgData(), $group);
            $this->addElement($g);
        }
    }
}

