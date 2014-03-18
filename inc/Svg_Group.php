<?php

require_once(dirname(__FILE__) . '/Svg_Element.php');

class Svg_Group extends Svg_Element
{
    /**
     * Create a group
     *
     * @param string $id
     * @param array $attributes
     */
    public function __construct($id, $attributes = array())
    {
        // Regardless of attributes, groups can only have an id and/or a transform
        $this->attributes['id'] = $id;
        foreach($this->attributes as $key => $value)
        {
            if(!in_array($key, array('id', 'transform'))) {
                unset($this->attributes[$key]);
            }
        }

        parent::__construct('g');
    }
}
