<?php
/**
 * display a form field for dropdown lists (<select>)
 * @param string $name field name
 * @param Data $formInstance instance of resource
 * @param array $foreignKeys information on foreign keys
 * @param array $foreignData list of foreign items
 * @param bool $setFocus should the field have focus
 */

use Watamelo\Data\Data;

$fClass = $foreignKeys[$name]['class'];
?>
<div>
    <label for="<?php echo $name; ?>"><?php echo $fClass; ?></label>
    <select id="<?php echo $name; ?>" name="<?php echo $name; ?>"<?php
    if ($setFocus && empty($formInstance->$name)) {
        echo ' autofocus';
        $setFocus = false;
    } ?>>
        <option value="">-</option><?php
    foreach ($foreignData[$fClass] as $id => $foreignInstance) {
        ?>

        <option value="<?php echo $id; ?>"<?php
        if ($formInstance->$name == $id) {
            echo ' selected';
        } ?>><?php echo $foreignInstance->getTitle(); ?></option><?php
    } ?>

    </select>
</div>
