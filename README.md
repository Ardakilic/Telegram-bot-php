# Telegram-bot-php

This app is a very simple Telegram PHP API for newly announced [Telegram Bots](https://telegram.org/blog/bot-revolution).

![Preview](https://i.imgur.com/juqrIZG.png)

![Preview2](https://i.imgur.com/RWo617C.png)

Features
---------

* By installing this app, you can have a working Telegram Bot within minutes!
* This app only has two routes (which one is to register the webhook, another is to listen the webhook) in an `index.php` file, and a configuration file.
* This app can handle multiple bots from same route. Different hooks will be different route parameters. I'm already hosting four different bots with the same application.
* This app can send messages both from config strings, or SQL directly.
* This app can also send photos, stickers, video and audio files if the source is set to SQL. You just need to add a new row in the table, and upload these resources in `assets/(audio|photo|sticker|video)` folder.
* There is a single `config/main.php` file which holds the general configuration, and a `config/bots/myDemoBot.php` as an example bot configuration.
* Bot works on both private chats and groups.
* The bot can either quote or send the response text directly.
* You can enable or disable preview links in bots' responses.

Requirements
---------

* PHP5.5+ (for Guzzle)
* Curl for PHP5 must be enabled to use Guzzle.
* PHP5-PDO libraries (not now, but will be needed in future versions).
* An SSL certificate (Telegram API requires this). You can use [Cloudflare's Free Flexible SSL](https://www.cloudflare.com/ssl) which crypts the web traffic from end user to their proxies if you're using CloudFlare DNS.
* Telegram API key, you can get one simply with [@BotFather](https://core.telegram.org/bots#botfather) with simple commands right after creating your bot.

Installation
---------
1. Set a new virtualhost and set the public folder as root. You can refer to Silex's official documentation [here](http://silex.sensiolabs.org/doc/web_servers.html)
2. Copy the app into your (virtual)server
3. Cd into the app's directory
4. Run `composer install` and install dependencies
5. Edit the `config/main.php` file due to comments, just be aware of the `bots` key. The keys of the bots will be the hook routes, the values will be the bot names (Think like aliases).

So if your config is like this:

```php
return [

	'bots'	=> [
		'bothook'	=> 'myDemoBot',
	]

]
```

The *alias* of your bot is `bothook`, and the actual name of your bot is `myDemoBot`.

(Alias is needed, because this app can handle multiple bots at once, and the alias is the key in this app that defines the bots)

6. (Optional) If you will be using SQL as driver, first set the `source` key to `mysql` (for MySQL) or `pgsql` (for Postgres) in `config/main.php`, then in the `mysql` / `pgsql` sub-key in the `connections` key, update your database credentials, and lastly run `php migration.php` in the terminal and run the migration to create the table for the bots. You can also import the `example_sql_data/example_data.mysql.sql` for MySQL or `example_sql_data/example_data.mysql.sql` for Postgres as a reference and example.
7. Create a new `myDemoBot.php` inside `config/bots` folder and fill your Telegram API token. you can use the `sampleBot.php` as a template. If you are using SQL (`mysql`, `pgsql` etc.) as driver, token is the only key that's required in the bot.
8. If you will be using the `file` driver (no database), you also need the responses key for replies. You can refer from `config/bots/sampleBot.php`.
9. To run the bot, you first need to set the webhook and teach Telegram where to post. To do this, navigate through http*(s)*://yoursite.com/set_webhook/**bothook**. The api then will post to http**s**://yoursite.com/hook/**bothook** (Remember, `bothook` is the alias of our bot named `myDemoBot` which we set in step 5).
9. Add your **@myDemoBot** to your conversation or group and start messaging :smile:

Notes
---------

* If you are using SQL as bot driver, the only key required in the config file is the `token` key. The remaining information will be per-response and will be fetched from SQL.


Thanks
---------

* [Eser Özvataf](http://eser.ozvataf.com), for his ideas and bootstrapping the app. Also some of the codes are taken from his PHP Framework, [ScabbiaFW](https://github.com/scabbiafw/scabbia2-fw). You should definitely check that!
* [Silex](http://silex.sensiolabs.org/), the micro PHP framework.
* [Guzzle](http://guzzlephp.org/), an extensible PHP HTTP client.
* [Idiorm](http://j4mie.github.io/idiormandparis/) A lightweight nearly-zero-configuration object-relational mapper and fluent query builder for PHP5.

TODOs
---------
* Different migration schema and configuration examples for different database drivers. (Currently only for MySQL and derivatives such as MariaDB and Postgres)
* Better search algorithm for provided parameters
* More complex tasks (like fetching data from 3rd party services such as Trakt etc.)
* Commands in addition to responses, like typing `/myBotCommand parameter` in a message.
* Sending location feature
* ~~SQL support (already working on this). Messages etc. will be fetched from database~~
* ~~Sending sticker feature~~

Changelog
---------

#### 1.1.0 - release 2015-07-27
* Postgres support added **(needs testing)**!
* An error was fixed where you typed a command (string starting with `/`) bot(s) responded with default fallback message (Command support will be added in future versions).

#### 1.0.0 - release 2015-07-23
* SQL support - Now you can save and serve your responses from SQL. (You must use a database driver that is supported by [Idiorm](http://j4mie.github.io/idiormandparis/) (most PDO drivers should work))
* You can now send `photo`s, `video`s, `sticker`s and `audio` files as response when using SQL. While adding a response, just set your `response_type`, and put the file name in `response_data`. An example SQL dump is in this repository as `example_data.sql`. You need to upload these files in their according folder in `assets` (E.g: if you are sending a photo named `metal.jpg`, you set the `response_type` as `image`, `response_data` as `metal.jpg` and upload the actual `metal.jpg` file into `assets/photo` sub folder).
* When using SQL driver, you can set per-response configuration for replies. You can set them whether to be sent as quote, or previewing links if any per response.
* Migration is now database-specific

#### 0.2 - release 2015-07-01
* General bootstrapping, and better configuration structure thanks to [Eser Özvataf](http://eser.ozvataf.com).

#### 0.1 - release 2015-06-26
* First Release.