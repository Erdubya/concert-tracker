<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */

require_once '_functions.php';

putenv("TZ=US/Eastern");
define("MAIL_TO", 'erikwilson@outlook.com');
define("ERR_MSG", "Could not connect to database! Contact <a href=\"mailto:" . MAIL_TO . "\">" . MAIL_TO . "</a> for help.");

/*
 * DATABASE CONSTANTS
 */
const HANDLER  = 'mysql';
const HOSTNAME = 'localhost';
const DATABASE = 'concerts';
const USERNAME = 'concerts';
const PASSWORD = 'testpass';
