<?php

class Clipart_Tacks extends Clipart
{
    /**
     * Initialize
     */
    protected function init()
    {
        Boom::importDefinitions($this->svg, glob('./clipart/tack*.svg'));
    }
}