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
require_once('inc/Svg_Image.php');
require_once('inc/Svg_Border.php');

$files = glob('images/*.jpg');
printf("Found %d images\n", count($files));

// Set globals:
$GLOBALS['page_width'] = 210 * 4;   // A4
$GLOBALS['page_height'] = 297 * 4;  // A4

$GLOBALS['max_image_width'] = $GLOBALS['page_width'] * 1.5;
$GLOBALS['max_image_height'] = $GLOBALS['page_height'] * 1.5;

// Set image ratios:
$GLOBALS['portrait_sizes'] = array(
    1/1, 2/3
);
$GLOBALS['landscape_sizes'] = array(
    1/1, 3/2
);

// Layouts:



// Iterate through the files and add them to pages:
$pages = array();
foreach($files as $file)
{
    $info = getimagesize($file);
//     echo $info[1] / $info[0] . "\n";
}

// Testing purposes:
$svg = new Svg_Document($GLOBALS['page_width'], $GLOBALS['page_height']);

/*$rect = new Svg_Element('rect',
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

$svg->addUse('group01', array('x' => 10, 'y' => 10));*/

// Image test:
$image = new Svg_Image('./images/DSC00234.jpg');
$svg->addElement($image);

$border = new Svg_Border();
$svg->addElement($border);

$svg->parse('./test.svg');