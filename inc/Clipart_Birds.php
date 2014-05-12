<?php

class Clipart_Birds extends Clipart
{
    /**
     * Initialize
     */
    protected function init()
    {
        Boom::importDefinitions($this->svg, glob('./clipart/bird*.svg'));

        // Add birds
        $this->svg->addUse('bird' . rand(1, 8), array(
            'x' => $GLOBALS['page_width'] / 2,
            'y' => $GLOBALS['page_height'] / 2
        ));

        if(rand(0, 10) > 5) {
            // Add another:
            $this->svg->addUse('bird' . rand(1, 8), array(
                'x' => $GLOBALS['page_width'] / 2,
                'y' => $GLOBALS['page_height'] / 2
            ));
        }
    }
}