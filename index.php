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

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Concert Tracker</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- jQueryUI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </head>
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
                            <li><a href="#">Artists <span class="sr-only">(current)</span></a></li>
                            <li><a href="#">Concerts <span class="sr-only">(current)</span></a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    
        <main>
            <!-- TODO: TABLES TABLES TABLES -->
        </main>
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
