# Telegram-bot-php

This app is a very simple Telegram PHP API for newly announced [Telegram Bots](https://telegram.org/blog/bot-revolution).

![Preview](http://i.imgur.com/gVsY6zB.png)

###Features

 * By installing this app, you can have a working Telegram Bot within minutes!
 * This app only has two routes (which one is to register the webhook, another is to listen the webhook) in an `index.php` file, and a configuration file.
 * This app can handle multiple accounts from same route. Different hooks will be different route parameters. I'm already hosting two different bots with the same application.
 * There is a single `config.php` file which holds the API keys, auto reply strings and patterns.
 * Bot works on both private chats and groups.
 * The bot can either quote or send the response text directly.
 * You can enable or disable preview links in Bots' responses.
 * For now, the Bots can only send a random string from an array of each predefined text patterns, or else the fallback string.

###Requirements

 * PHP5.5+ (for Guzzle)
 * An SSL certificate (Telegram API requires this). You can use [Cloudflare's Free Flexible SSL](https://www.cloudflare.com/ssl) which crypts the web traffic from end user to their proxies if you're using CloudFlare DNS.
 * Telegram API key, you can get one simply with [@BotFather](https://core.telegram.org/bots#botfather) with simple commands right after creating your bot.

###Installation

 * Copy the app into your (virtual)server
 * Cd into the app's directory
 * Run `composer install`
 * Edit the `config.php` file with your credentials, and API keys
 * Run your app.

###Thanks

  * [Silex, the micro PHP framework](http://silex.sensiolabs.org/)
  * [Guzzle](http://guzzlephp.org/)

###TODO
 * SQL support (already working on this). Messages etc. will be fetched from database.
 * Better search algorithm for provided parameters
 * Sending sticker feature
 * More complex tasks (like fetching data from 3rd party services such as Trakt etc.)
