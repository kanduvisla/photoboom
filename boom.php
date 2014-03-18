<?php

echo "Photo-BOOM!!!\n";
echo "Command-Line Photoalbum generator because all those apps from different manufacturers just don't quite do the trick...\n\n";

$GLOBALS['args'] = array();
foreach($argv as $var) {
    $args[$var] = $var;
}

if(isset($args['debug'])) {
    echo "Debugging enabled\n\n";
}

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
require_once('inc/Svg_Textbox.php');
require_once('inc/Svg_Pattern.php');
require_once('inc/Svg_Border.php');

$files = glob('images/*.jpg');
printf("Found %d images\n", count($files));

// Set globals:
$GLOBALS['page_width'] = 210 * 4;   // A4
$GLOBALS['page_height'] = 297 * 4;  // A4

$GLOBALS['margin'] = 80;    // Margins around the page
$GLOBALS['padding'] = 40;   // Padding between pictures

$GLOBALS['photo_random_seed'] = array(
    1 => array(0, 0.1),
    2 => array(0.1, 0.3),
    3 => array(0.3, 0.8),
    4 => array(0.8, 1)
); // This is the chance of certain pages to appear.

$GLOBALS['max_image_width'] = $GLOBALS['page_width'] * 1.5;
$GLOBALS['max_image_height'] = $GLOBALS['page_height'] * 1.5;

// Set image ratios:
$GLOBALS['ratio']  = 3/2;

// Colors: (Kuler: Interactive Map (modified) + Girly Girl
$GLOBALS['colors'] = array(
    'F31976',
    '5F2478',
    '06BDB1',
    'F5C229',
    'F1702B',

    'F21B6A',
    'F2BDD0',
    '04BFBF',
    '09A603',
    'F2E205'
);

// Layouts:

// Functions:
function randomColor()
{
    return '#' . $GLOBALS['colors'][rand(0, count($GLOBALS['colors'])-1)];
}

/**
 * @param $positions array
 * @param $svg Svg_Element
 */
function magicLayout($positions, &$svg)
{
    for($i = 0; $i < count($positions); $i ++)
    {
        // Each position has an X and an Y, defining their center in percent.
        // The pictures are put on the page is big as possible with the following conditions:
        // - Their outer boundaries may never exceed the pages' margin.
        // - The photos must have at least this padding between them.

        $currentPosition    = $positions[$i];
        $imgInfo            = getimagesize($currentPosition['file']);
        $imgRatio           = $imgInfo[0] / $imgInfo[1];
        $x                  = $currentPosition['x'] * $GLOBALS['page_width'];
        $y                  = $currentPosition['y'] * $GLOBALS['page_height'];
        $maxWidth  = $GLOBALS['page_width'] * (($currentPosition['x'] * 2) - (max(0, $currentPosition['x'] - 0.5) * 4)) - ($GLOBALS['margin'] * 2);
        $maxHeight = $GLOBALS['page_height'] * (($currentPosition['y'] * 2) - (max(0, $currentPosition['y'] - 0.5) * 4)) - ($GLOBALS['margin'] * 2);

        // Set image size:
        if($imgRatio < 1)
        {
            // Landscape
            $imgWidth = $maxWidth;
            $imgHeight = $maxWidth * $GLOBALS['ratio'];
            if($imgHeight > $maxHeight) {
                $imgWidth = $imgWidth * ($maxHeight / $imgHeight);
                $imgHeight = $maxHeight;
            }
        } else {
            // Portrait
            $imgHeight = $maxHeight;
            $imgWidth = $maxHeight * $GLOBALS['ratio'];
            if($imgWidth > $maxWidth) {
                $imgHeight = $imgHeight * ($maxWidth / $imgWidth);
                $imgWidth  = $maxWidth;
            }
        }

        // Debug:
        if(isset($GLOBALS['args']['debug']))
        {
            $rect = new Svg_Element('rect', array(
                'fill' => 'none',
                'stroke' => 'blue',
                'x' => $x - $maxWidth / 2,
                'y' => $y - $maxHeight / 2,
                'width' => $maxWidth,
                'height' => $maxHeight
            ));
            $svg->addElement($rect);

            $rect = new Svg_Element('rect', array(
                'fill' => 'none',
                'stroke' => 'red',
                'x' => $x - $imgWidth / 2,
                'y' => $y - $imgHeight / 2,
                'width' => $imgWidth,
                'height' => $imgHeight
            ));
            $svg->addElement($rect);
            // End debug
        }

        // Add photo:
        $image = new Svg_Fancybox($currentPosition['file'],
            array(
                'x' => $x - $imgWidth / 2,
                'y' => $y - $imgHeight / 2,
                'width' => $imgWidth,
                'height' => $imgHeight,
                'border-radius' => 20,
                'stroke' => array(
                    array(
                        'width' => 3, 'dasharray' => '5, 5', 'color' => randomColor(), 'linecap' => 'round', 'offset' => 5
                    ),
                    array(
                        'width' => 3, 'dasharray' => '5, 5', 'color' => randomColor(), 'linecap' => 'round', 'offset' => -5
                    )
                ),
                'rotation' => rand(-10, 10),
                'extra' => 'tack'
            )
        );
        // $svg->addElement($image);
    }
}

