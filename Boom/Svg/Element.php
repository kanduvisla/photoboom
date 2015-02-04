<?php

namespace Boom\Svg;

class Element
{
    protected $svg;         // SimpleXMLElement that acts for this SVG Element
    protected $attributes;  // Array with default attributes

    /**
     * Create a new SVG Element
     *
     * @param string $type
     * @param array $attributes
     */
    public function __construct($type, $attributes = array())
    {
        $this->svg = new \SimpleXMLElement('<'.$type.' />');
        $this->mergeAttributes($attributes);
        foreach($this->attributes as $key => $value)
        {
            $this->svg->addAttribute($key, $value);
        }
    }

    /**
     * Get the SVG Elements' XML Data
     *
     * @return \SimpleXMLElement
     */
    public function getSvgData()
    {
        return $this->svg;
    }

    /**
     * Add a SVG Element
     *
     * @param Element $element
     */
    public function addElement($element)
    {
        $this->sxml_append($this->svg, $element->getSvgData());
    }

    /**
     * Append one SimpleXMLElement to another
     *
     * @param \SimpleXMLElement $to
     * @param \SimpleXMLElement $from
     */
    protected function sxml_append(\SimpleXMLElement $to, \SimpleXMLElement $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }

    /**
     * Merge settings
     *
     * @param array $defaultSettings
     * @param array $userSettings
     * @return array
     */
//    protected function merge_settings($defaultSettings, $userSettings)
//    {
//        foreach($defaultSettings as $key => $value)
//        {
//            if(isset($userSettings[$key])) {
//                $defaultSettings[$key] = $userSettings[$key];
//            }
//        }
//        return $defaultSettings;
//    }

    /**
     * Merge the attributes
     *
     * @param array ...
     */
    protected function mergeAttributes()
    {
        if(!is_array($this->attributes)) { $this->attributes = array(); }
        $args = func_get_args();
        for($i = 0; $i < func_num_args(); $i++)
        {
            if(is_array($args[$i])) {
                foreach($args[$i] as $key => $value)
                {
                    $this->attributes[$key] = $value;
                }
            }
        }
    }

    /**
     * Stroke function
     */
    protected function stroke()
    {
        if(isset($this->attributes['stroke']) !== false && is_array($this->attributes['stroke']))
        {
            foreach($this->attributes['stroke'] as $stroke)
            {
                if(is_array($stroke))
                {
                    if(!isset($stroke['offset'])) { $stroke['offset'] = 0; }
                    $strokeAttributes = array(
                        'width'     => $this->attributes['width'] + ($stroke['offset'] * 2),
                        'height'    => $this->attributes['height'] + ($stroke['offset'] * 2),
                        'fill'      => 'none',
                        'rx'        => $this->attributes['border-radius'],
                        'ry'        => $this->attributes['border-radius'],
                        'x'         => -$stroke['offset'],
                        'y'         => -$stroke['offset']
                    );
                    if(isset($stroke['color']))     { $strokeAttributes['stroke'] = $stroke['color']; }
                    if(isset($stroke['width']))     { $strokeAttributes['stroke-width'] = $stroke['width']; }
                    if(isset($stroke['dasharray'])) { $strokeAttributes['stroke-dasharray'] = $stroke['dasharray']; }
                    if(isset($stroke['linecap']))   { $strokeAttributes['stroke-linecap'] = $stroke['linecap']; }

                    $strokeRect = new Element('rect', $strokeAttributes);
                    $this->addElement($strokeRect);
                }
            }
        }
    }
}