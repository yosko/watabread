<?php
/**
 * display a field in a table cell
 * @param string $key field name
 * @param BreadModel $instance instance of resource
 */

use Yosko\WataBread\BreadManager;

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
