<?php
$pageTitle = sprintf('Liste de %s %s - page %u/%u', count($data), $model, $page, $lastPage);
$context = $model;

// TODO: remove dependency to this structure/naming
include $templatePath . 'general/header.tpl.php';
?>
    <header>
        <h2><?php echo $pageTitle; ?></h2>
        <?php include $pluginTplPath . 'nav.tpl.php'; ?>
    </header>

    <form action="" method="get" class="filters"><?php
        $filters = $breadView->getAvailableFilters($model);
        $foreignKeys = $breadView->getForeignKeys($model);
        $foreignData = [];
        $formInstance = $breadView->getInstance($model);
        foreach ($filters as $name => $filter) {
            if(property_exists($formInstance, $name) && !empty($_GET[$name])) {
                $formInstance->$name = $_GET[$name];
            }
            if ($filter['source'] == 'list') {
                $class = $filter['class'];

                $foreignData[$filter['class']] = $filter['data'];
                include $pluginTplPath . 'formFields/dropdown.tpl.php';
            }
        } ?>

        <input type="submit" value="Filtrer">
    </form>
<?php include $pluginTplPath . 'collectionTable.tpl.php'; ?>
<?php include $templatePath . 'general/footer.tpl.php'; ?>