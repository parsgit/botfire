# telebot
### php library for telegram robot

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

usage in night framework
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
##basic usage

```php
bot::setToken('token-string');
bot::autoInput();
```
