<?php
/**
 * display a form field for booleans
 * @param string $name field name
 * @param BreadModel $formInstance instance of resource
 * @param bool $setFocus should the field have focus
 */

use Yosko\WataBread\BreadModel;
?>
<div>
    <label class="checkboxLabel" for="<?php echo $name; ?>">
        <input type="checkbox" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="checked"<?php
        if (isset($formInstance->$name) && $formInstance->$name) {
            echo ' checked';
        }
        if ($setFocus && empty($formInstance->$name)) {
            echo ' autofocus';
            $setFocus = false;
        } ?>>
        <?php echo $name; ?>
    </label>
</div>
