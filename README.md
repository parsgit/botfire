# telebot
php library for telegram robot


## usage

usage in nightframework
```php
use Models\BotFire as bot;
```
<br>
or usage in other framework and project

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
