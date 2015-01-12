<?php
    define('BOOM_ROOT', dirname(__FILE__));
    require_once('./Boom/Boom.php');

    use Boom\Boom;
?>
<html>
<head></head>
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
                <a href="index.php?item=<?php echo $_item->getCode(); ?>">
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
                    <?php echo $_item->renderSvg(); ?>
                </div>
                <aside id="options">
                    <!-- Options for each item -->
                    <?php foreach($_item->getOptions() as $_option): ?>
                        <?php echo \Boom\Options::renderOptionController($_option); ?>
                    <?php endforeach; ?>
                </aside>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <footer id="main-footer">
        Copyright &copy; <?php echo date('Y'); ?> <a href="http://gielberkers.com">Giel Berkers</a>
    </footer>
    
</div>
</body>
</html>