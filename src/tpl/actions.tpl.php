
<?php if ($self != 'instance') { ?><a class="action" href="<?php echo $breadView->instanceRoute($instance); ?>" title="Détails"><span class="icon">🔍</span></a><?php } ?>

<?php if ($self != 'form') { ?><a class="action" href="<?php echo $breadView->instanceRoute($instance, 'edit'); ?>" title="Modifier"><span class="icon">✏️</span></a><?php } ?>

<?php if ($self != 'form') { ?><a class="action" href="<?php echo $breadView->instanceRoute($instance, 'copy'); ?>" title="Dupliquer"><span class="iconlite">⎘</span></a><?php } ?>

<?php if ($self != 'delete') { ?><a class="action" href="<?php echo $breadView->instanceRoute($instance, 'delete'); ?>" title="Supprimer"><span class="icon">❌</span></a><?php } ?>
