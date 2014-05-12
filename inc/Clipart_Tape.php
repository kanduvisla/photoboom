<?php

class Clipart_Tape extends Clipart
{
    /**
     * Initialize
     */
    protected function init()
    {
        Boom::importDefinitions($this->svg, glob('./clipart/tape*.svg'));
    }
}