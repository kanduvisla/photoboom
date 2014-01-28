<?php

echo "Photo-BOOM!!!\n";
echo "Command-Line Photoalbum generator because all those apps from different manufacturers just don't quite do the trick...\n\n";

if(!file_exists('./images') || !is_dir('./images')) {
    die("Error: images-folder not found. Aborting...\n");
}

// Load dependencies:
require_once('inc/Svg_Element.php');
require_once('inc/Svg_Document.php');
require_once('inc/Svg_Group.php');
require_once('inc/Svg_Border.php');

$files = glob('images/*.jpg');
printf("Found %d images\n", count($files));

// Iterate through the files:
foreach($files as $file)
{
    $info = getimagesize($file);
    // echo $info[1] / $info[0] . "\n";
}

// Testing purposes:
$svg = new Svg_Document(600, 400);

$rect = new Svg_Element('rect',
    array(
        'rx' => 20,
        'ry' => 20,
        'width' => 420,
        'height' => 220,
        'fill' => 'red'
    )
);

$group = new Svg_Group('group01');
$group->addElement($rect);

$svg->addDefinition($group);

$svg->addUse('group01', array('x' => 10, 'y' => 10));

$border = new Svg_Border();
$svg->addElement($border);

$svg->parse('output/test.svg');