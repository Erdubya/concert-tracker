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
include "class/Encoding.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

$pageTitle = "Upload Data - Concert Tracker";

use ForceUTF8\Encoding;

if (isset($_POST['filesubmit'])) {
    if (isset($_FILES['csvfile'])) {
        // TODO: check errors
        $file = $_FILES['csvfile']['tmp_name'];

        // Generate a 2D array from the CSV file
        $csv = [];
        foreach (file($file) as $line) {
            $csv[] = str_getcsv(Encoding::toUTF8($line));
        }
        if (isset($_POST['headers'])) {
            array_shift($csv); //Drop headers
        }

        // Convert that array into an SQL insert statement
        $sql = [];
        if ($_POST['filetype'] == "artist") {
            foreach ($csv as $row => $line) {
                $name      = $line[0];
                $genre     = $line[1];
                $country   = $line[2];
                $sql[$row] = "(\"$name\", \"$genre\", \"$country\")";
            }
            $sql = "INSERT INTO artist(name, genre, country) VALUES "
                   . implode(", ", $sql);
            unset($_POST);
            header('Location: artists.php');
        } elseif ($_POST['filetype'] == "concert") {
            // TODO: does not work, needs foreign key handling
//            foreach ($csv as $row => $line) {
//                $artist    = $line[0];
//                $date      = date_parse($line[1]);
//                $attended  = $line[2];
//                $notes     = $line[3];
//                $sql[$row] = "(\"$artist\", $date, $attended, \"$notes\")";
//            }
//            $sql = "INSERT INTO concert(artist, date, city, notes) VALUES "
//                   . implode(", ", $sql);
//            unset($_POST);
//            header('Location: concerts.php');
        }
        $dbh->exec($sql);
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
    <? include "navbar.php" ?>
</header>

<main class="container head-foot-spacing">
    <form class="container panel form-upload panel-default"
          action="<?php echo $_SERVER["PHP_SELF"]; ?>"
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
        <div class="form-group">
            <label>
                <input type="checkbox" value="" name="headers">
                My data has headers
            </label>
        </div>
        <hr>
        <button type="submit" class="btn btn-default" name="filesubmit">
            Submit
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
        if (pathname !== "") {
            $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
        }
    })
</script>
</body>
</html>
