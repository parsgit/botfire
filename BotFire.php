<?php

/********************************************
*
* telegram robot library
* repository : https://github.com/parsgit/botfire
* Documentation : https://telegram.botfire.ir
*
********************************************/

namespace Models;

class BotFire{

  private static $token='',$get=[];
  public static $server='https://api.telegram.org/bot';


  public static $input,$json,$chat_id,$username,$first_name,$last_name,$full_name,$user_type,$title,$isCallback=false;

  public static function setToken($token)
  {
    BotFire::$token=$token;
  }

  public static function getToken()
  {
    return BotFire::$token;
  }

  public static function setJson($input)
  {
    BotFire::$json=json_decode($input);
  }

  public static function getInput(){

    if (BotFire::$input==null) {
      BotFire::$input=file_get_contents ( 'php://input' );
    }

    return BotFire::$input;
  }

  public static function autoInput()
  {
    BotFire::setJson(BotFire::getInput());
    BotFire::initClientInfo();
  }

  public static function initClientInfo()
  {
    if (isset(BotFire::$json->message)) {
      BotFire::$isCallback=false;
      $message=BotFire::$json->message;

      BotFire::$get['text']=BotFire::checkIsset('text',$message);
      BotFire::$get['caption']=BotFire::checkIsset('caption',$message);
      BotFire::$get['message_id']=$message->message_id;

      if (isset($message->chat)) {
        $chat=$message->chat;
        BotFire::initChatUserInfo($chat);
      }

      if ($message->from) {
        BotFire::$get['user']=$message->from;
      }
    }
    else if( isset(BotFire::$json->callback_query) ) {
      BotFire::$isCallback=true;
      $query=BotFire::$json->callback_query;

      BotFire::$get['text']=BotFire::checkIsset('text',$query->message);
      BotFire::$get['caption']=BotFire::checkIsset('caption',$query->message);
      BotFire::$get['data']=$query->data;
      BotFire::$get['callback_id']=$query->id;
      BotFire::$get['message_id']=$query->message->message_id;

      if (isset($query->message->chat)) {
        $chat=$query->message->chat;
        BotFire::initChatUserInfo($chat);
      }
      if ($query->from) {
        BotFire::$get['user']=$query->from;
      }
    }
  }

  private static function initChatUserInfo($ob)
  {
    BotFire::$chat_id=BotFire::checkIsset('id',$ob);

    BotFire::$username=BotFire::checkIsset('username',$ob);
    BotFire::$user_type=BotFire::checkIsset('type',$ob);
    BotFire::$first_name=BotFire::checkIsset('first_name',$ob);
    BotFire::$last_name=BotFire::checkIsset('last_name',$ob);
    BotFire::$full_name=BotFire::$first_name.' '.BotFire::$last_name;

    BotFire::$title=BotFire::checkIsset('title',$ob);

  }

  public static function getMessageType(){
    if (isset(BotFire::$json->message->text)) {
      return ['type'=>'text','data'=>BotFire::$json->message->text];
    }
    elseif (isset(BotFire::$json->message->photo)) {
      return ['type'=>'photo','data'=>BotFire::$json->message->photo];
    }
    elseif (isset(BotFire::$json->message->video)) {
      return ['type'=>'video','data'=>BotFire::$json->message->video];
    }
    elseif (isset(BotFire::$json->message->video_note)) {
      return ['type'=>'video_note','data'=>BotFire::$json->message->video_note];
    }
    elseif (isset(BotFire::$json->message->voice)) {
      return ['type'=>'voice','data'=>BotFire::$json->message->voice];
    }
    elseif (isset(BotFire::$json->message->animation)) {
      return ['type'=>'animation','data'=>BotFire::$json->message->animation];
    }
    elseif (isset(BotFire::$json->message->document)) {
      return ['type'=>'document','data'=>BotFire::$json->message->document];
    }
    else {
      return ['type'=>false,'data'=>BotFire::$json];
    }
  }

  /**
  *
  */
  public static function get($name)
  {
    return BotFire::$get[$name];
  }

  public function isGroup($only_supergroup=true)
  {
    if ($only_supergroup && BotFire::$user_type=='supergroup') {
      return true;
    }
    elseif (! $only_supergroup && (BotFire::$user_type=='supergroup' || BotFire::$user_type=='group') ) {
      return true;
    }
    else {
      return false;
    }
  }

  public function isUser()
  {
    if ( BotFire::$user_type == 'private' ) {
      return true;
    }
    else {
      return false;
    }
  }


