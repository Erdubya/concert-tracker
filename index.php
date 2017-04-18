<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */
require_once '_functions.php';
check_install();

//require the config file
require_once "config.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

$pageTitle = "Concert Tracker";

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <!-- Include the HTML head -->
    <? include "htmlhead.php" ?>
    <body>
    <header>
        <? include "navbar.php" ?>
    </header>

    <main class="container head-foot-spacing">
        <!-- TODO: Home page -->
        <!-- Upcoming? -->
    </main>

    <!-- Simple footer -->
    <footer class="panel-footer navbar-fixed-bottom">
        <div class="container">
            <p class="text-center">Built by Erik Wilson</p>
        </div>
    </footer>

    <script>
        // Handle navbar dynamic highlighting
        $(document).ready(function () {
            // get current URL path and assign 'active' class to navbar
            var pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }
        })
    </script>
    </body>
    </html>
<?php
ob_end_flush();
