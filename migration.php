#!/usr/bin/env php
<?php


/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-PHP-Example-Bot
 * @version  0.2
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     https://arda.pw
 */


require_once __DIR__ . '/bootstrap.php';

$header = "  _____    _                                      _           _        ____  _   _ ____  
 |_   _|__| | ___  __ _ _ __ __ _ _ __ ___       | |__   ___ | |_     |  _ \| | | |  _ \ 
   | |/ _ \ |/ _ \/ _` | '__/ _` | '_ ` _ \ _____| '_ \ / _ \| __|____| |_) | |_| | |_) |
   | |  __/ |  __/ (_| | | | (_| | | | | | |_____| |_) | (_) | ||_____|  __/|  _  |  __/ 
   |_|\___|_|\___|\__, |_|  \__,_|_| |_| |_|     |_.__/ \___/ \__|    |_|   |_| |_|_|    
                  |___/                                                                  
-----------------------------------------------------------------------------------------";

echo $header.PHP_EOL;

writeColor('green', 'This command installs the required migration file for reponses');

echo "Are you sure you want to install the migration? Type 'yes' to continue: ";

//Let's read the user input
//$handle = fopen ("php://stdin","r");
//$userInput = fgets($handle);
$userInput = fgets(STDIN);
if(trim($userInput) !== 'yes'){
    echo PHP_EOL."Bye!".PHP_EOL;
    exit;
}

//Now let's try to create the table
try {

    $db = ORM::get_db();
    //TODO cross platform migration files, this may come from a configuration value maybe.
    $db->exec("CREATE TABLE responses (
          
          id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          bot_name VARCHAR(100) NOT NULL, 
          pattern VARCHAR(100) NOT NULL UNIQUE,
          response_text TEXT,
          response_image VARCHAR(400),
          as_quote ENUM('y', 'n') NOT NULL DEFAULT 'n',
          preview_links_if_any ENUM('y', 'n') NOT NULL DEFAULT 'n',
          
          PRIMARY KEY (id),
          INDEX (bot_name, pattern)

        ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;");

    writeColor('green', 'Database table created successfully!');

} catch(PDOException $e) {

    writeColor('red', 'An error has been occurred:');
    writeColor('red', 'Error '.$e->getCode().': '.$e->getMessage());

}


//This helper will only be needed here, 
//So until more of these are needed, I've decided to keep this here for now

/**
 * Writes given message in specified color
 * Taken From Scabbla2 PHP Framework
 * https://github.com/scabbiafw/scabbia2-fw/
 *
 * @param string $uColor   color
 * @param string $uMessage message
 *
 * @return void
 */
function writeColor($uColor, $uMessage)
{
    if (strncasecmp(PHP_OS, "WIN", 3) === 0) {
        echo $uMessage, PHP_EOL;
        return;
    }

    if ($uColor === "black") {
        $tColor = "[0;30m";
    } elseif ($uColor === "darkgray") {
        $tColor = "[1;30m";
    } elseif ($uColor === "blue") {
        $tColor = "[0;34m";
    } elseif ($uColor === "lightblue") {
        $tColor = "[1;34m";
    } elseif ($uColor === "green") {
        $tColor = "[0;32m";
    } elseif ($uColor === "lightgreen") {
        $tColor = "[1;32m";
    } elseif ($uColor === "cyan") {
        $tColor = "[0;36m";
    } elseif ($uColor === "lightcyan") {
        $tColor = "[1;36m";
    } elseif ($uColor === "red") {
        $tColor = "[0;31m";
    } elseif ($uColor === "lightred") {
        $tColor = "[1;31m";
    } elseif ($uColor === "purple") {
        $tColor = "[0;35m";
    } elseif ($uColor === "lightpurple") {
        $tColor = "[1;35m";
    } elseif ($uColor === "brown") {
        $tColor = "[0;33m";
    } elseif ($uColor === "yellow") {
        $tColor = "[1;33m";
    } elseif ($uColor === "white") {
        $tColor = "[1;37m";
    } else /* if ($uColor === "lightgray") */ {
        $tColor = "[0;37m";
    }

    echo "\033", $tColor, $uMessage, "\033[0m", PHP_EOL;
}
