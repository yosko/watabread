<?php

use Watamelo\Managers\DataManager;

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
            <?php include $templatePath.'data/nav.tpl.php'; ?>
        </header>
        <form method="post" action="">
            <fieldset>
                <legend><?php echo $model; ?></legend>
                <?php

                if (!empty($instance)) {
                    foreach ($dataView->getHidden($formInstance) as $name) {
                        include $templatePath . 'data/formFields/hidden.tpl.php';
                    }
                }

                $setFocus = true;
                $foreignKeys = $dataView->getForeignKeys($model);
                foreach ($formInstance as $name => $value) {
                    if (!$dataView->isHidden($formInstance, $name) && $dataView->isPropertyWritable($formInstance, $name)) {


                        switch ($dataView->getPropertyType($formInstance, $name)) {
                            case DataManager::TYPE_INT:
                                if (isset($foreignKeys[$name])) {
                                    include $templatePath . 'data/formFields/dropdown.tpl.php';
                                } else {
                                    include $templatePath . 'data/formFields/integer.tpl.php';
                                }
                                break;

                            case DataManager::TYPE_FLOAT:
                            case DataManager::TYPE_MONEY:
                                include $templatePath . 'data/formFields/float.tpl.php';
                                break;

                            case DataManager::TYPE_TEXT:
                                include $templatePath . 'data/formFields/text.tpl.php';
                                break;

                            case DataManager::TYPE_TEXT_MULTI:
                                include $templatePath . 'data/formFields/multiline.tpl.php';
                                break;

                            case DataManager::TYPE_BOOL:
                                include $templatePath . 'data/formFields/boolean.tpl.php';
                                break;

                            case DataManager::TYPE_DATE:
                                include $templatePath . 'data/formFields/date.tpl.php';
                                break;

                            case DataManager::TYPE_DATETIME:
                                include $templatePath . 'data/formFields/datetime.tpl.php';
                                break;

                            case DataManager::TYPE_PASSWORD:
                                include $templatePath . 'data/formFields/password.tpl.php';
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