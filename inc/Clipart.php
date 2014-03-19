<?php

class Clipart
{
    protected $svg;

    /**
     * Constructor
     *
     * @param Svg_Document $svg
     */
    public function __construct($svg)
    {
        $this->svg = $svg;
        $this->init();
    }

    /**
     * Initialize
     */
    protected function init()
    {

    }
}