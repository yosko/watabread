<?php
/**
 * display a form field for dates (without time)
 * @param string $name field name
 * @param Data $formInstance instance of resource
 * @param bool $setFocus should the field have focus
 */

use Watamelo\Data\Data;

?>
<div>
    <label for="<?php echo $name; ?>"><?php echo $name; ?></label>
    <input type="date" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php
    if (!empty($formInstance->$name)) {
        echo $formInstance->$name;
    } else {
        echo date('Y-m-d');
    } ?>"<?php
    if ($setFocus && empty($formInstance->$name)) {
        echo ' autofocus';
        $setFocus = false;
    } ?>>
</div>
