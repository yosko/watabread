<?php

use Yosko\WataBread\BreadManager;

$model = $formInstance->getClassName();
if(!empty($instance)) {
    $pageTitle = "Modification de ".$instance->getTitle();
} else {
    $pageTitle = "Ajout de ".$model;
}
include $templatePath.'general/header.tpl.php';
?>

        <header>
            <h2><?php echo $pageTitle; ?></h2>
            <?php include $pluginTplPath.'nav.tpl.php'; ?>
        </header>
        <form method="post" action="">
            <fieldset>
                <legend><?php echo $model; ?></legend>
                <?php

                if (!empty($instance)) {
                    foreach ($breadView->getHidden($formInstance) as $name) {
                        include $pluginTplPath . 'formFields/hidden.tpl.php';
                    }
                }

                $setFocus = true;
                $foreignKeys = $breadView->getForeignKeys($model);
                foreach ($formInstance as $name => $value) {
                    if (!$breadView->isHidden($formInstance, $name) && $breadView->isPropertyWritable($formInstance, $name)) {


                        switch ($breadView->getPropertyType($formInstance, $name)) {
                            case BreadManager::TYPE_INT:
                                if (isset($foreignKeys[$name])) {
                                    include $pluginTplPath . 'formFields/dropdown.tpl.php';
                                } else {
                                    include $pluginTplPath . 'formFields/integer.tpl.php';
                                }
                                break;

                            case BreadManager::TYPE_FLOAT:
                            case BreadManager::TYPE_MONEY:
                                include $pluginTplPath . 'formFields/float.tpl.php';
                                break;

                            case BreadManager::TYPE_TEXT:
                                include $pluginTplPath . 'formFields/text.tpl.php';
                                break;

                            case BreadManager::TYPE_TEXT_MULTI:
                                include $pluginTplPath . 'formFields/multiline.tpl.php';
                                break;

                            case BreadManager::TYPE_BOOL:
                                include $pluginTplPath . 'formFields/boolean.tpl.php';
                                break;

                            case BreadManager::TYPE_DATE:
                                include $pluginTplPath . 'formFields/date.tpl.php';
                                break;

                            case BreadManager::TYPE_DATETIME:
                                include $pluginTplPath . 'formFields/datetime.tpl.php';
                                break;

                            case BreadManager::TYPE_PASSWORD:
                                include $pluginTplPath . 'formFields/password.tpl.php';
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                } ?>

            </fieldset>
            <input type="submit" name="submitForm" id="submitForm" value="Enregister" />
        </form>

<?php include $templatePath.'general/footer.tpl.php'; ?>