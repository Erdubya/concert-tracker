<?php
/**
 * User: Erik Wilson
 * Date: 13-Apr-17
 * Time: 21:23
 */

require_once '_functions.php';

putenv("TZ=US/Eastern");
define("MAIL_TO", "{{MAIL_TO}}");
define("ERR_MSG", "Could not connect to database! Contact <a href=\"mailto:" . MAIL_TO . "\">" . MAIL_TO . "</a> for help.");

/*
 * DATABASE CONSTANTS
 */
const HANDLER  = "{{HANDLER}}";
const HOSTNAME = "{{HOSTNAME}}";
const DATABASE = "{{DATABASE}}";
const USERNAME = "{{USERNAME}}";
const PASSWORD = "{{PASSWORD}}";