  private static function checkIsset($name,$ob)
  {
    if (isset($ob->$name)) {
      return $ob->$name;
    }
    else {
      return null;
    }
  }

  public static function keyboard()
  {
    return new keyboard;
  }

  public static function this()
  {
    return new BotFireSendMessage(BotFire::$token,BotFire::$chat_id);
  }

  public static function id($chat_id)
  {
    return new BotFireSendMessage(BotFire::$token,$chat_id);
  }

  /*
  * for send server file to robot
  */
  public static function loadFile($path)
  {
    return curl_file_create($path);
  }
}

/**
*
*/
class BotFireSendMessage
{
  private $token,$method;
  private $params=[];

  function __construct($token,$chat_id)
  {
    $this->token=$token;
    $this->params['chat_id']=$chat_id;
  }

  public function keyboard($k)
  {
    $this->params['reply_markup']=json_encode($k->get());

    return $this;
  }

  public function removeKeyboard($remove_keyboard=true,$selective=null)
  {
    $arr=['remove_keyboard'=>$remove_keyboard];
    if($selective!=null){$arr['selective']=$selective;}
    $this->params['reply_markup']=json_encode($arr);
    return $this;
  }


  /**
  * Use this method to get up to date information about the chat
  * docs : https://core.telegram.org/bots/api#getchat
  * method : getChat
  */
  public function getChat()
  {
    $this->method='getChat';
    return $this;
  }

  /**
  * Use this method to get a list of administrators in a chat
  * docs : https://core.telegram.org/bots/api#getchatadministrators
  * method : getChatAdministrators
  */
  public function getChatAdministrators()
  {
    $this->method='getChatAdministrators';
    return $this;
  }

  /**
  * Use this method to get the number of members in a chat. Returns Int on success.
  * docs : https://core.telegram.org/bots/api#getchatmemberscount
  * method : getChatMembersCount
  */
  public function getChatMembersCount()
  {
    $this->method='getChatMembersCount';
    return $this;
  }

  /**
  * Use this method to get information about a member of a chat.
  * docs : https://core.telegram.org/bots/api#getchatmember
  * method : getChatMember
  */
  public function getChatMember($user_id)
  {
    $this->params['user_id']=$user_id;
    $this->method='getChatMember';

    return $this;
  }


  /**
  * A simple method for testing your bot's auth token.
  * docs : https://core.telegram.org/bots/api#getme
  * @return json object
  */
  public function getMe()
  {
    $this->method='getMe';

    return $this->sendAndGetJson();
  }

  /**
  * docs : https://core.telegram.org/bots/api#sendmessage
  * method : sendMessage
  * @param $text string
  */
  public function message($text)
  {
    $this->params['text']=$text;
    $this->method='sendMessage';

    return $this;
  }

  /**
  * Use this method to send photos.
  *
  * @param $photo InputFile or String
  * @param $caption String
  */
  public function photo($photo,$caption=null)
  {
    $this->params['photo']=$photo;

    if ($caption!=null) {
      $this->params['caption']=$caption;
    }

    $this->method='sendPhoto';

    return $this;
  }

  /**
  * Use this method to send audio
  * our audio must be in the .MP3 or .M4A format.
  *
  * @param $audio InputFile or String
  * @param $caption String
  */
  public function audio($audio,$caption=null)
  {
    $this->params['audio']=$audio;

    if ($caption!=null) {
      $this->params['caption']=$caption;
    }

    $this->method='sendAudio';

    return $this;
  }

  /**
  * Use this method to send general files
  *
  * @param $document InputFile or String
  * @param $caption String
  */
  public function document($document,$caption=null)
  {
    $this->params['document']=$document;

    if ($caption!=null) {
      $this->params['caption']=$caption;
    }

    $this->method='sendDocument';

    return $this;
  }

  /**
  * Use this method to send video files,
  *
  * @param $video InputFile or String
  * @param $caption String
  */
  public function video($video,$caption=null)
  {
    $this->params['video']=$video;

    if ($caption!=null) {
      $this->params['caption']=$caption;
    }

    $this->method='sendVideo';

    return $this;
  }

  /**
  * Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound).
  *
  * @param $animation InputFile or String
  * @param $caption String
  */
  public function animation($animation,$caption=null)
  {
    $this->params['animation']=$animation;

    if ($caption!=null) {
      $this->params['caption']=$caption;
    }

    $this->method='sendAnimation';

    return $this;
  }

  /**
  * Use this method to send audio
  *
  * @param $voice InputFile or String
  * @param $caption String
  */
  public function voice($voice,$caption=null)
  {
    $this->params['voice']=$voice;

    if ($caption!=null) {
      $this->params['caption']=$caption;
    }

    $this->method='sendVoice';

    return $this;
  }


