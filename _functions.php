<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */

/** Function to connect to a database
 * Uses settings provided in _configuration.php
 * @return null|PDO
 */
function db_connect()
{
    try {
        $dbh = new PDO(HANDLER . ":host=" . HOSTNAME . ";dbname=" . DATABASE,
            USERNAME, PASSWORD);
    } catch (PDOException $e) {
        $dbh = null;
    }

    return $dbh;
}

/**
 * @param $dbh     PDO The connection to build the database in
 * @param $handler string The DB handler to create the DB for
 *
 * @return bool The success state of the database creation
 */
function build_db($dbh, $handler)
{
    if ($handler == "mysql") {
        $artist_table  = "CREATE TABLE IF NOT EXISTS artist(
						artist_id SERIAL PRIMARY KEY ,
						name VARCHAR(50) UNIQUE NOT NULL ,
						genre VARCHAR(50) NULL , 
						country VARCHAR(50) NULL
						)";
        $concert_table = "CREATE TABLE IF NOT EXISTS concert(
						concert_id SERIAL PRIMARY KEY ,
						artist BIGINT UNSIGNED NOT NULL , 
						date DATE NOT NULL , 
						city VARCHAR(30) NOT NULL , 
						attend BOOLEAN NOT NULL DEFAULT FALSE,
						notes VARCHAR(500), 
						FOREIGN KEY (artist) REFERENCES artist(artist_id),
						UNIQUE (artist, date)
						)";
    } else {
        $artist_table  = null;
        $concert_table = null;
    }
    try {
        $dbh->exec($artist_table);
        $dbh->exec($concert_table);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * checks if the program is installed, and ends the script if it is not
 */
function check_install()
{
    if ( !file_exists('config.php')) {
        die("Please run the <a href='install.php'>install script</a> set up Concert Tracker.");
    }
}

/**
 * Removes the byte order mark from a utf-8 string.
 *
 * @param $text string The string to the bom from, if it exists
 *
 * @return string The input string, minus the bom.
 */
function remove_utf8_bom($text)
{
    $bom  = pack('H*', 'EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);

    return $text;
}
