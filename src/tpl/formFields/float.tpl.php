<?php
/**
 * display a form field for floating numbers
 * @param string $name field name
 * @param Data $formInstance instance of resource
 * @param bool $setFocus should the field have focus
 */

use Watamelo\Data\Data;

?>
<div>
    <label for="<?php echo $name; ?>"><?php echo $name; ?></label>
    <input type="number" id="<?php echo $name; ?>" name="<?php echo $name; ?>" step="0.01" value="<?php
    if (isset($formInstance->$name)) {
        echo $formInstance->$name;
    } ?>"<?php
    if ($setFocus && empty($formInstance->$name)) {
        echo ' autofocus';
        $setFocus = false;
    } ?>>
</div>
