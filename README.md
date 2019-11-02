# BotFire
### php library for telegram robot


<br>

**it is not complete**

**کتابخونه هنوز تکمیل نشده است**

<br>

Build a robot with three lines of code :)

```php
bot::setToken('token-string');
bot::autoInput();

bot::this()->message( bot::get('text') )->send();
```


<br>

download and move BotFire.php to models directory :|

## usage

usage in [night framework](https://github.com/parsgit/night)
```php
use Models\BotFire as bot;
```
<br>
or usage in other project

```php
include_once('BotFire.php');
use Models\BotFire as bot;
```
<br>

## basic usage

```php
bot::setToken('token-string');
bot::autoInput();
```

### sendMessage

```php
bot::this()->message('your message text')->send();

bot::this()->message('use parse_mode')->parse_mode('HTML')->send();

// send message to other user chat_id
bot::id(chat_id)->message('your message text')->send();

// if you want to get json result
$res=bot::this()->message('your message text')->sendAndGetJson();
```

### sendPhoto

```PHP
// send from url
bot::this()->photo(image_url_string)->send();

// send by file_id
bot::this()->photo(file_id_string)->send();

// send from local server
$file=bot::loadFile(file_path);
bot::this()->photo( $file )->send();

// send photo with caption
bot::this()->photo( 'user.png' , 'Caption text' )->send();
```
