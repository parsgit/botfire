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

// send file from local server
$file=bot::loadFile('user.png');
bot::this()->photo( $file )->send();

// send photo with caption
bot::this()->photo( $file , 'Caption text for photo' )->send();

// use other methods
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
```PHP
bot::this()->audio( $file , 'Caption text for audio' )->send();

// use other methods
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
```PHP
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
```PHP
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
```PHP
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
```PHP
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
```PHP
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

#### bot::loadFile
The loadFile function is used to send server files

```php
bot::this()->document(  bot::loadFile(file_path_string) )->send();
```

### keyboard
inline keyboard sample :
```
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