  /**
  * As of v.4.0, Telegram clients support rounded square mp4 videos of up to 1 minute long.
  * Use this method to send video messages.
  *
  * @param $video_note InputFile or String
  * @param $caption String
  */
  public function videoNote($video_note)
  {
    $this->params['video_note']=$video_note;
    $this->method='sendVideoNote';

    return $this;
  }


  public function editReplyMarkup($message_id=null)
  {
    if ($message_id==null) {
      $this->message_id(BotFire::get('message_id'));
    }
    else {
      $this->message_id($message_id);
    }
    $this->method='editMessageReplyMarkup';

    return $this;
  }

  public function editMessage($text)
  {
    $this->params['text']=$text;
    $this->message_id(BotFire::get('message_id'));
    $this->method='editMessageText';

    return $this;
  }

  public function editCaption($caption)
  {
    $this->params['caption']=$caption;
    $this->message_id(BotFire::get('message_id'));
    $this->method='editMessageCaption';

    return $this;
  }

  public function deleteMessage(){
    $this->message_id(BotFire::get('message_id'));
    $this->method='deleteMessage';
    return $this;
  }



  public function getWebhookInfo()
  {
    $this->method='getWebhookInfo';
    return $this;
  }
  public function setWebhook($url)
  {
    $this->method='setWebhook';
    $this->params['url']=$url;
    return $this;
  }
  public function certificate($file)
  {
    $this->params['certificate']=$file;
    return $this;
  }

  public function max_connections($value=40)
  {
    $this->params['max_connections']=$value;
    return $this;
  }

  public function allowed_updates($array)
  {
    $this->params['allowed_updates']=$array;
    return $this;
  }




  public function message_id($message_id)
  {
    $this->params['message_id']=$message_id;
    return $this;
  }

  public function inline_message_id($inline_message_id)
  {
    $this->params['inline_message_id']=$inline_message_id;
    return $this;
  }

  public function callback_query_id($callback_query_id)
  {
    $this->params['callback_query_id']=$callback_query_id;
    return $this;
  }

  public function url($url)
  {
    $this->params['url']=$url;
    return $this;
  }
  public function text($text)
  {
    $this->params['text']=$text;
    return $this;
  }

  public function answerCallback($show_alert=false)
  {
    $this->callback_query_id(BotFire::get('callback_id'));
    $this->params['show_alert']=$show_alert;
    $this->method='answerCallbackQuery';
    return $this;
  }


  /**
  * Use this method to send phone contacts.
  * @param $phone_number string Required
  * @param $first_name   string Required
  * @param $last_name   string Optional
  */
  public function contact($phone_number,$first_name,$last_name=null)
  {
    $this->params['phone_number']=$phone_number;
    $this->params['first_name']=$first_name;

    if ($last_name!=null) {
      $this->params['last_name']=$last_name;
    }

    $this->method='sendContact';

    return $this;
  }

  /**
  * Use this method to kick a user from a group
  *
  * @param $user_id [Integer] Unique identifier of the target user
  * @param $until_date [Integer] Date when the user will be unbanned, unix time. If user is banned for more than 366 days or less than 30 seconds from the current time they are considered to be banned forever
  */
  public function kickChatMember($user_id,$until_date=null)
  {
    $this->params['user_id']=$user_id;

    if ($until_date!=null) {
      $this->params['until_date']=$until_date;
    }
    $this->method='kickChatMember';

    return $this;
  }

  /**
  * Use this method to unban a previously kicked user in a supergroup or channel.
  *
  * @param $user_id [Integer]
  */
  public function unbanChatMember($user_id)
  {
    $this->params['user_id']=$user_id;
    $this->method='unbanChatMember';

    return $this;
  }

  /**
  * Use this method when you need to tell the user that something is happening on the bot's side
  * @param $action String ['typing','upload_photo','record_video','upload_video','record_audio','upload_audio','upload_document','find_location','record_video_note','upload_video_note']
  */
  public function chatAction($action)
  {
    $this->params['action']=$action;
    $this->method='sendChatAction';

    return $this;
  }


  /**
  * Use this method to send point on the map. On success, the sent Message is returned.
  * @param  [Float number] $latitude  Latitude of the location
  * @param  [Float number] $longitude Longitude of the location
  */
  public function location($latitude,$longitude)
  {
    $this->params['latitude']=$latitude;
    $this->params['longitude']=$longitude;

    $this->method='sendLocation';

    return $this;
  }

