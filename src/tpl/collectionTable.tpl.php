<?php
use Yosko\WataBread\BreadManager;

/**
 * display a table of BreadModel elements
 * @param array $data BreadModel elements
 */
if (!empty($data)) { ?>

<p class="summary"><?php
    echo $breadView->getSummary($data, $context);
?></p>
<table class="collection">
    <thead>
        <tr><?php
        $firstInstance = reset($data);
        $reflectedProperties = (new \ReflectionClass($firstInstance))->getProperties();
        foreach ($reflectedProperties as $property) {
            $key = $property->name;
            $value = $firstInstance->$key ?? null;
        //foreach ($firstInstance as $key => $value) {
            if ($breadView->isPropertyReadable($firstInstance, $key) && !$breadView->isPropertySecondary($firstInstance, $key)) { ?>

                <th><?php echo str_replace('_', ' ', $key); ?></th><?php
            }
        } ?>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody><?php

    // in a paginated context, only display items for the given page
    if (isset($page) && isset($itemsPerPage)) {
        $pageData = array_slice($data, $itemsPerPage * ($page-1), $itemsPerPage, true);
    } else {
        $pageData = $data;
    }

    foreach ($pageData as $instance) {
        /*
        $row = $dom->createElement('tr');
        if ($instance->isEven())
            $row->setAttribute('class', 'even');
        foreach ($instance as $key => $value) {

        }

        echo $dom->saveHTML($row);
        */
        ?>

        <tr<?php echo $instance->isEven() ? ' class="even"' : ''; ?>><?php
        $reflectedProperties = (new \ReflectionClass($instance))->getProperties();
        foreach ($reflectedProperties as $property) {
            $key = $property->name;
            $value = $instance->$key ?? null;
        //foreach ($instance as $key => $value) {
            // passwords are hidden
            
            include $pluginTplPath.'collectionCell.tpl.php';

        } ?>

            <td class="nowrap">
                <?php include $pluginTplPath.'actions.tpl.php'; ?>
            </td>
        </tr><?php
    } ?>

    </tbody>
</table><?php
} ?>
