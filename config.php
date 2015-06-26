<?php

/**
 * Telegram-PHP-Bot - A Simple PHP app for Telegram Bots
 *
 * @package  Telegram-PHP-Example-Bot
 * @version  0.1
 * @author   Arda Kilicdagi <arda@kilicdagi.com>
 * @link     http://arda.pw
 */


$config = [

    'tokens' => [
        'MyFirstBot' => '',
        'MySecondBot' => 'HashYadaYada',
    ],

    //All of these should be POST routes in index.php, the app has to support be https protocol.
    //Keys are routes, values are the bots that are using that webhook
    'webhook_urls' => [
        'hook1' => 'MyFirstBot',
        'hook2' => 'MySecondBot'
    ],

    'timezone' => 'Europe/Istanbul',

    //Set whether the responses should be as reply or a normal message.
    //If this is set to (bool)true, Bot's reponses will be as replies
    'as_reply'	=> false,

    //Enable/disable previews on the messages if there are links inside
    //If this is set to (bool)true, Bot's reponses will preview the links
    'preview_links' => false,

    'responses' => [
        //Here, each botname should have their own keys, and for each pattern they should have a value as array
        'MyFirstBot' => [
            'command1' => [ //This will be like this: "@MyFirstBot please tell me command1" or "@MiyFirstBot command1" directly
                "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce a diam ullamcorper, semper nulla eget, malesuada nibh. Fusce interdum, leo vel tincidunt pellentesque, nunc odio facilisis nulla, sit amet volutpat nisl tellus in sem. Ut dapibus ante sit amet efficitur sodales.caksın Serayı mis. Onun da kurulumu maliyetli ama getirisi şukella.",
                "Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Ut semper rhoncus libero vel lobortis. Sed a pharetra leo. Nulla eget ullamcorper massa",
                'HODOR',
                "Suspendisse blandit blandit enim, non gravida turpis iaculis sit amet. Duis venenatis semper quam eu consectetur. ",
            ],
            'command2' => ['Pellentesque ut mauris dolor. Sed tortor augue, sollicitudin vitae turpis at, efficitur faucibus magna. Morbi orci massa, feugiat a diam ut, aliquam eleifend lacus'],
        ],
        'MySecondBot' => [
            'command1' => [
                "Ut lectus massa, pellentesque quis velit in, viverra sagittis velit. Sed id fermentum erat, ac blandit est. Donec dictum mollis eros, in hendrerit mi porta et.",
            ]
        ],
        //The fallback response that's sent when none of the commands provided above are met
        'fallback_response' => 'Sorry, could you repeat that?',
    ]
];