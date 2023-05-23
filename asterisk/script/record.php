#!/usr/bin/php -q
    <?php
      ini_set("display_errors","0");
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // DEBUG
      set_time_limit(0);
      require('phpagi.php');
      require("config.php");
  
      function get_settings($name)
      {
        $m = new mysqli('database',MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE);
        $sql = "SELECT * FROM `settings` WHERE `set_name` LIKE '".$name."'";
        $res = $m->query($sql);
        if($m->affected_rows > 0)
        {
            $res = $res->fetch_array($res);
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

      $agi = new AGI();
      $agi->answer();
      $ani = $agi->request;
      $agi->noop("Neuer eingehender Anruf!");
      $m1 = msql();
      $data  = $m1->query("SELECT * FROM durchsagen_quellen WHERE rufnummer='".$ani["agi_callerid"]."'")->fetch_array();

      if(!isset($data["quelle_id"]))
      {
            $m2 = msql();
            $m2->query("INSERT into durchsagen_quellen (rufnummer,name,erlaubt) VALUES ('".$ani["agi_callerid"]."','',0);");

            $agi->verbose(json_encode($ani));


            $quellid = $m2->insert_id;


            $allowed = false;
      }
      else
      {
            if($data["erlaubt"] == "1")
            {
              $allowed = true;
            }
              $quellid = $data["quelle_id"];
      }

        if($allowed)
        {
            $file = gen_speech_by_text("Durchsage nach dem Signal ton sprechen, dann mit raute beenden");
            $agi->noop($file);
            $agi->stream_file(str_replace(".mp3","",$file));

            $filename = time()+rand(0,100);
            $agi->record_file("/durchsagen//".$filename,"wav","#",-1, NULL, true);

            $m3 = msql();
            $m3->query("INSERT into durchsagen (time_created,text,filename,quell_id,deleted,samplerate,speechtotext,pushed) VALUES ('".time()."','"."<img src=\"images/ajax-loader.gif\">"."','".$filename."','".$quellid."','0',8000,0,0)");

            $m4 = msql();
            $m4->query("INSERT into durchsagen_warteschlange (durchsage_id,play_time,played) VALUES ('".$m3->insert_id."',0,0)");
            set_settings("durchsagen_change_id",time());
            exec("chmod 777 /durchsagen/".$filename.".wav");

            $file = gen_speech_by_text("Durchsage wird wiedergegeben!");
            $agi->stream_file(str_replace(".mp3","",$file));
        }
        else
        {
            $file = gen_speech_by_text("Sie sind nicht berechtigt diesen Dienst zu benutzen! Ihre BEnutzernummer ist: ".$quellid);
            $agi->stream_file(str_replace(".mp3","",$file));
        }


    $agi->hangup();
  ?>