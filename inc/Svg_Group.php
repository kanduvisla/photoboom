<?php

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
        $this->mergeAttributes(array('id' => $id), $attributes);
        parent::__construct('g');
    }
}
