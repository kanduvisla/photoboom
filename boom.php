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
require_once('inc/Svg_Imagebox.php');
require_once('inc/Svg_Fancybox.php');
require_once('inc/Svg_Border.php');

$files = glob('images/*.jpg');
printf("Found %d images\n", count($files));

// Set globals:
$GLOBALS['page_width'] = 210 * 4;   // A4
$GLOBALS['page_height'] = 297 * 4;  // A4

$GLOBALS['margin'] = 20;    // Margins around the page

$GLOBALS['photo_random_seed'] = array(
    1 => array(0, 0.1),
    2 => array(0.1, 0.3),
    3 => array(0.3, 0.8),
    4 => array(0.8, 1)
); // This is the chance of certain pages to appear.

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
$current = 0;
while($current < count($files))
{
    $random = rand(0, 100) / 100;
    $count  = 1;
    foreach($GLOBALS['photo_random_seed'] as $amount => $seed)
    {
        if($random >= $seed[0] && $random < $seed[1])
        {
            $count = $amount;
        }
    }
    if($current + $count > count($files)) { $count = count($files) - $current; }
    $pages[] = $count;
    $current += $count;
    printf("Page %d will get %d photos. (rand=%s)\n", count($pages), $count, number_format($random, 2));
}

/*foreach($files as $file)
{
    $info = getimagesize($file);
//     echo $info[1] / $info[0] . "\n";

}*/

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
$image = new Svg_Fancybox('./images/test.jpg', array('width' => 200, 'height' => 200, 'x' => 100, 'y' => 100, 'border-radius' => 20,
    'rotation' => 10, 'stroke' => array(
        array(
            'width' => 3, 'dasharray' => '5, 5', 'color' => '#ff0000', 'linecap' => 'round', 'offset' => 5
        ),
        array(
            'width' => 3, 'dasharray' => '5, 5', 'color' => '#00ff00', 'linecap' => 'round', 'offset' => -5
        )
    )));
$svg->addElement($image);

$image = new Svg_Fancybox('./images/test.jpg', array('width' => 300, 'height' => 200, 'x' => 350, 'y' => 100));
$svg->addElement($image);

$image = new Svg_Fancybox('./images/test.jpg', array('width' => 200, 'height' => 300, 'x' => 100, 'y' => 350));
$svg->addElement($image);

$image = new Svg_Fancybox('./images/test.jpg', array('width' => 300, 'height' => 300, 'x' => 350, 'y' => 350));
$svg->addElement($image);

$image = new Svg_Fancybox('./images/test.jpg', array('width' => 550, 'height' => 200, 'x' => 100, 'y' => 700));
$svg->addElement($image);

$border = new Svg_Border(array('fill' => '#0000ff', 'border-radius' => 10, 'size' => 40));
$svg->addElement($border);

$svg->parse('./test.svg');