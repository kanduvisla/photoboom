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

// Check for image dir:
if(!file_exists('./images') || !is_dir('./images')) {
    die("Error: images-folder not found. Aborting...\n");
}

// Load dependencies:
foreach(glob('inc/*.php') as $file)
{
    require_once($file);
}

//$files = glob('images/*.jpg');
//printf("Found %d images\n", count($files));

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

$files = glob('images/*', GLOB_ONLYDIR);

// Iterate through the files and add them to pages:
$pages = array();
$current = 0;

$clipArtNr = 0;

while($current < count($files))
{
    $filesInDir = glob($files[$current].'/*.[jJ][pP][gG]');
    $count  = count($filesInDir);
//    foreach($GLOBALS['photo_random_seed'] as $amount => $seed)
//    {
//        if($random >= $seed[0] && $random < $seed[1])
//        {
//            $count = $amount;
//        }
//    }
//     if($current + $count > count($files)) {
    if($current == count($files) - 1) {
        // Last page
        $spread = false;
        $lastpage = true;
    } else {
        $spread = $current != 0;
        $lastpage = false;
    }
    $pages[] = $count;
    $current += 1;
    printf("Page %d will get %d photos.\n", $current, $count);

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
                $fill = Boom::randomColor();
                $colorsArr = array('white');
                $opacity = rand(0, 1) == 1 ? 1 : 0.25;
                break;
            default :
                $fill = 'white';
                $colorsArr = rand(0, 1) == 1 ? array(Boom::randomColor()) : array(Boom::randomColor(), Boom::randomColor());
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
        $clipArtNr = false;
        $r = rand(0, 100);

        if($r < 25) {
            new Clipart_Gras($svg);
        } elseif($r < 50) {
            new Clipart_Tacks($svg);
            $clipArtNr = 1;
        } elseif($r < 75) {
            new Clipart_Tape($svg);
            $clipArtNr = 2;
        } else {
            new Clipart_Birds($svg);
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

    // Alternative approach:
    $positions = array();
    $predefinedPositions = array(
        1 => array(
            array('x' => 0.5, 'y' => 0.5),
        ),
        2 => array(
            array('x' => 0.5, 'y' => 0.25),
            array('x' => 0.5, 'y' => 0.75),
        ),
        3 => array(
            array('x' => 0.5, 'y' => 0.25),
            array('x' => 0.5, 'y' => 0.5),
            array('x' => 0.5, 'y' => 0.75),
        ),
        4 => array(
            array('x' => 0.25, 'y' => 0.25),
            array('x' => 0.75, 'y' => 0.25),
            array('x' => 0.25, 'y' => 0.75),
            array('x' => 0.75, 'y' => 0.75),
        ),
        5 => array(
            array('x' => 0.5, 'y' => 0.5),
            array('x' => 0.25, 'y' => 0.25),
            array('x' => 0.75, 'y' => 0.25),
            array('x' => 0.25, 'y' => 0.75),
            array('x' => 0.75, 'y' => 0.75),
        ),
        6 => array(
            array('x' => 0.25, 'y' => 0.25),
            array('x' => 0.75, 'y' => 0.25),
            array('x' => 0.25, 'y' => 0.5),
            array('x' => 0.75, 'y' => 0.5),
            array('x' => 0.25, 'y' => 0.75),
            array('x' => 0.75, 'y' => 0.75),
        ),
        7 => array(
            array('x' => 0.5, 'y' => 0.5),
            array('x' => 0.25, 'y' => 0.25),
            array('x' => 0.75, 'y' => 0.25),
            array('x' => 0.25, 'y' => 0.5),
            array('x' => 0.75, 'y' => 0.5),
            array('x' => 0.25, 'y' => 0.75),
            array('x' => 0.75, 'y' => 0.75),
        ),
        8 => array(
            array('x' => 0.25, 'y' => 0.2),
            array('x' => 0.75, 'y' => 0.2),
            array('x' => 0.25, 'y' => 0.4),
            array('x' => 0.75, 'y' => 0.4),
            array('x' => 0.25, 'y' => 0.6),
            array('x' => 0.75, 'y' => 0.6),
            array('x' => 0.25, 'y' => 0.8),
            array('x' => 0.75, 'y' => 0.8),
        ),
        9 => array(
            array('x' => 0.25, 'y' => 0.25),
            array('x' => 0.5, 'y' => 0.25),
            array('x' => 0.75, 'y' => 0.25),
            array('x' => 0.25, 'y' => 0.5),
            array('x' => 0.5, 'y' => 0.5),
            array('x' => 0.75, 'y' => 0.5),
            array('x' => 0.25, 'y' => 0.75),
            array('x' => 0.5, 'y' => 0.75),
            array('x' => 0.75, 'y' => 0.75),
        )
    );
    for($c = 1; $c <= $count; $c++)
    {
        $scale = 0.5 + 1 / $count / 2;

        $positions[] = array(
            'file' => $filesInDir[$c - 1],
            'scale' => $scale,
            'x' => $predefinedPositions[$count][$c - 1]['x'],
            'y' => $predefinedPositions[$count][$c - 1]['y']
        );
    }
    Boom::magicLayout($positions, $wrap, $clipArtNr);

    $svg->addElement($wrap);

    // Add textballoon:
    $textBox = new Svg_Textbox('Lorem ipsum', array(
        'font-size' => 24,
        'font-family' => 'ChalkboardSE-Regular',
        'x' => $GLOBALS['page_width'], 'y' => $GLOBALS['page_height'] / 2,
        'border-radius' => 10
    ));

    $svg->addElement($textBox);

    if(count($pages) % 2 == 1) {
        // Add border:
        $border = new Svg_Border(
            array(
                'fill' => Boom::randomColor(),
                'stroke' => Boom::randomColor(),
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