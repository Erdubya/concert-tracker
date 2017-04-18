<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:47
 * Installation script for concert-tracker.  My first attempt at writing one,
 * so it's probably not that great.
 */
require_once '_functions.php';
check_install();

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

    echo build_db($dbh);

	header('Location: index.php');
}
?>
<html>
<head>
    <title>Concert Tracker Installer</title>
    <script></script>
</head>
<body>
<h1>Concert Tracker Installer</h1>
<form method="post">
    <h2>Database</h2>
    <div id="manager">
        Database Manager: <br>
        <label for="mysql">MySQL</label>
        <input id="mysql" type="radio" name="dbms" value="mysql" checked/><br>
        <label for="pgsql">Postgres</label>
        <input id="pgsql" type="radio" name="dbms" value="pgsql" disabled/>
        <!--TODO: implement PostgreSQL support-->
    </div>
    <br>
    <div id="networkdb">
        <label for="hostname">Hostname:</label>
        <input id="hostname" type="text" name="hostname"/><br>
        <label for="dbname">Database:</label>
        <input id="dbname" type="text" name="database"/><br>
        <label for="username">Username:</label>
        <input id="username" type="text" name="username"/><br>
        <label for="password">Password:</label>
        <input id="password" type="password" name="password"/><br>
    </div>
    <h2>Administration</h2>
    <div id="admin">
        <label for="email">Mail To:</label>
        <input id="email" type="email" name="mailto"/><br>
    </div>
    <br>
    <input type="submit" value="Install" name="install"/>
</form>
</body>
</html>
