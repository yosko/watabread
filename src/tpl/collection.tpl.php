<?php

$pageTitle = 'Liste de ' . count($data) . ' ' . $model;
$context = $model;
include $templatePath . 'general/header.tpl.php';
?>
    <header>
        <h2><?php echo $pageTitle; ?></h2>
        <?php include $templatePath . 'data/nav.tpl.php'; ?>
    </header>

    <form action="" method="get" class="filters"><?php
        $filters = $dataView->getAvailableFilters($model);
        $foreignKeys = $dataView->getForeignKeys($model);
        $foreignData = [];
        $formInstance = $dataView->getInstance($model);
        foreach ($filters as $name => $filter) {
            if(property_exists($formInstance, $name) && !empty($_GET[$name])) {
                $formInstance->$name = $_GET[$name];
            }
            if ($filter['source'] == 'list') {
                $class = $filter['class'];

                $foreignData[$filter['class']] = $filter['data'];
                include $templatePath . 'data/formFields/dropdown.tpl.php';
            }
        } ?>

        <input type="submit" value="Filtrer">
    </form>
<?php include $templatePath . 'data/collectionTable.tpl.php'; ?>
<?php include $templatePath . 'general/footer.tpl.php'; ?>