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
        foreach ($firstInstance as $key => $value) {
            if ($breadView->isPropertyReadable($firstInstance, $key) && !$breadView->isPropertySecondary($firstInstance, $key)) { ?>

                <th><?php echo str_replace('_', ' ', $key); ?></th><?php
            }
        } ?>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody><?php
    foreach ($data as $instance) {
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
        foreach ($instance as $key => $value) {
            // passwords are hidden
            if ($breadView->isPropertyReadable($instance, $key) && $breadView->getPropertyType($instance, $key) == BreadManager::TYPE_PASSWORD) { ?>

                <td>*****</td><?php
            } elseif ($breadView->isPropertyReadable($instance, $key) && !$breadView->isPropertySecondary($instance, $key)) {
                $fgColor = $breadView->getForegroundColor($instance, $key);
                $bgColor = $breadView->getBackgroundColor($instance, $key);
                $type = $breadView->getPropertyType($instance, $key);

                if ($type == BreadManager::TYPE_TEXT_MULTI && !empty($instance->$key) && strlen($instance->$key) > 10)
                    $value = mb_strimwidth($instance->$key, 0, 30, "...");
                else
                    $value = $breadView->formated($instance, $key);

                $link = $breadView->getHyperlink($instance, $key);

                $node = $dom->createElement('td');
                $textNode = $dom->createTextNode($value);
                if (empty($link))
                    $node->appendChild($textNode);
                else {
                    $linkNode = $dom->createElement('a');
                    $linkNode->setAttribute('href', $link);
                    $linkNode->appendChild($textNode);
                    $node->appendChild($linkNode);
                }

                if ($type == BreadManager::TYPE_TEXT_MULTI) {
                    $node->setAttribute('title', $breadView->formated($instance, $key));
                    $node->setAttribute('class', 'multiline');
                }

                if (!empty($fgColor) || !empty($bgColor)) {
                    $color = '';
                    if (!empty($fgColor))
                        $color .= 'color: '.$fgColor.'; ';
                    if (!empty($bgColor))
                        $color .= 'background-color: '.$bgColor.'; ';
                    $node->setAttribute('style', $color);
                }

                echo $dom->saveHTML($node);
            }

        } ?>

            <td class="nowrap">
                <?php include $pluginTplPath.'actions.tpl.php'; ?>
            </td>
        </tr><?php
    } ?>

    </tbody>
</table><?php
} ?>
