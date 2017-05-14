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
    // get the correct SQL code
    if ($handler == "mysql") {
        $tables = mysql_tables();
    } elseif ($handler == "pgsql") {
        $tables = pgsql_tables();
    } else {
        $tables  = null;
    }
    
    // attempt to execute the queries
    try {
        $dbh->exec($tables['user']);
        $dbh->exec($tables['artist']);
        $dbh->exec($tables['concert']);
        $dbh->exec($tables['token']);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Generates the SQL needed to create the database tables in MySQL.
 * 
 * @return array An array of SQL strings for creating tables.
 */
function mysql_tables() {
    unset($tables);
    
    $tables['user']    = "CREATE TABLE IF NOT EXISTS users(
                          user_id INT UNSIGNED AUTO_INCREMENT,
                          email VARCHAR(30) UNIQUE NOT NULL ,
                          passwd VARCHAR(255) NOT NULL ,
                          name VARCHAR(50) NOT NULL ,
                          PRIMARY KEY (user_id)
                          )";
    $tables['artist']  = "CREATE TABLE IF NOT EXISTS artist(
						  artist_id INT UNSIGNED AUTO_INCREMENT ,
						  user_id INT UNSIGNED NOT NULL ,
						  name VARCHAR(50) UNIQUE NOT NULL ,
						  genre VARCHAR(50) NULL , 
						  country VARCHAR(50) NULL,
						  PRIMARY KEY (artist_id)
						  )";
    $tables['concert'] = "CREATE TABLE IF NOT EXISTS concert(
						  concert_id INT UNSIGNED AUTO_INCREMENT ,
						  artist_id INT UNSIGNED NOT NULL , 
						  date DATE NOT NULL , 
						  city VARCHAR(30) NOT NULL , 
						  attend BOOLEAN NOT NULL DEFAULT FALSE,
						  notes VARCHAR(500), 
						  PRIMARY KEY (concert_id) ,
						  FOREIGN KEY (artist_id) REFERENCES artist(artist_id),
						  UNIQUE (artist_id, date)
						  )";
    $tables['token']   = "CREATE TABLE IF NOT EXISTS auth_token(
                          token_id INT UNSIGNED AUTO_INCREMENT ,
                          selector CHAR(12) UNIQUE NOT NULL ,
                          token CHAR(64) NOT NULL ,
                          user_id INT UNSIGNED NOT NULL ,
                          expires DATETIME NOT NULL ,
                          PRIMARY KEY (token_id) ,
                          FOREIGN KEY (user_id) REFERENCES users(user_id)
                          )";
    
    return $tables;
}

/**
 * Generates the SQL needed to create the database tables in PostgreSQL.
 * 
 * @return array An array of SQL strings for creating tables.
 */
function pgsql_tables() {
    unset($tables);
    $tables['user']    = "CREATE TABLE IF NOT EXISTS users(
                          user_id SERIAL ,
                          email VARCHAR(30) UNIQUE NOT NULL ,
                          passwd VARCHAR(255) NOT NULL ,
                          name VARCHAR(50) NOT NULL ,
                          PRIMARY KEY (user_id)
                          )";
    $tables['artist']  = "CREATE TABLE IF NOT EXISTS artist(
						  artist_id SERIAL ,
						  user_id INT NOT NULL ,
						  name VARCHAR(50) UNIQUE NOT NULL ,
						  genre VARCHAR(50) NULL , 
						  country VARCHAR(50) NULL ,
						  PRIMARY KEY (artist_id)
						  )";
    $tables['concert'] = "CREATE TABLE IF NOT EXISTS concert(
						  concert_id SERIAL ,
						  artist_id INT NOT NULL , 
						  date DATE NOT NULL , 
						  city VARCHAR(30) NOT NULL , 
						  attend BOOLEAN NOT NULL DEFAULT FALSE,
						  notes VARCHAR(500) ,
						  PRIMARY KEY (concert_id) ,
						  FOREIGN KEY (artist_id) REFERENCES artist(artist_id),
						  UNIQUE (artist_id, date)
						  )";
    $tables['token']   = "CREATE TABLE IF NOT EXISTS auth_token(
                          token_id SERIAL ,
                          selector CHAR(12) UNIQUE NOT NULL ,
                          token CHAR(64) NOT NULL ,
                          user_id INT NOT NULL ,
                          expires DATETIME NOT NULL ,
                          PRIMARY KEY (token_id) ,
                          FOREIGN KEY (user_id) REFERENCES users(user_id)
                          )";
    
    return $tables;
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

/**
 * @param $dbh PDO The database connector
 *
 * @return bool True if the cookie validates, false otherwise.
 */
function cookie_loader($dbh) {
    if (isset($_COOKIE['uid'])) {
        $value = $_COOKIE['uid'];
        
        list($selector, $token) = explode(":", $value); 
//        var_dump($selector);
//        var_dump($token);
        
        $stmt = $dbh->prepare("SELECT token, auth_token.user_id, expires, name FROM auth_token, users WHERE selector = :selector AND auth_token.user_id = users.user_id");
        $stmt->bindParam(":selector", $selector);
        $stmt->execute();
        
        $result = $stmt->fetch();
//        var_dump($result);
        
        if (hash_equals($result['token'], hash("sha256", $token)) && strtotime($result['expires']) >= time()) {
            $_SESSION['user'] = $result['user_id'];
            $_SESSION['username'] = $result['name'];
            return true;
        } else {
            return false;
        }
        
    } else {
        return false;
    }
}

/**
 * @param int $length The length of the token to generate
 *
 * @return string The generated token
 */
function gen_token($length = 20) {
    return bin2hex(random_bytes($length));
}
