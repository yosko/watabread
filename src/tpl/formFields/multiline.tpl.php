<?php
/**
 * display a form field for multiline texts
 * @param string $name field name
 * @param BreadModel $formInstance instance of resource
 * @param bool $setFocus should the field have focus
 */

use Yosko\WataBread\BreadModel;

?>
<div>
    <label for="<?php echo $name; ?>"><?php echo $name; ?></label>
    <textarea id="<?php echo $name; ?>" name="<?php echo $name; ?>"<?php
    if ($setFocus && empty($formInstance->$name)) {
        echo ' autofocus';
        $setFocus = false;
    } ?>><?php
    if (isset($formInstance->$name)) {
        echo $formInstance->$name;
    } ?></textarea>
</div>