  /**
  * Send Markdown or HTML
  */
  public function parse_mode($mode='HTML')
  {
    $this->params['parse_mode']=$mode;

    return $this;
  }

  public function vcard($vcard)
  {
    $this->params['vcard']=$vcard;

    return $this;
  }


  /**
  * Send Markdown or HTML
  */
  public function disable_web_page_preview($disable=true)
  {
    $this->params['disable_web_page_preview']=$disable;
    return $this;
  }

  /**
  * Disables link previews for links in this message
  */
  public function disable_notification($active=true)
  {
    $this->params['disable_notification']=$active;
    return $this;
  }

  /**
   * Period in seconds for which the location will be updated (see Live Locations, should be between 60 and 86400.
   * @param  [Integer] $live_period
   */
  public function live_period($live_period)
  {
    $this->params['live_period']=$live_period;
    return $this;
  }

  /**
  * If the message is a reply, ID of the original message
  * @param $message_id Integer
  **/
  public function reply_to($message_id)
  {
    $this->params['reply_to_message_id']=$message_id;
    return $this;
  }

  public function duration($duration)
  {
    $this->params['duration']=$duration;
    return $this;
  }

  public function performer($performer)
  {
    $this->params['performer']=$performer;
    return $this;
  }

  public function title($title)
  {
    $this->params['title']=$title;
    return $this;
  }

  public function width($width)
  {
    $this->params['width']=$width;
    return $this;
  }

  public function height($height)
  {
    $this->params['height']=$width;
    return $this;
  }

  public function supports_streaming($supports_streaming)
  {
    $this->params['supports_streaming']=$supports_streaming;
    return $this;
  }

  public function thumb($thumb)
  {
    $this->params['thumb']=$thumb;
    return $this;
  }

  public function length($length)
  {
    $this->params['length']=$length;
    return $this;
  }

  /**
  * Additional interface options. A JSON-serialized
  * object for an inline keyboard,custom reply keyboard,
  * instructions to remove reply keyboard or to force a reply from the user.
  */
  public function reply_markup($ob)
  {
    $this->params['reply_markup']=$ob;
    return $this;
  }


  public function send()
  {
    $url=BotFire::$server.$this->token.'/'.$this->method;
    return Request::api($url,$this->params,true);
  }

  public function sendAndGetJson()
  {
    return json_decode($this->send());
  }


}

/**
*
*/
class keyboard
{
  private $params=[];
  private $btns=[],$type='inline_keyboard',$resize_keyboard,$one_time_keyboard;

  public function inline()
  {
    $this->type='inline_keyboard';
    return $this;
  }

  public function markup($resize_keyboard=false,$one_time_keyboard=false,$selective=null)
  {
    $this->type='keyboard';
    $this->resize_keyboard=$resize_keyboard;
    $this->one_time_keyboard=$one_time_keyboard;
    $this->selective=$selective;

    return $this;
  }



  public function row($func=null)
  {
    if ($func!=null) {
      $func($this);
    }

    $this->params[]=$this->btns;
    $this->btns=[];

    return $this;
  }

  public function btnUrl($name,$url)
  {
    $this->btns[]=['text'=>$name,'url'=>$url];
    return $this;
  }

  public function btn($name,$callback_data=null)
  {
    if ($callback_data!=null) {
      $this->btns[]=['text'=>$name,'callback_data'=>$callback_data];
    }
    else {
      $this->btns[]=['text'=>$name];
    }

    return $this;
  }

  public function get()
  {
    if ($this->getType()=='inline_keyboard') {
      $params=[
        $this->getType()=>$this->params
      ];
    }
    else {
      $params=[
        $this->getType()=>$this->params,
        'resize_keyboard'=>$this->resize_keyboard,
        'one_time_keyboard'=>$this->one_time_keyboard
      ];
      if ($this->selective!=null) {
        $params['selective']=$this->selective;
      }
    }

    return $params;
  }

  public function getType()
  {
    return $this->type;
  }

}


class Request
{

  /**
  *
  * @param string $url
  * @param array $params
  * @param string $use_curl
  * @param string $post
  * @return string request text
  */
  public static function api($url,$params=[],$post=false){

    if (! $post){
      $url.="?";
      foreach ($params as $key=>$value){
        $url.="$key=$value&";
      }
    }
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    if (! $post){
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    }
    else{
      curl_setopt($ch, CURLOPT_POST,1);
    }
    // curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // required as of PHP 5.6.0
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result=curl_exec($ch);
    curl_close($ch);
    return $result;

  }
}
