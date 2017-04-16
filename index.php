<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
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

$pageTitle = "Concert Tracker";

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <!-- Include the HTML head -->
    <? include "htmlhead.php" ?>
    <body>
    <header>
        <!-- Nav bar -->
        <nav class="navbar navbar-inverse">
            <div class="container">
                <!-- Navbar "Home" button -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed"
                            data-toggle="collapse"
                            data-target="#collapsible-navbar"
                            aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/concert-tracker/">Concert
                        Tracker</a>
                </div>

                <!-- Other navbar buttons -->
                <div class="collapse navbar-collapse" id="collapsible-navbar">
                    <ul class="nav navbar-nav">
                        <li><a href="artists.php">Artists</a></li>
                        <li><a href="concerts.php">Concerts</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="upload.php">Upload Data</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
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
<?php
ob_end_flush();
