<?php
/**
 * add a hidden form field (usually for instance id)
 * @param string $name field name
 * @param Data $formInstance instance of resource
 */
?>
<input type="hidden" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php
    if (isset($formInstance->$name)) {
        echo $formInstance->$name;
    }
?>">
