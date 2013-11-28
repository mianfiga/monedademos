AAAAAA<br/>AAAAAA
<?php if ($edit || count($links) > 0) { ?>

    <ul class="inline-list">
        <?php
        foreach ($links as $link) {
            if ($link->active) {
                echo '<li class="' . $link->logo . '"><a rel="nofollow" href="' . $link->url . '">' . $link->text . '</a>'. 'EDIT'.
                        '</li>';
            }
        }
        ?>
        <li>ADD LINK</li>
    </ul>
<?php } ?>

