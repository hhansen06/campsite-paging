<?php


function play($file)
{
    echo "Starting Playing File".$file."\n";
    $cmd = "mpv ".$file." --ao=jack --jack-name=dplayer --jack-port=\"Non-Mixer/Durchsagen\"";
    exec($cmd);
    echo "Finished Playing File ".$file."\n";
}

  
function get_settings($name)
{
  $m = new mysqli('database',MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE);
  $sql = "SELECT * FROM `settings` WHERE `set_name` LIKE '".$name."'";
  $res = $m->query($sql);

  if($m->affected_rows > 0)
  {
      $data = $res->fetch_array();
      if($data["set_value"])
          return $data["set_value"];
      else
          return " ";
  }
  else
      return false;
}

function set_settings($name, $value)
{
  $m = new mysqli('database',MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE);
  if(get_settings($name) != false)
  {
    $m->query("UPDATE settings SET set_value = '".$value."' WHERE set_name = '".$name."'");
  }
  else
  {
    $m->query("INSERT into settings (set_name,set_value) VALUES ('".$name."','".utf8_encode($value)."')");
  }
}


function msql()
{
  return new mysqli('database',MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE);
}


function gen_speech_by_text($text)
{
  $folder = "/usr/share/asterisk/agi-bin/";
  $filename = md5($text);

  if(!file_exists($filename.".wav")) {
    $fp = fopen($folder.$filename.".wav", 'w');

    $url = "http://gametts:5000/?speaker_id=".VOICE_ID."&text=".urlencode($text);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $data = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    
    exec("sox ".$folder.$filename.".wav -r 8000 ".$folder.$filename."-8000.wav");
  }
  return $folder.$filename."-8000";
}