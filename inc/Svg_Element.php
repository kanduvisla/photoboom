<?php

class Svg_Element
{
    protected $svg;

    /**
     * Create a new SVG Element
     *
     * @param string $type
     * @param array $attributes
     */
    public function __construct($type, $attributes = array())
    {
        $this->svg = new SimpleXMLElement('<'.$type.' />');
        foreach($attributes as $key => $value)
        {
            $this->svg->addAttribute($key, $value);
        }
    }

    /**
     * Get the SVG Elements' XML Data
     *
     * @return SimpleXMLElement
     */
    public function getSvgData()
    {
        return $this->svg;
    }

    /**
     * Add a SVG Element
     *
     * @param Svg_Element $element
     */
    public function addElement($element)
    {
        $this->sxml_append($this->svg, $element->getSvgData());
    }

    /**
     * Append one SimpleXMLElement to another
     *
     * @param SimpleXMLElement $to
     * @param SimpleXMLElement $from
     */
    protected function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }
}