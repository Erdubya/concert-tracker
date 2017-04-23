<?php
/**
 * User: Erik Wilson
 * Date: 17-Apr-17
 * Time: 00:52
 */
require_once '_functions.php';
check_install();

//require the config file
require_once "config.php";

// start the session
session_start();

$pageTitle = "Export Data - Concert Tracker";
$extraIncludes = array(
    "<script src='js/bootstrap-checkbox.js' defer></script>"
);
?>
<!DOCTYPE html>
<html lang="en">
<!-- Include the HTML head -->
<?php include "htmlhead.php" ?>
<body>
<header>
    <?php include "navbar.php" ?>
</header>

<main class="container head-foot-spacing">
    <!-- Export Request form -->
    <form class="container panel form-upload panel-default"
          action="export-csv.php"
          method="post" enctype="multipart/form-data">
        <h2>Export Data</h2>
        <hr>
        <div class="form-group">
            <div class="radio">
                <label>
                    <input type="radio" name="data" value="artist" required>
                    Artist Data
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="data" value="concert">
                    Concert Data
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="bom">Include BOM</label><br>
            <input type="checkbox" id="bom" name="bom" checked data-group-cls="btn-group-sm">
        </div>
        <hr>
        <button type="submit" class="btn btn-default" name="export">
            Export
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
    // Set dynamic navbar highlighting
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

