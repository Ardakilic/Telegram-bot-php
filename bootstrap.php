<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-Bot-PHP
 * @version  1.0
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */


//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

$config = require __DIR__ . '/config/main.php';

//This is suggested from Guzzle
date_default_timezone_set($config['timezone']);

//This is needed for ORM connection
require_once __DIR__ . '/vendor/autoload.php';

//Now if driver is not "file", let's connect to the database
if ($config['source'] != 'file') {

    $dbDriver = $config['connection'][$config['source']];

    //Let's connect to database
    ORM::configure([
        'connection_string' => str_replace(['[DRIVER]', '[HOST]', '[PORT]', '[DATABASE]'], [$dbDriver['driver'], $dbDriver['host'], $dbDriver['port'], $dbDriver['database']], $dbDriver['PDOString']),
        'username' => $dbDriver['username'],
        'password' => $dbDriver['password'],
        'driver_options' => $dbDriver['driver_options'],
    ]);

    //Let's unset the driver variable
    unset($dbDriver);

}
