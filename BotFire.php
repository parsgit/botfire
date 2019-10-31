<?php

/********************************************
*
* telegram robot library
* repository : https://github.com/parsgit/botfire
*
********************************************/




namespace Models;
use App\Web\File;

class BotFire{

  private static $token='',$get=[];
  public static $server='https://api.telegram.org/bot';


  public static $input,$json,$chat_id,$username,$first_name,$last_name,$full_name,$user_type,$title,$isCallback;

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

  public function initClientInfo()
  {
    if (isset(BotFire::$json->message)) {
      BotFire::$isCallback=false;
      $message=BotFire::$json->message;

      BotFire::$get['text']=BotFire::checkIsset('text',$message);

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
      BotFire::$get['data']=$query->data;

      if (isset($query->message->chat)) {
        $chat=$query->message->chat;
        BotFire::initChatUserInfo($chat);
      }
      if ($query->from) {
        BotFire::$get['user']=$query->from;
      }

    }
  }

  private function initChatUserInfo($ob)
  {
    BotFire::$chat_id=BotFire::checkIsset('id',$ob);

    BotFire::$username=BotFire::checkIsset('username',$ob);
    BotFire::$user_type=BotFire::checkIsset('type',$ob);
    BotFire::$username=BotFire::checkIsset('id',$ob);
    BotFire::$first_name=BotFire::checkIsset('first_name',$ob);
    BotFire::$last_name=BotFire::checkIsset('last_name',$ob);
    BotFire::$full_name=BotFire::$first_name.' '.BotFire::$last_name;

    BotFire::$title=BotFire::checkIsset('title',$ob);

  }

  public static function get($name)
  {
    return BotFire::$get[$name];
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

  public function inlineKeyboard()
  {
    return new keyboard;
  }

  public static function this()
  {
    return new BotFireSendMessage(BotFire::$token,BotFire::$chat_id);
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


     echo json_encode($this->params);

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
  * Send Markdown or HTML
  */
  public function parse_mode($mode='HTML')
  {
    $this->params['parse_mode']=$mode;

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
  * If the message is a reply, ID of the original message
  * @param $message_id Integer
  **/
  public function reply_to($message_id)
  {
    $this->params['reply_to_message_id']=$message_id;
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



  public function row($func)
  {
    $func($this);
    $this->params[]=$this->btns;
    $this->btns=[];

    return $this;
  }

  public function btnUrl($name,$url)
  {
    $this->btns[]=['text'=>$name,'url'=>$url];
    return $this;
  }

  public function btn($name,$callback_data)
  {
    $this->btns[]=['text'=>$name,'callback_data'=>$callback_data];
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
