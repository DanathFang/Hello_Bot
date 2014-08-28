<?php
class IRCBOT {
  private $server;
  private $channel;
  private $botnick;

  function __construct ($server, $channel, $botnick) {
    $this->server = stream_socket_client ($server);
    $this->channel = $channel;
    $this->botnick = $botnick;

    fwrite($this->server, "USER " . $this->botnick . " " . $this->botnick . " " . $this->botnick . " :logbot\n");
    fwrite($this->server, "NICK " . $this->botnick . "\n");
    fwrite($this->server, "JOIN " . $this->channel . "\n");
  }

  function wait () {
    $pattern = '/^:(.*)!.*:(.*)$/';

    while (!feof ($this->server)) {
      $result = fgets($this->server, 1024);

//      echo $result . "\n";
//$result = ":YoYo_!uid41756@gateway/web/irccloud.com/x-wzgbgpuiqndbietd PRIVMSG #trpg.tw :使用 php 改寫";

      preg_match($pattern, $result, $matches);
      if ($matches) {
        $this->action($matches[1], $matches[2]);
      }
    }
  }

  function show () {
    echo $this->channel . " channel\n";
    echo $this->botnick . " botnick\n";
  }

  function action ($nick, $say) {
    switch($nick) {//--判讀說話的人的暱稱
      case "一卡":
          switch($say) {//判斷一卡說了啥？
            case "HELLO":
            case "HI":
            case "你好":  
              echo "一卡主人你好啊，人家好期待你今天分享的資訊喔，請務必要跟人家說喔~";
              break;
            default: 
              echo "";
              break;
          }
          break;
      default:
          echo $nick . ":" . $say . "\n";
          break;
    }
    //echo $nick . ":" . $say . "\n";
  }
}

$port = ":6667";
$server = "tcp://irc.freenode.net" . $port;
$channel = "#trpg.tw";
$botnick = "Mybot";

$bot = new IRCBOT($server, $channel, $botnick);
$bot->wait();
?>
