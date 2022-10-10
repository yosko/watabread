<?php
$model = $instance->getClassName();
$pageTitle = "Suppression de ".$instance->getTitle();
include $templatePath.'general/header.tpl.php';
?>

        <header>
            <h2><?php echo $pageTitle; ?></h2>
            <?php include $pluginTplPath.'nav.tpl.php'; ?>
        </header>
        <form method="post" action="">
            <input type="hidden" name="id" id="id" value="<?php echo $instance->id; ?>">
            <input type="submit" name="submitDelete" id="submitDelete" value="Confirmer la suppression" />
        </form>

<?php include $templatePath.'general/footer.tpl.php'; ?>