<?php
/**
 * User: Erik Wilson
 * Date: 16-Apr-17
 * Time: 00:16
 */
require_once '_functions.php';
check_install();

//require the config file
require_once "config.php";

// start the session and connect to DB
session_start();
$dbh = db_connect() or die(ERR_MSG);

cookie_loader($dbh);

// redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
} else {
    $userid = $_SESSION['user'];
}

$pageTitle     = "Import Data - Concert Tracker";
$extraIncludes = array(
    "<script src='js/bootstrap-checkbox.js' defer></script>"
);

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <!-- Include the HTML head -->
    <?php include "htmlhead.php" ?>
    <body>
    <header>
        <?php
        include "navbar.php";
        echo $navbar;
        ?>
    </header>

    <main class="container head-foot-spacing">
        <!-- Import from file form -->
        <form class="container panel form-upload panel-default"
              action="import-csv.php"
              method="post" enctype="multipart/form-data">
            <h2>Import Data</h2>
            <hr>
            <div class="form-group">
                <label>Data Type</label>
                <div class="radio">
                    <label>
                        <input type="radio" name="filetype" value="artist"
                               required>
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
            <div class="form-group">
                <label for="file-upload">Upload CSV</label>
                <input type="file" id="file-upload" name="csvfile" required>
            </div>
            <div class="form-group">
                <label for="headers">Headers</label><br>
                <input id="headers" type="checkbox" name="headers"
                       data-group-cls="btn-group-sm">
            </div>
            <hr>
            <button type="submit" class="btn btn-default" name="filesubmit">
                Submit
            </button>
        </form>
    </main>

    <!-- Simple footer -->
    <?php
    include 'footer.php';
    echo $footer;
    ?>

    <script>
        // Handle dynamic navbar highlighting
        $(document).ready(function () {
            // get current URL path and assign 'active' class to navbar
            var pathname = new URL(window.location.href).pathname.split('/').pop();
            if (pathname !== "") {
                $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
            }

            $(':checkbox').checkboxpicker();
        })
    </script>
    </body>
    </html>
<?php
ob_end_flush();
