<?php
use Watamelo\Managers\DataManager;

/**
 * display a table of Data elements
 * @param array $data Data elements
 */
if (!empty($data)) { ?>

<p class="summary"><?php
    echo $dataView->getSummary($data, $context);
?></p>
<table class="collection">
    <thead>
        <tr><?php
        $firstInstance = reset($data);
        foreach ($firstInstance as $key => $value) {
            if ($dataView->isPropertyReadable($firstInstance, $key) && !$dataView->isPropertySecondary($firstInstance, $key)) { ?>

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
            if ($dataView->isPropertyReadable($instance, $key) && $dataView->getPropertyType($instance, $key) == DataManager::TYPE_PASSWORD) { ?>

                <td>*****</td><?php
            } elseif ($dataView->isPropertyReadable($instance, $key) && !$dataView->isPropertySecondary($instance, $key)) {
                $fgColor = $dataView->getForegroundColor($instance, $key);
                $bgColor = $dataView->getBackgroundColor($instance, $key);
                $type = $dataView->getPropertyType($instance, $key);

                if ($type == DataManager::TYPE_TEXT_MULTI && !empty($instance->$key) && strlen($instance->$key) > 10)
                    $value = mb_strimwidth($instance->$key, 0, 30, "...");
                else
                    $value = $dataView->formated($instance, $key);

                $link = $dataView->getHyperlink($instance, $key);

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

                if ($type == DataManager::TYPE_TEXT_MULTI) {
                    $node->setAttribute('title', $dataView->formated($instance, $key));
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
                <?php include $templatePath.'data/actions.tpl.php'; ?>
            </td>
        </tr><?php
    } ?>

    </tbody>
</table><?php
} ?>
