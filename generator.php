<?php
    define('BOOM_ROOT', dirname(__FILE__));
    require_once('./Boom/Boom.php');

    use Boom\Boom;

    // Check for download in the header:
    Boom::checkForDownload();
?><!doctype html>
<html>
<head>
    <link rel="stylesheet" href="css/screen.css"/>
</head>
<body>
<div id="page">
    <header id="main-header">
        <!-- Main header -->
        
    </header>
    <aside id="items">
        <!-- Available items -->
        <?php $_items = Boom::getItems(); ?>
        <ul>
        <?php foreach($_items as $_item): ?>
            <li>
                <a href="generator.php?item=<?php echo $_item->getCode(); ?>" <?php
                    if($_item->getCode() == $_GET['item']): ?>class="active"<?php endif; ?>>
                    <?php echo $_item->getName(); ?>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    </aside>
    <section id="preview">
        <!-- This is where the SVG is loaded -->
        <?php if(isset($_GET['item'])): ?>
            <?php $_item = Boom::getItemByCode($_GET['item']); ?>
            <?php if($_item !== false): ?>
                <div id="svg">
                    <?php echo $_item->renderSvg(Boom::getRequestOptions()); ?>
                </div>
                <aside id="options">
                    <form action="index.php">
                        <input type="hidden" name="item" value="<?php echo $_item->getCode(); ?>"/>
                        <!-- Options for each item -->
                        <?php foreach($_item->getOptions() as $_option): ?>
                            <?php echo \Boom\Options::renderOptionController($_option); ?>
                        <?php endforeach; ?>
                        <fieldset>
                            <input type="submit" class="submit" value="submit"/>
                            <input type="submit" class="save" value="save svg" name="save"/>
                            <input type="submit" class="save" value="save png" name="save_png"/>
                        </fieldset>
                    </form>
                </aside>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <footer id="main-footer">
        Copyright &copy; <?php echo date('Y'); ?> <a href="http://gielberkers.com">Giel Berkers</a>
    </footer>    
</div>
<script type="text/javascript" src="js/global.js"></script>
</body>
</html>