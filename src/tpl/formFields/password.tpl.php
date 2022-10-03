<?php
/**
 * display a form field for passwords (defaults always empty)
 * @param string $name field name
 * @param Data $formInstance instance of resource
 * @param bool $setFocus should the field have focus
 */

use Watamelo\Data\Data;

?>
<div>
    <label for="<?php echo $name; ?>"><?php echo $name; ?></label>
    <input type="password" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value=""<?php
    if($setFocus && empty($formInstance->$name)) {
        echo ' autofocus';
        $setFocus = false;
    } ?>>
</div>
