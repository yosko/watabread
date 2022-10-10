<?php /** @noinspection PhpUndefinedVariableInspection */

use Yosko\WataBread\BreadManager;

$model = $instance->getClassName();
$context = $model;
$pageTitle = $instance->getTitle();
include $templatePath.'general/header.tpl.php';
?>

        <header>
            <h2><?php echo $pageTitle; ?></h2>
            <?php include $pluginTplPath.'nav.tpl.php'; ?>
        </header>
        <dl><?php
        foreach ($instance as $name => $value) {
            if ($breadView->isPropertyReadable($instance, $name) && $breadView->getPropertyType($instance, $name) != BreadManager::TYPE_PASSWORD) { ?>

                <dt><?php echo str_replace('_', ' ', $name); ?></dt>
                <dd<?php if ($breadView->getPropertyType($instance, $name) == BreadManager::TYPE_TEXT_MULTI) { echo ' class="multiline"'; } ?>><?php
                    $link = $breadView->getHyperlink($instance, $name);
                    if (!empty($link))
                        echo '<a href="'.$link.'">';
                    echo $breadView->formated($instance, $name);
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
        include $pluginTplPath . 'nav.tpl.php'; ?>

    </header><?php
    include $pluginTplPath.'collectionTable.tpl.php';
}

include $templatePath.'general/footer.tpl.php';
?>