/**
 * Helper function to sort array
 *
 * @param $a
 * @param $b
 * @return bool
 */
function sortByOrientation($a, $b)
{
    return $a['orientation'] < $b['orientation'];
}

function importDefinitions(&$svg, $files)
{
    foreach($files as $svgFile)
    {
        $svg->importSvgAsDefinition($svgFile, str_replace('.svg', '', basename($svgFile)));
    }
}

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
    if($current + $count > count($files)) {
        // Last page
        $count = count($files) - $current;
        $spread = false;
        $lastpage = true;
    } else {
        $spread = $current != 0;
        $lastpage = false;
    }
    $pages[] = $count;
    $current += $count;
    printf("Page %d will get %d photos. (rand=%s)\n", count($pages), $count, number_format($random, 2));

    // Create new page:
    $width = $spread ? $GLOBALS['page_width'] * 2 : $GLOBALS['page_width'];
    if(count($pages) == 1)
    {
        $svg = new Svg_Document($width, $GLOBALS['page_height']);
    }

    // Create a background:
    if(!(count($pages) % 2 == 1 && $spread))
    {
        $types = array('dots', 'lines', 'lines45');
        $selectedType = $types[rand(0, 2)];
        switch($selectedType) {
            case 'dots' :
                $fill = randomColor();
                $colorsArr = array('white');
                $opacity = rand(0, 1) == 1 ? 1 : 0.25;
                break;
            default :
                $fill = 'white';
                $colorsArr = rand(0, 1) == 1 ? array(randomColor()) : array(randomColor(), randomColor());
                $opacity = 0.25;
                break;
        }
        $pattern = new Svg_Pattern(
            array(
                'type' => $selectedType,
                'colors' => $colorsArr,
                'opacity' => $opacity,
                'fill' => $fill,
                'offset' => 25,
                'size' => 25,
                'width' => $width,
            )
        );
        $svg->addElement($pattern);

        // Clipart?
        $r = rand(0, 100);

        // Clouds and grass:

        // Import SVG's as definitions:
        importDefinitions($svg, glob('./clipart/cloud*.svg'));
        importDefinitions($svg, glob('./clipart/gras.svg'));
        $totalClouds = rand(1, 6);
        for($i = 0; $i < $totalClouds; $i ++) {
            $svg->addUse('cloud' . rand(1, 3), array(
                'x' => $GLOBALS['page_width'] * (rand(0, 200) / 100),
                'y' => $GLOBALS['margin'] + (rand(0, 200))
            ));
        }
        $svg->addUse('gras', array(
            'x' => 0,
            'y' => $GLOBALS['page_height'] - 200
        ));

        // Random butterflies:
        $totalButterflies = rand(1, 6);
        importDefinitions($svg, glob('./clipart/butterfly.svg'));
        for($i = 0; $i < $totalButterflies; $i ++)
        {
            $x = $GLOBALS['page_width'] * (rand(0, 200) / 100);
            $y = $GLOBALS['margin'] + (rand(0, $GLOBALS['page_height']));
            $svg->addUse('butterfly', array(
//                'x' => $GLOBALS['page_width'] * (rand(0, 200) / 100),
//                'y' => $GLOBALS['margin'] + (rand(0, 500)),
                'transform' => 'translate('.$x.','.$y.'), rotate('.rand(-70, 70).'), scale('.(rand(10, 100) / 100).')',
                'fill' => randomColor()
            ), true);
        }
    }



    // Add the photo's:
    $leftX = (count($pages) % 2 == 1 && $spread) ? $GLOBALS['page_width'] : 0;
    $centerX = $leftX + $GLOBALS['page_width'] / 2;
    $centerY = $GLOBALS['page_height'] / 2;
    $wrap = new Svg_Group(
        'wrap_'.count($pages)
    );
    $wrap->getSvgData()->addAttribute('transform', 'translate('.$leftX.', 0)');

    switch($count)
    {
        case 1:
        {
            // Center picture:
            $positions = array(
                array(
                    'file' => $files[$current - 1],
                    'x' => 0.5,
                    'y' => 0.5
                )
            );
            magicLayout($positions, $wrap);
            break;
        }
        case 2:
        {
            // 2 pictures:
            $file1 = $files[$current - 1];
            $file2 = $files[$current - 2];
            $info = getimagesize($file1);
            $orientation1 = $info[0] / $info[1];
            $info = getimagesize($file2);
            $orientation2 = $info[0] / $info[1];

            if($orientation1 > 1 && $orientation2 > 1) {
                // landscape, landscape
                $positions = array(
                    array(
                        'file' => $file1, 'x' => 0.5, 'y' => 0.29
                    ),
                    array(
                        'file' => $file2, 'x' => 0.5, 'y' => 0.71
                    )
                );
            } elseif($orientation1 < 1 && $orientation2 < 1) {
                // portrait, portrait
                $positions = array(
                    array(
                        'file' => $file1, 'x' => 0.29, 'y' => 0.5
                    ),
                    array(
                        'file' => $file2, 'x' => 0.71, 'y' => 0.5
                    )
                );
            } else {
                // portrait, landscape
                $positions = array(
                    array(
                        'file' => $file1, 'x' => 0.5, 'y' => 0.29
                    ),
                    array(
                        'file' => $file2, 'x' => 0.5, 'y' => 0.71
                    )
                );
            }
            magicLayout($positions, $wrap);
            break;
        }
        case 3:
        {
            // 3 pictures:
            $file1 = $files[$current - 1];
            $file2 = $files[$current - 2];
            $file3 = $files[$current - 3];
            $info = getimagesize($file1);
            $orientation1 = $info[0] / $info[1];
            $info = getimagesize($file2);
            $orientation2 = $info[0] / $info[1];
            $info = getimagesize($file3);
            $orientation3 = $info[0] / $info[1];
            /*
             * 000
             * 100
             * 110
             * 111
             */

            // Sort by orientation:
            $f = array(
                array('file' => $file1, 'orientation' => $orientation1),
                array('file' => $file2, 'orientation' => $orientation2),
                array('file' => $file3, 'orientation' => $orientation3),
            );
            usort($f, sortByOrientation);
            if($f[0]['orientation'] > 1 && $f[1]['orientation'] > 1 && $f[2]['orientation'] > 1) {
                // landscape, landscape, landscape
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.35, 'y' => 0.25
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.65, 'y' => 0.5
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.35, 'y' => 0.75
                    )
                );
            } elseif($f[0]['orientation'] > 1 && $f[1]['orientation'] > 1 && $f[2]['orientation'] < 1) {
                // landscape, landscape, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.5, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.3, 'y' => 0.7
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.75, 'y' => 0.7
                    )
                );
            } elseif($f[0]['orientation'] > 1 && $f[1]['orientation'] < 1 && $f[2]['orientation'] < 1) {
                // landscape, portrait, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.5, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.25, 'y' => 0.7
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.75, 'y' => 0.7
                    )
                );
            } else {
                // portrait, portrait, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.3, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.3, 'y' => 0.5
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.7, 'y' => 0.7
                    )
                );
            }

            magicLayout($positions, $wrap);

            break;
        }
        case 4:
        {
            // 4 pictures:
            $file1 = $files[$current - 1];
            $file2 = $files[$current - 2];
            $file3 = $files[$current - 3];
            $file4 = $files[$current - 4];
            $info = getimagesize($file1);
            $orientation1 = $info[0] / $info[1];
            $info = getimagesize($file2);
            $orientation2 = $info[0] / $info[1];
            $info = getimagesize($file3);
            $orientation3 = $info[0] / $info[1];
            $info = getimagesize($file4);
            $orientation4 = $info[0] / $info[1];
            /*
             * 0000
             * 1000
             * 1100
             * 1110
             * 1111
             */
            // Sort by orientation:
            $f = array(
                array('file' => $file1, 'orientation' => $orientation1),
                array('file' => $file2, 'orientation' => $orientation2),
                array('file' => $file3, 'orientation' => $orientation3),
                array('file' => $file4, 'orientation' => $orientation4)
            );
            usort($f, sortByOrientation);

            if(
                ($f[0]['orientation'] > 1 && $f[1]['orientation'] > 1 && $f[3]['orientation'] > 1 && $f[4]['orientation'] > 1) ||
                ($f[0]['orientation'] < 1 && $f[1]['orientation'] < 1 && $f[3]['orientation'] < 1 && $f[4]['orientation'] < 1)
            ) {
                // landscape, landscape, landscape, landscape
                // portrait, portrait, portrait, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.3, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.7, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.3, 'y' => 0.7
                    ),
                    array(
                        'file' => $f[3]['file'], 'x' => 0.7, 'y' => 0.7
                    )
                );
            } elseif($f[0]['orientation'] > 1 && $f[1]['orientation'] > 1 && $f[3]['orientation'] > 1 && $f[4]['orientation'] < 1) {
                // landscape, landscape, landscape, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.5, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.3, 'y' => 0.5
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.3, 'y' => 0.7
                    ),
                    array(
                        'file' => $f[3]['file'], 'x' => 0.7, 'y' => 0.6
                    )
                );
            } elseif($f[0]['orientation'] > 1 && $f[1]['orientation'] < 1 && $f[3]['orientation'] < 1 && $f[4]['orientation'] < 1) {
                // landscape, landscape, portrait, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.3, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.7, 'y' => 0.7
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.7, 'y' => 0.4
                    ),
                    array(
                        'file' => $f[3]['file'], 'x' => 0.3, 'y' => 0.6
                    )
                );
            } else {
                // landscape, portrait, portrait, portrait
                $positions = array(
                    array(
                        'file' => $f[0]['file'], 'x' => 0.5, 'y' => 0.3
                    ),
                    array(
                        'file' => $f[1]['file'], 'x' => 0.25, 'y' => 0.75
                    ),
                    array(
                        'file' => $f[2]['file'], 'x' => 0.5, 'y' => 0.75
                    ),
                    array(
                        'file' => $f[3]['file'], 'x' => 0.75, 'y' => 0.75
                    )
                );
            }

            magicLayout($positions, $wrap);

            break;
        }
    }
    $svg->addElement($wrap);

    if(count($pages) % 2 == 1) {
        // Add border:
        $border = new Svg_Border(
            array(
                'fill' => randomColor(),
                'stroke' => randomColor(),
                'border-radius' => 10,
                'size' => 40,
                'width' => $width
            )
        );
        $svg->addElement($border);

        printf("Save page... (page%d.svg)\n", ceil(count($pages) / 2));
        $svg->parse(sprintf('./page%d.svg', ceil(count($pages) / 2)));

        $spread = $current != 0 && !$lastpage;
        $width = $spread ? $GLOBALS['page_width'] * 2 : $GLOBALS['page_width'];

        // Create new page:
        $svg = new Svg_Document($width, $GLOBALS['page_height']);

        // Import SVG's as definitions:
//        foreach(glob('./clipart/*.svg') as $svgFile)
//        {
//            $svg->importSvgAsDefinition($svgFile, str_replace('.svg', '', basename($svgFile)));
//        }
    }
}

