# Telegram-bot-php

This app is a very simple Telegram PHP API for newly announced [Telegram Bots](https://telegram.org/blog/bot-revolution).

![Preview](http://i.imgur.com/gVsY6zB.png)

Features
---------

* By installing this app, you can have a working Telegram Bot within minutes!
* This app only has two routes (which one is to register the webhook, another is to listen the webhook) in an `index.php` file, and a configuration file.
* This app can handle multiple accounts from same route. Different hooks will be different route parameters. I'm already hosting two different bots with the same application.
* There is a single `config.php` file which holds the API keys, auto reply strings and patterns.
* Bot works on both private chats and groups.
* The bot can either quote or send the response text directly.
* You can enable or disable preview links in Bots' responses.
* For now, the Bots can only send a random string from an array of each predefined text patterns, or else the fallback string.

Requirements
---------

* PHP5.5+ (for Guzzle)
* Curl for PHP5 must be enabled to use Guzzle.
* PHP5-PDO libraries (not now, but will be needed in future versions).
* An SSL certificate (Telegram API requires this). You can use [Cloudflare's Free Flexible SSL](https://www.cloudflare.com/ssl) which crypts the web traffic from end user to their proxies if you're using CloudFlare DNS.
* Telegram API key, you can get one simply with [@BotFather](https://core.telegram.org/bots#botfather) with simple commands right after creating your bot.

Installation
---------

* Copy the app into your (virtual)server
* Cd into the app's directory
* Run `composer install`
* Edit the `config/main.php` file due to comments, just be aware of the `bots` key. The keys of the bots will be the hook routes, the values will be the bot names (Think like aliases).

So if your config is like this:

```php
return [

	'bots'	=> [
		'bothook'	=> 'myDemoBot',
	]

]
```

To run the bot, you first need to navigate through http://yoursite.com/set_webhook/**bothook**. The api then will post to http**s**://yoursite.com/hook/**bothook**.

* Create a new `myDemoBot.php` inside `config/bots` folder and fill your Telegram API token and the messages, you can use the `sampleBot.php` as a template.
* To set the webhook, as described above, navigate through http://yoursite/set_webhook/**bothook** through your browser.
* Add your **@myDemoBot** to your conversation or group and start messaging :smile:

Notes
---------

* There's a `migration.php` cli PHP file already on the repository, but **don't** use it yet. It's there just to show I'm working on it, and It will be used in further versions.

Thanks
---------

* [Eser Özvataf](http://eser.ozvataf.com), for his ideas and bootstrapping the app. Also some of the codes are taken from his PHP Framework, [ScabbiaFW](https://github.com/scabbiafw/scabbia2-fw). You should definitely check that!
* [Silex](http://silex.sensiolabs.org/), the micro PHP framework
* [Guzzle](http://guzzlephp.org/), an extensible PHP HTTP client.
* [Idiorm](http://j4mie.github.io/idiormandparis/) A lightweight nearly-zero-configuration object-relational mapper and fluent query builder for PHP5.

TODOs
---------
* SQL support (already working on this). Messages etc. will be fetched from database
* Better search algorithm for provided parameters
* Sending sticker feature
* More complex tasks (like fetching data from 3rd party services such as Trakt etc.)
* Commands in addition to responses, like typing `/myBotCommand parameter` in a message.

Changelog
---------

#### 0.2 - release 2015-07-01
* General bootstrapping, and better configuration structure thanks to [Eser Özvataf](http://eser.ozvataf.com).

#### 0.1 - release 2015-06-26
* First Release.