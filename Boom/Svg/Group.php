<?php

namespace Boom\Svg;

class Group extends Element
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
