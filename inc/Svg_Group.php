<?php

class Svg_Group extends Svg_Element
{
    /**
     * Create a group
     *
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct('g',
            array(
                'id' => $id
            )
        );
    }
}
