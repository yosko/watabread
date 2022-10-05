<?php
/**
 * display a form field for dates with times
 * @param string $name field name
 * @param BreadModel $formInstance instance of resource
 * @param bool $setFocus should the field have focus
 */

use Yosko\WataBread\BreadModel;

$date = null;
if (!empty($formInstance->$name)) {
    $date = new DateTime($formInstance->$name);
} else {
    $date = new DateTime();
    $date->setTime(8, 0);
}

?>
<div>
    <label for="<?php echo $name; ?>Date"><?php echo $name; ?></label>
    <input type="date" id="<?php echo $name; ?>Date" name="<?php echo $name; ?>Date" value="<?php
    if (!is_null($date)) {
        echo $date->format('Y-m-d');
    } ?>"<?php
    if ($setFocus && empty($formInstance->$name)) {
        echo ' autofocus';
        $setFocus = false;
    } ?>>
    <input type="time" id="<?php echo $name; ?>Time" name="<?php echo $name; ?>Time" value="<?php
    if (!is_null($date)) {
        echo $date->format('H:i');
    } ?>">
</div>
