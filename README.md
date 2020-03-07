#  BotFire


<br>

Build a robot with three lines of code :)

```php
bot::setToken('token-string');
bot::autoInput();

bot::this()->message( bot::get('text') )->send();
```
<br>


 - [basic usage](#usage)


### Webhook

 - [setWebhook](#setWebhook)
 - [getWebhookInfo](#getWebhookInfo)

### send method

 - [sendMessage](#sendMessage)
 - [sendPhoto](#sendPhoto)
 - [sendAudio](#sendAudio)
 - [sendDocument](#sendDocument)
 - [sendVideo](#sendVideo)
 - [sendAnimation](#sendAnimation)
 - [sendVoice](#sendVoice)
 - [sendVideoNote](#sendVideoNote)
 - [sendLocation](#sendLocation)
 
 ### update method
 
  - [editMessageText](#editMessageText)
  - [editMessageCaption](#editMessageCaption)
  - [editMessageReplyMarkup](#editMessageReplyMarkup)
  - [deleteMessage](#deleteMessage)

<br>

## usage

usage in [night framework](https://github.com/parsgit/night)
```php
use Models\BotFire as bot;
```
<br>

or usage in other project ( [view sample](https://github.com/parsgit/botfire/wiki/a-basic-example-of-a-robot) )


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


<br>

download and extract BotFire.php to models directory :|

## data access

```php
// Get the current chat_id
$chat_id=bot::$chat_id;

// Get the current username
$username=bot::$username;

// Get the current first_name
$first_name=bot::$first_name;

// Get the current last_name
$last_name=bot::$last_name;

// first_name + last_name
$full_name=bot::$full_name;

//Receive text the user has sent
$text=bot::get('text');

// get message_id
$message_id=bot::get('message_id');

// get message caption
$caption=bot::get('caption');

//Receive object of string sent by telegram
$ob_str=bot::$input;

//Receive json object sent by telegram
$json==bot::$json;
```
<hr>

### Get message type (new)

```php
// support messages [text,photo,video,video_note,voice,animation,document,contact,location]
$message = bot::getMessageType();

```

output text sample :
```json
{
  "type":"text",
  "data":"Hello botfire"
}
```

output photo sample :
```json
{
  "type": "photo",
  "data": [
    {
      "file_id": "AgA***",
      "file_unique_id": "AQA***",
      "file_size": 20303,
      "width": 320,
      "height": 296
    }
  ]
}
```
<hr>

**check client type**

```php
// If the request is from the supergroup return true else false
$is_group=bot::isGroup();

// If the request is from the supergroup or group return true else false
$is_group=bot::isGroup(false);

// If the request is from the private user
$is_user=bot::isUser();
```

get request from the supergroup

```php
// get group id
$chat_id = bot::$chat_id;

// Get the group title
$title = bot::$title;

// get sender user
$user = bot::get('user');

$user_chat_id = $user->id;
$is_bot = $user->is_bot;
```

### getMe
```php
$res=bot::this()->getMe()->send();
echo $res;
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

 use other methods
```php
bot::this()
->message('text')
->parse_mode(string) // HTML or Markdown
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->disable_web_page_preview(boolean)
->send();
```

### sendPhoto

```php
// send from url
bot::this()->photo(image_url_string)->send();

// send by file_id
bot::this()->photo(file_id_string)->send();

// send file from local server
$file=bot::loadFile('user.png');
bot::this()->photo( $file )->send();

// send photo with caption
bot::this()->photo( $file , 'Caption text for photo' )->send();
```

use other methods

```php
bot::this()
->photo( $file , 'Caption text for photo' )
->parse_mode(string) // HTML or Markdown
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();
```

** Other methods can be used like the photo method **
<br>
### sendAudio
Use this method to send audio our audio must be in the .MP3 or .M4A format.
```php
bot::this()->audio( $file , 'Caption text for audio' )->send();
```

use other methods

```php
bot::this()
->audio( $file , 'Caption text for audio' )
->duration(int_to_seconds) //Duration of the audio in seconds
->performer(string)
->title(string) //Track name
->parse_mode(string) // HTML or Markdown
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();

```

### sendDocument
Use this method to send general files
```php
bot::this()->document( $file , 'Caption text for document' )->send();

// use other methods
bot::this()
->document( $file , 'Caption text for document' )
->thumb(loadFile_or_string)
->parse_mode(string) // HTML or Markdown
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();

```

### sendVideo
Use this method to send video files,
```php
bot::this()->video( $file , 'Caption text for video' )->send();

// use other methods
bot::this()
->video( $file , 'Caption text for video' )
->duration(int_to_seconds) //Duration of sent video in seconds
->width(int) //Animation width
->height(int)//Animation height
->thumb(loadFile_or_string)
->parse_mode(string) // HTML or Markdown
->supports_streaming(boolean) //Pass True, if the uploaded video is suitable for streaming
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();
```


### sendAnimation
Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound).
```php
bot::this()->animation( $file , 'Caption text for animation' )->send();

// use other methods
bot::this()
->animation( $file , 'Caption text for animation' )
->duration(int_to_seconds) //Duration of sent animation in seconds
->width(int) //Animation width
->height(int)//Animation height
->thumb(loadFile_or_string)
->parse_mode(string) // HTML or Markdown
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();
```

### sendVoice
Use this method to send audio
```php
bot::this()->voice( $file , 'Caption text for voice' )->send();

// use other methods
bot::this()
->voice($file,'caption')
->parse_mode(string) // HTML or Markdown
->duration(int_to_seconds) //Duration of sent voice in seconds
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();
```

### sendVideoNote
As of v.4.0, Telegram clients support rounded square mp4 videos of up to 1 minute long.
```php
bot::this()->voice( video_file )->send();

// use other methods
bot::this()
->voice( video_file )
->duration(int_to_seconds) //Duration of sent video in seconds
->length(int) //Video width and height
->thumb(loadFile_or_string)
->disable_notification(boolean)
->reply_to(message_id)
->keyboard(botfire_keyboard)
->send();
```


### bot::loadFile
The loadFile function is used to send server files

```php
bot::this()->document(  bot::loadFile(file_path_string) )->send();
```


### sendLocation
Use this method to send point on the map. On success, the sent Message is returned.

```php
bot::this()->location($latitude,$longitude)->send();

// support live_period
bot::this()->location($latitude,$longitude)->live_period($number)->send();
```


## send chat action
Use this method when you need to tell the user that something is happening on the bot's side

action list :

'typing','upload_photo','record_video','upload_video','record_audio','upload_audio','upload_document','find_location','record_video_note','upload_video_note'

```php
bot::this()->chatAction('typing')->send();
```

### keyboard
inline keyboard sample :

```php
$k=bot::keyboard();

$k->inline()->row(function($col){
  // usage callback
  $col->btn('button name','callback_text');

  // usage url
  $col->btnUrl('night framework','https://nightframework.com');
})
->row(function($col){
  $col->btn('one button','callback_text_2');
});

// send message with inline button
bot::this()->message('message text')->keyboard($k)->send();

// send photo with inline button
bot::this()->photo(file_id,'caption')->keyboard($k)->send();
```

sample 2
```php
$k=bot::keyboard();
$k->btn('game 1','game_callback');
$k->btn('game 2','game_callback');
$k->row();

bot::this()->message('message text')->keyboard($k)->send();
```

[Telegram ReplyKeyboardMarkup Docs](https://core.telegram.org/bots/api#replykeyboardmarkup)
### markup($resize_keyboard,$one_time_keyboard,$selective)
```php
$k=bot::keyboard();

$k->markup()->row(function($col){
  $col->btn('button name');
});

$k->markup(true)->row(function($col){
  $col->btn('button name');
});

bot::this()->message('text')->keyboard($k)->send();
```

### remove markup keyboard

```php
bot::this()->message('text')->removeKeyboard()->send();
```

## Query callback
check request calback
```php
if(bot::$isCallback){
    /// code ..
}
```
Receive callback data
```php
$data=bot::get('data');
```

Receive callback_query_id
```php
$query_id = bot::get('callback_id');
```

### answerCallback($show_alert=false)
```php
bot::this()->answerCallback()->send();

// send alert
bot::this()->answerCallback(true)->text('hello Telegram :)')->send();

// or open robot link
$link='t.me/your_robot?start=xxxx';
bot::this()->answerCallback()->url($link)->send();
```


## Edit Message

### editMessageText
Use this method to edit text and game messages. On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.

```php
bot::this()->editMessage('new text string')->send();
```

```php
// use other methods
bot::this()
->editMessage('new text string')

// Use custom message_id
->message_id(id)
// Use custom inline_message_id
->inline_message_id(id)

->parse_mode('HTML')
->disable_web_page_preview(boolean)
->keyboard($k) // A JSON-serialized object for an inline keyboard.
->send();
```


### editMessageCaption

Use this method to edit captions of messages. On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.

```php
bot::this()->editCaption('new text string')->send();
```

```php
// use other methods
bot::this()
->editCaption('new text string')

// Use custom message_id
->message_id(id)
// Use custom inline_message_id
->inline_message_id(id)

->parse_mode('HTML')
->keyboard($k) // A JSON-serialized object for an inline keyboard.
->send();
```

### editMessageReplyMarkup

Use this method to edit only the reply markup of messages. On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.

```php
bot::this()->editReplyMarkup()->keyboard($k)->send();
```

```php
// use other methods
bot::this()
->editReplyMarkup('new text string')

// Use custom message_id
->message_id(id)
// Use custom inline_message_id
->inline_message_id(id)

->keyboard($k) // A JSON-serialized object for an inline keyboard.
->send();
```

### deleteMessage

Use this method to delete a message

```php
bot::this()->deleteMessage()->send();
// or
bot::this()->deleteMessage()->message_id('custom message_id')->send();
```

### setWebhook

```php

bot::this()->setWebhook($url)->send();

// or

bot::this()->setWebhook($url)->max_connections(40)->send();

// or

$cert=bot::loadFile('certificate.txt');

bot::this()
->setWebhook($url)
->certificate($cert)
->allowed_updates($array)
->send();
```


### getWebhookInfo

```php
$res=bot::this()->getWebhookInfo()->send();
echo $res;
```

<hr>

## Members

### getChat

Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.

```php
$result=bot::this()->getChat()->send();
// or 
$result=bot::id(chat_id)->getChat()->send();

echo $result;
```

### getChatAdministrators

Use this method to get a list of administrators in a chat. On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.

```php
$result=bot::this()->getChatAdministrators()->send();
// or 
$result=bot::id(chat_id)->getChatAdministrators()->send();

echo $result;
```

### getChatMembersCount

Use this method to get the number of members in a chat. Returns Int on success.

```php
$result=bot::this()->getChatMembersCount()->send();
// or 
$result=bot::id(chat_id)->getChatMembersCount()->send();

echo $result;
```
