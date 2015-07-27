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

        //Example connection and schema for MySQL
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
            'rand_method' => 'RAND()', //E.g: RAND() for MySQL (and derivatives), RANDOM() for PSQL, NEWID() for SqlServer etc.

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

        //Example connection and schema for Postgres
        'psgql' => [
            'driver' => 'pgsql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'database',
            'username' => 'user',
            'password' => 'password',
            'PDOString' => '[DRIVER]:host=[HOST];port=[PORT];dbname=[DATABASE]',

            //Set the value as null if you don't want this
            'driver_options' => null, //Postgres comes with UTF-8 as default 

            //This will be the method that will be used in ORDER BY statement in SQL.
            'rand_method' => 'RANDOM()', //E.g: RAND() for MySQL (and derivatives), RANDOM() for PSQL, NEWID() for SqlServer etc.

            //The migration query that creates the table when you run "php migration.php" from terminal.
            'migration' => "CREATE TYPE responses_response_type AS ENUM ('text','image','sticker','video','audio'); 
                CREATE TYPE responses_as_quote AS ENUM ('y','n'); 
                CREATE TYPE responses_preview_links_if_any AS ENUM ('y','n'); 
                CREATE TABLE responses (
                    id integer  NOT NULL,
                    bot_name varchar(200) NOT NULL,
                    pattern varchar(200) NOT NULL,
                    response_type responses_response_type DEFAULT NULL,
                    response_data text ,
                    as_quote responses_as_quote NOT NULL DEFAULT 'n',
                    preview_links_if_any responses_preview_links_if_any NOT NULL DEFAULT 'n',
                    PRIMARY KEY (id)
                );",
        ],

    ],

    //The timezone setting, Guzzle suggests having this for proper requests/responses
    'timezone' => 'Europe/Istanbul',

    //If no response is found, this will be used as response
    'default_fallback_response' => 'Sorry, could you repeat that?',

];
