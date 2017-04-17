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

$pageTitle     = "Artists - Concert Tracker";
$extraIncludes = array(
    '<link href="css/custom.css" rel="stylesheet">',
);

$stmt = $dbh->prepare("INSERT INTO artist(name, genre, country) VALUES (:artist, :genre, :country)");
$stmt->bindParam(':artist', $name);
$stmt->bindParam(':genre', $genre);
$stmt->bindParam(':country', $country);

if (isset($_POST['submit'])) {
    $name    = $_POST['artist_name'];
    $genre   = $_POST['genre'];
    $country = $_POST['country'];

    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- Include the HTML head -->
<? include "htmlhead.php" ?>
<body>
<header>
    <? include "navbar.php"; ?>
</header>

<main class="container footer-spacing">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active" role="presentation"><a href="#list" role="tab"
                                                  data-toggle="tab">Artists</a>
        </li>
        <li role="presentation"><a href="#form" role="tab"
                                   data-toggle="tab">Add</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="list">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>Artist</th>
                        <th>Genre</th>
                        <th>Country</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($dbh->query("SELECT * FROM artist ORDER BY name ASC ") as $result) {
                        echo "<tr>";
                        echo "<td>" . $result['name'] . "</td>";
                        echo "<td>" . $result['genre'] . "</td>";
                        echo "<td>" . $result['country'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="form">
            <form class="container" action="" method="post">
                <h2>Add Artist</h2>
                <hr>
                <div class="form-group">
                    <label for="artist_name">Artist</label>
                    <input class="form-control" type="text" name="artist_name"
                           id="artist_name" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input class="form-control" type="text" name="genre"
                           id="genre" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input class="form-control" type="text" name="country"
                           id="country" maxlength="50" required>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary" name="submit">
                    Submit
                </button>
            </form>
        </div>
    </div>
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
