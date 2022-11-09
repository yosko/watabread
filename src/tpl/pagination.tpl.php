<?php
if (isset($pagination)) { ?>

    <nav class="pagination"><?php
    foreach($pagination as $key => $value) {
        if($value == 'current') { ?>

            <span class="action active"><?php echo $key; ?></span><?php
        } else {
            if($value == 'last') { ?>

                &hellip;<?php
            } ?>

            <a class="action" title="page <?php echo $key; ?>" href="<?php echo $paginationUrl . $key; ?>"><?php
                if($value == 'first') {
                    echo '<span class="icon">&laquo;</span>';
                } elseif($value == 'last') {
                    echo '<span class="icon">&raquo;</span>';
                } else {
                    echo $key;
                }
            ?></a><?php
        }
        if($value == 'first') { ?>

            &hellip;<?php
        }
    } ?>

    </nav><?php
}