<?php /** @noinspection PhpUndefinedVariableInspection */

use Watamelo\Managers\DataManager;

$model = $instance->getClassName();
$context = $model;
$pageTitle = $instance->getTitle();
include $templatePath.'general/header.tpl.php';
?>

        <header>
            <h2><?php echo $pageTitle; ?></h2>
            <?php include $templatePath.'data/nav.tpl.php'; ?>
        </header>
        <dl><?php
        foreach ($instance as $name => $value) {
            if ($dataView->isPropertyReadable($instance, $name) && $dataView->getPropertyType($instance, $name) != DataManager::TYPE_PASSWORD) { ?>

                <dt><?php echo str_replace('_', ' ', $name); ?></dt>
                <dd<?php if ($dataView->getPropertyType($instance, $name) == DataManager::TYPE_TEXT_MULTI) { echo ' class="multiline"'; } ?>><?php
                    $link = $dataView->getHyperlink($instance, $name);
                    if (!empty($link))
                        echo '<a href="'.$link.'">';
                    echo $dataView->formated($instance, $name);
                    if (!empty($link))
                        echo '</a>';
                ?></dd><?php

            }
        } ?>

        </dl>

<?php
$self = 'data/collection';
$mainModel = $model;
$mainInstance = $instance;
foreach ($childrenData as $model => $data) {
    $queryStringForAdd = 'id'.$mainModel.'='.$mainInstance->id;
    ?>

    <header>
        <h3><?php echo $model; ?></h3><?php
        include $templatePath . 'data/nav.tpl.php'; ?>

    </header><?php
    include $templatePath.'data/collectionTable.tpl.php';
}

include $templatePath.'general/footer.tpl.php';
?>