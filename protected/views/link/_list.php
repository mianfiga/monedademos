
<?php if ($edit || count($links) > 0) { ?>
    <div class="social_box">
        <ul class="">
            <?php
            foreach ($links as $link) {
                echo '<li class="social ' . $link->logo . '"><a rel="nofollow" href="' . $link->url . '">' . $link->text . '</a>' .
                '</li>';
            }
            ?>
            <li>ADD LINK</li>
        </ul>
    </div>
<?php } ?>

