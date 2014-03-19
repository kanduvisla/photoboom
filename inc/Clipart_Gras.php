<?php

class Clipart_Gras extends Clipart
{
    /**
     * Initialize
     */
    protected function init()
    {
        $showGrass = rand(0, 100) < 25;
        $showClouds = rand(0, 100) < 50;
        $showButterfly = rand(0, 100) < 25;
        $showKite = rand(0, 100) < 15;
        $showBee = rand(0, 100) < 15;
        $cloudCount = rand(1, 6);
        $butterflyCount = rand(5, 10);

        if($showClouds)
        {
            Boom::importDefinitions($this->svg, glob('./clipart/cloud*.svg'));
            for($i = 0; $i < $cloudCount; $i ++) {
                $this->svg->addUse('cloud' . rand(1, 3), array(
                    'x' => $GLOBALS['page_width'] * (rand(0, 200) / 100),
                    'y' => $GLOBALS['margin'] + (rand(0, 200))
                ));
            }
        }

        if($showGrass)
        {
            Boom::importDefinitions($this->svg, './clipart/gras.svg');
            $this->svg->addUse('gras', array(
                'x' => 0,
                'y' => $GLOBALS['page_height'] - 200
            ));
        }

        if($showButterfly)
        {
            Boom::importDefinitions($this->svg, './clipart/butterfly.svg');
            for($i = 0; $i < $butterflyCount; $i ++)
            {
                $x = $GLOBALS['page_width'] * (rand(0, 200) / 100);
                $y = $GLOBALS['margin'] + (rand(0, $GLOBALS['page_height'] / 1.5));
                $this->svg->addUse('butterfly', array(
                    'transform' => 'translate('.$x.','.$y.'), rotate('.rand(-70, 70).'), scale('.(rand(10, 100) / 100).')',
                    'fill' => Boom::randomColor()
                ), true);
            }
        }

        if($showKite)
        {
            Boom::importDefinitions($this->svg, './clipart/vlieger.svg');
            $x = $GLOBALS['page_width'] * (rand(0, 200) / 200);
            $y = 250;
            $this->svg->addUse('vlieger', array('x' => $x, 'y' => $y));
        }

        if($showBee)
        {
            Boom::importDefinitions($this->svg, './clipart/bij.svg');
            $x = $GLOBALS['page_width'] * (rand(0, 200) / 100);
            $y = $GLOBALS['margin'] + (rand(0, $GLOBALS['page_height'] / 1.5));
            $this->svg->addUse('bij', array('x' => $x, 'y' => $y, 'transform' => 'scale(0.5), rotate('.rand(-30, 30).')'));
        }
    }
}