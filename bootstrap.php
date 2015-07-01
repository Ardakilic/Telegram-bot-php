<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-PHP-Example-Bot
 * @version  0.2
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */


//Show all errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

$config = require __DIR__ . '/config/main.php';

date_default_timezone_set($config['timezone']);

//This is needed for ORM connection
require_once __DIR__ . '/vendor/autoload.php';

//Now if set, let's connect to the database
if($config['source'] == 'sql') {

    ORM::configure([
        'connection_string' => str_replace(['[DRIVER]', '[HOST]', '[PORT]', '[DATABASE]'],[$config['connection']['driver'], $config['connection']['host'], $config['connection']['port'], $config['connection']['database']], $config['connection']['PDO_string']),
        'username'  => $config['connection']['username'],
        'password'  => $config['connection']['password'],
        'driver_options'    => $config['connection']['driver_options'],
    ]);

}
