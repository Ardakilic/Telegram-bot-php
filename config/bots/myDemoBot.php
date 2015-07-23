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
    
    //Your app token, if you will be using SQL, this is the only key that's needed
    'token' => '',

    /**
     *
     * If you will be using file as source, the following configuration values below are also needed
     * If you will be using SQL, you can remove the following keys from config, token key is enough for SQL
     *
    */

    //Set whether the responses should be as reply or a normal message.
    //If this is set to (bool)true, Bot's reponses will be as replies
    'as_reply'  => false,

    //Enable/disable previews on the messages if there are links inside
    //If this is set to (bool)true, Bot's reponses will preview the links
    'preview_links' => false,

    'responses' => [
        'message1' => [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tristique, nisi ut semper volutpat, nulla mi malesuada eros, et maximus augue mauris vel odio",
            "Nam lacinia egestas cursus. Quisque placerat justo ut porttitor rhoncus. Nam non est vitae leo consequat vestibulum eget a nisl",
        ],

        'hodor'    => [
            'Hodor?',
            'HODOR!',
            "HODOR!\nHODOR!\nHODOR!\n",
        ],
        
        //The fallback response that's sent when none of the commands provided above are met
        'fallback_response' => 'Sorry, could you repeat that?',
    ],

];
