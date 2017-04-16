<?php
/**
 * User: Erik Wilson
 * Date: 16-Apr-17
 * Time: 00:16
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

$pageTitle     = "Upload Data - Concert Tracker";
$extraIncludes = array(
	'<link href="css/custom.css" rel="stylesheet">',
);

if (isset($_POST['filesubmit'])) {
	if (isset($_FILES['csvfile'])) {
		// TODO: check errors
		$file = $_FILES['csvfile']['tmp_name'];

		$csv = array_map('str_getcsv', file($file));
		array_walk($csv, function (&$a) use ($csv) {
			$a = array_combine($csv[0], $a);
		});
		array_shift($csv); # remove column header

		echo "<pre>";
		print_r($csv);
		echo "</pre>";
	} else {
		echo "NO FILE SELECTED <br>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- Include the HTML head -->
<? include "htmlhead.php" ?>
<body>
<header>
    <!-- Nav bar -->
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Navbar "Home" button -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed"
                        data-toggle="collapse" data-target="#collapsible-navbar"
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
    <form class="form-upload" action="<?php echo $_SERVER["PHP_SELF"]; ?>"
          method="post" enctype="multipart/form-data">
        <h2>Upload Data</h2>
        <hr>
        <div class="form-group">
            <div class="radio">
                <label>
                    <input type="radio" name="filetype" value="artist">
                    Artist Data
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="filetype" value="concert">
                    Concert Data
                </label>
            </div>
        </div>
        <!--        <hr>-->
        <div class="form-group">
            <label for="file-upload">Upload CSV</label>
            <input type="file" id="file-upload" name="csvfile">
        </div>
        <hr>
        <button type="submit" class="btn btn-default" name="filesubmit">Submit
        </button>
    </form>

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