if(count($pages) % 2 == 0) {
    // Save last page:
    $pageNum = ceil(count($pages) / 2);
    if(count($pages) % 2 == 0) {
        $pageNum += 1;
    }
    printf("Save page... (page%d.svg)\n", $pageNum);
    $svg->parse(sprintf('./page%d.svg', $pageNum));
}


/*foreach($files as $file)
{
    $info = getimagesize($file);
//     echo $info[1] / $info[0] . "\n";

}*/

// Testing purposes:
//$svg = new Svg_Document($GLOBALS['page_width'], $GLOBALS['page_height']);

// Set the dropshadow:
//$svg->setDropshadow();

// Import SVG's as definitions:
//foreach(glob('./clipart/*.svg') as $svgFile)
//{
//    $svg->importSvgAsDefinition($svgFile, str_replace('.svg', '', basename($svgFile)));
//}

// Calculate layouts:
// Layouts are done by drawing imaginary lines:
/**
 * @param $svg      Svg_Document
 * @param $options  array
 */
/*function generateLayout($svg, $options)
{

}

generateLayout($svg,
    array(

    )
);*/

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

// Pattern test:
/*
$pattern = new Svg_Pattern(
    array(
        'type' => 'dots',
        'colors' => array('red', 'blue'),
        'opacity' => .25,
        'fill' => 'white',
        'offset' => 25,
        'size' => 25
    )
);

$svg->addElement($pattern);

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

$image = new Svg_Fancybox('./images/test.jpg', array('width' => 300, 'height' => 300, 'x' => 350, 'y' => 350,
    'extra' => 'tack', 'rotation' => -5/*, 'dropshadow' => 1*//*));
$svg->addElement($image);

$image = new Svg_Fancybox('./images/test.jpg', array('width' => 550, 'height' => 200, 'x' => 100, 'y' => 700));
$svg->addElement($image);

// Add text:
$textbox = new Svg_Textbox('Hello world' , array('x' => 500, 'y' => 850, 'border-radius' => 10, 'rotation' => 5,
    'stroke' => array(
            array(
                'width' => 3, 'dasharray' => '5, 5', 'color' => '#ff0000', 'linecap' => 'round', 'offset' => 5
            ),
            array(
                'width' => 3, 'dasharray' => '5, 5', 'color' => '#00ff00', 'linecap' => 'round', 'offset' => -5
            )
        )
    )
);

$svg->addElement($textbox);

// Add border:
$border = new Svg_Border(array('fill' => '#0000ff', 'border-radius' => 10, 'size' => 40));
$svg->addElement($border);

$svg->parse('./test.svg');
*/