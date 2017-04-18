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
    <!-- Export Request form -->
    <form class="container panel form-upload panel-default"
          action="logic/export-csv.php"
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
    })
</script>
</body>
</html>

