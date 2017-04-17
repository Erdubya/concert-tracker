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

$pageTitle = "Concerts - Concert Tracker";

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
    <ul class="nav nav-tabs" role="tablist">
        <li class="active" role="presentation"><a href="#panel1" role="tab"
                                                  data-toggle="tab">Concerts</a>
        </li>
        <li role="presentation"><a href="#panel2" role="tab"
                                   data-toggle="tab">Add</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="panel1">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Artist</th>
                        <th>City</th>
                        <th>Attend</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($dbh->query("SELECT a.name, c.date, c.city, c.notes, c.attend FROM concert AS c, artist AS a WHERE a.artist_id = c.artist ORDER BY c.date DESC ") as $key => $result) {
                        $current_date = date("Y-m-d");
                        if ($result['date'] <= $current_date) {
                            echo "<tr style='background-color: #f9f9f9;'>";
                        } else {
                            echo "<tr>";
                        }
                        echo "<td>" . $result['date'] . "</td>";
                        echo "<td>" . $result['name'] . "</td>";
                        echo "<td>" . $result['city'] . "</td>";
                        echo "<td>";
                        if ($result['attend']) {
                            echo "<span class='glyphicon glyphicon-ok-sign'>";
                        } else {
                            echo "<span class='glyphicon glyphicon-remove-sign'>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="panel2">
            <form class="container" action="concerts-add.php" method="post">
                <h2>Add Concert</h2>
                <hr>
                <div class="form-group">
                    <label for="artist_id">Artist</label>
                    <select id="artist_id" class="form-control" name="artist_id"
                            required>
                        <option readonly selected>Select an Artist</option>
                        <?php
                        foreach ($dbh->query("SELECT artist_id, name FROM artist ORDER BY name ASC ") as $result) {
                            echo "<option value='" . $result['artist_id'] . "'>" . $result['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input class="form-control" type="date" name="date"
                           id="date" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input class="form-control" type="text" name="city"
                           id="city" maxlength="30" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="attend">
                        I'm going!
                    </label>
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
        // get current URL path and assign 'active' class to navbar
        var pathname = new URL(window.location.href).pathname.split('/').pop();
        if (pathname !== "") {
            $('.nav > li > a[href="' + pathname + '"]').parent().addClass('active');
        }
    })
</script>
</body>
</html>
