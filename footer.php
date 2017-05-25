<?php
/**
 * Holds the static footer for the site
 * User: Erik Wilson
 * Date: 09-May-17
 * Time: 11:58
 */

ob_start();
?>
<footer class="panel-footer navbar-fixed-bottom">
    <div class="container">
        <p class="text-center">Built by <a href="https://github.com/Erdubya" target="_blank">Erik Wilson</a></p>
    </div>
</footer>
<?php
$footer = ob_get_clean();
