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
    
    //Your app token
    'token' => '',

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

    'commands' => [
    ]
];
