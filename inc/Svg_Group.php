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
        parent::__construct('g',
            array_merge(
                array(
                    'id' => $id
                ),
                $attributes
            )
        );
    }
}
