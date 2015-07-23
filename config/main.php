<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-Bot-PHP
 * @version  1.0
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */

return [

    //Source of strings
    //if set to "file", bot responses are fetched from configuration files under config/bots directly,
    //If set to "mysql", it'll be fetched from database from the "connection" key
    //Feel free to duplicate with your own DB driver.
    //Note: Idiorm ( https://github.com/j4mie/idiorm ) should support your connection type.
    'source' => 'mysql',

    //All of these should be POST routes in index.php, the app has to support be https protocol.
    //Keys are routes, values are the bots that are using that webhook
    'bots' => [
        'bothook' => 'myDemoBot',
    ],

    //Database connection credentials
    'connection' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'database',
            'username' => 'user',
            'password' => 'password',
            'PDOString' => '[DRIVER]:host=[HOST];port=[PORT];dbname=[DATABASE]',

            //Set the value as null if you don't want this
            'driver_options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'],

            //This will be the method that will be used in ORDER BY statement in SQL.
            'rand_method' => 'RAND()', //E.g: RAND() for MySQL (and derivatives), NEWID() for SqlServer etc.

            //The migration query that creates the table when you run "php migration.php" from terminal.
            'migration' => "CREATE TABLE responses (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                bot_name VARCHAR(100) NOT NULL,
                pattern VARCHAR(100) NOT NULL,
                response_type ENUM('text', 'image', 'sticker', 'video', 'audio'),
                response_data TEXT,
                as_quote ENUM('y', 'n') NOT NULL DEFAULT 'n',
                preview_links_if_any ENUM('y', 'n') NOT NULL DEFAULT 'n',

                PRIMARY KEY (id),
                INDEX (bot_name, pattern)

                ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;",
        ],

    ],

    //The timezone setting, Guzzle suggests having this for proper requests/responses
    'timezone' => 'Europe/Istanbul',

    //If no response is found, this will be used as response
    'default_fallback_response' => 'Sorry, could you repeat that?',

];
