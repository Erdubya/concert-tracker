<?php
/**
 * User: Erik Wilson
 * Date: 16-Apr-17
 * Time: 00:49
 */
// check if program is installed
if ( !file_exists('config.php')) {
    die("Please run the <a href='install.php'>install script</a> set up Concert Tracker.");
}

//require the config file
require_once "config.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

$pageTitle = "Upload Data - Concert Tracker";
?>
<!DOCTYPE html>
<html lang="en">
<!-- Include the HTML head -->
<? include "htmlhead.php" ?>
<body>
<header>
    <? include "navbar.php" ?>
</header>

<main class="container">

</main>

<!-- Simple footer -->
<footer class="panel-footer navbar-fixed-bottom">
    <div class="container">
        <p class="text-center">Built by Erik Wilson</p>
    </div>
</footer>
<script>
    $(document).ready(function () {
        // get current URL path and assign 'active' class
        var pathname = new URL(window.location.href).pathname.split('/').pop();
        if (pathname != "") {
            $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
        }
    })
</script>
</body>
</html>
