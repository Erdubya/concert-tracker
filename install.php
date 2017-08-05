<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:47
 * Installation script for concert-tracker.  My first attempt at writing one,
 * so it's probably not that great.
 */
$pageTitle = "Concert Tracker Install";

// check if it's already been run
if (file_exists("config.php")) {
    echo "<title>" . $pageTitle . "</title>";
    echo "Concert Tracker has already been installed. Delete config.php to reinstall.<br><br>";
    die("<a href='index.php'>Go back</a>");
}

require_once "_functions.php";

// run the config
if (isset($_POST['install'])) {
    // Store POST data for easier access
    $dbms     = $_POST['dbms'];
    $hostname = $_POST['hostname'];
    $database = $_POST['database'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $mail_to  = $_POST['mailto'];

    // Write the file
    $file   = file_get_contents('configdefault.php');
    $config = str_replace(
        array(
            '{{MAIL_TO}}',
            '{{HANDLER}}',
            '{{HOSTNAME}}',
            '{{DATABASE}}',
            '{{USERNAME}}',
            '{{PASSWORD}}'
        ),
        array(
            $mail_to,
            $dbms,
            $hostname,
            $database,
            $username,
            $password
        ),
        $file);
    file_put_contents('config.php', $config);

    // Test DB connection
    require_once "config.php";
    $dbh = db_connect() or die(ERR_MSG);

    // build database and handle errors
    if (!build_db($dbh, $dbms)) {
        unlink('config.php');
        unset($_POST);
        header('Location: install.php');
    }

    header('Location: index.php');
}
?>
<html>
<head>
    <?php include "htmlhead.php" ?>
</head>
<body>
<div class="container">
    <h1>Concert Tracker Installer</h1>
    <form method="post">
        <h2>Database</h2>
        <div id="manager" class="form-group">
            <div class="radio">
                <label for="mysql">
                    <input id="mysql" type="radio" name="dbms" value="mysql"
                           checked/>
                    MySQL
                </label>
            </div>
            <div class="radio">
                <label for="pgsql">
                    <input id="pgsql" type="radio" name="dbms" value="pgsql"/>
                    Postgres
                </label>
            </div>
            <!--TODO: implement SQLite support?-->
        </div>
        <br>
        <div class="form-group">
            <label for="hostname">Hostname:</label><br>
            <input id="hostname" type="text" name="hostname"
                   class="form-control" required value="localhost"/>
            <label for="dbname">Database:</label>
            <input id="dbname" type="text" name="database"
                   class="form-control" required/>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input id="username" type="text" name="username"
                   class="form-control" required/>
            <label for="password">Password:</label>
            <input id="password" type="password" name="password"
                   class="form-control" required/>
        </div>
        <h2>Administration</h2>
        <div id="admin" class="form-group">
            <label for="email">Mail To:</label>
            <input id="email" type="email" name="mailto" class="form-control"/>
        </div>
        <br>
        <button type="submit" name="install" class="btn btn-default">Install</button>
    </form>
</div>
</body>
</html>
