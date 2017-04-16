<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */
// check if installed
if (!file_exists('config.php')) {
    die("Please run the <a href='install.php'>install script</a> set up Concert Tracker.");
}

require_once "config.php";

session_start();
$dbh = db_connect() or die(ERR_MSG);

$pagetitle = "Concert Tracker";

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <? include "head.php" ?>
    <body>
        <header>
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsible-navbar" aria-expanded="false">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Concert Tracker</a>
                    </div>
                    
                    <div class="collapse navbar-collapse" id="collapsible-navbar">
                        <ul class="nav navbar-nav">
                            <li><a href="#">Artists</a></li>
                            <li><a href="#">Concerts</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#">Upload Data</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    
        <main class="container">
            <!-- TODO: Home page -->
            <!-- Upcoming? -->
        </main>
    
        <footer class="panel-footer navbar-fixed-bottom">
            <div class="container">
                <p>Built by Erik Wilson</p>
            </div>
        </footer>
    </body>
    <script>
        $(".nav li").on("click", function() {
            $(".nav li").removeClass("active");
            $(this).addClass("active");
        });
        
        $(".navbar-header a").on("click", function() {
            $(".nav li").removeClass("active");
        });

    </script>
    </html>
<?php
ob_end_flush();
