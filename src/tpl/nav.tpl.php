<nav><?php
// actions de collection
if ($self == 'data/collection') { ?>

    <a class="action" href="<?php
        echo $breadView->modelRoute($model, 'add');
        if (!empty($queryStringForAdd)) {
            echo '?'.$queryStringForAdd;
        }
    ?>" title="Ajouter <?php echo $model; ?>"><span class="icon">➕</span></a><?php

// actions d'instance
} else { ?>

    <a class="action" href="<?php echo $breadView->modelRoute($model); ?>" title="Retour à la liste de <?php echo $model; ?>"><span class="icon">⬅️</span></a><?php

    if (isset($instance)) {
        include $pluginTplPath.'actions.tpl.php';
    }
}

$navCustomActions = array_merge($navCustomActions ?? [], $breadView->getCustomActions($model, $instance ?? null));

if (!empty($navCustomActions)) {
    foreach ($navCustomActions as $action) {
        if ($self != $action['tpl']) {
        ?>

        <a class="action"
           id="<?php echo $action['id']; ?>"
           href="<?php echo $action['url']; ?>"
           title="<?php echo $action['title']; ?>"><span class="icon"><?php echo $action['icon']; ?></span></a><?php
        }
    }
}

unset($navCustomActions);

?>

</nav>
