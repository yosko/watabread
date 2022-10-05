<?php
/**
 * add a hidden form field (usually for instance id)
 * @param string $name field name
 * @param BreadModel $formInstance instance of resource
 */
?>
<input type="hidden" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php
    if (isset($formInstance->$name)) {
        echo $formInstance->$name;
    }
?>">
