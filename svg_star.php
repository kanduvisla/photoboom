<?php
/**
 * Example of how to draw a star in SVG
 */

// Play around with these parameters:
$pointCount = 5;
$innerRadius = 25;
$outerRadius = 50;

// The magic:
$piPart = (pi() / $pointCount);
$starX = 50;
$starY = 50;
$points = array();
$piOffset = pi();   // We have to add an offset, otherwise our star is upside down ;-)

// Generate the points by crunching some numbers:
for($i = 0; $i < $pointCount * 2; $i+=2)
{
    $points[] = ($starX + (sin($piOffset + $piPart * $i) * $outerRadius)) . ',' .
        ($starY + (cos($piOffset + $piPart * $i)) * $outerRadius);
    $points[] = ($starX + (sin($piOffset + $piPart * ($i + 1)) * $innerRadius)) . ',' .
        ($starY + (cos($piOffset + $piPart * ($i + 1))) * $innerRadius);
}

// Time to render some SVG:
$svg = sprintf('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="300" height="300">
    <polygon cx="%1$d" cy="%2$d" stroke="black" fill="none" points="%3$s"></polygon>
</svg>', $starX, $starY, implode(' ', $points));

echo $svg;
// It's that easy!