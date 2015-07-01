<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-PHP-Example-Bot
 * @version  0.2
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */

return [
    
    //Source of strings
    //if set to "file", it's fetched from configuration files directly,
    //If it's set to "sql", it'll be fetched from database from the "connection" key
    'source'        => 'file',

    //All of these should be POST routes in index.php, the app has to support be https protocol.
    //Keys are routes, values are the bots that are using that webhook
    'bots' => [
        'bothook'   => 'myDemoBot',
    ],

    //Database connection credentials
    'connection'   => [
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'port'      => 3306,
        'database'  => 'database',
        'username'  => 'dbuser',
        'password'  => 'dbpassword',
        'PDO_string' => '[DRIVER]:host=[HOST];port=[PORT];dbname=[DATABASE]',

        //Set the value as null if you don't want this
        'driver_options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'],
        
        //This will be the method that will be used in ORDER BY statement in SQL.
        'rand_method'   => 'RAND()', //E.g: RAND() for MySQL (and derivatives), NEWID() for SqlServer etc.
    ],

    'timezone' => 'Europe/Istanbul',

    'default_fallback_response' => 'Sorry, could you repeat that?'

];
