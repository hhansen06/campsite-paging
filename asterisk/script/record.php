#!/usr/bin/php -q
    <?php
    
      function gen_speech_by_text($text)
      {
      return "/usr/share/asterisk/agi-bin/".md5($text);
      }


      ini_set("display_errors","0");
      set_time_limit(0);
      require('phpagi.php');
      require("mysql.php");
      $agi = new AGI();
      $agi->answer();
      $ani = $agi->request;
      $agi->noop("Neuer eingehender Anruf!");
      $data  = $ms->query("SELECT * FROM durchsagen_quellen WHERE rufnummer='".$ani["agi_callerid"]."'")->fetch_array();
        if(!isset($data["quelle_id"]))
        {
            $quellid = $ms->query("INSERT into durchsagen_quellen (rufnummer) VALUES ('".$ani["agi_callerid"]."')")->insert_id;
            $allowed = false;
        }
        else
        {
            if($data["erlaubt"] == "1")
                $allowed = true;

                $quellid = $data["quelle_id"];
        }

        if($allowed)
        {
            $file = gen_speech_by_text("Durchsage nach dem Signal ton sprechen, dann mit raute beenden");
            $agi->noop($file);
            $agi->stream_file(str_replace(".mp3","",$file));

            $filename = time()+rand(0,100);
            $agi->record_file("/var/www/durchsagen/".$filename,"wav","#",-1, NULL, true);

            mysql_query("INSERT into durchsagen (time_created,text,filename,quell_id,deleted,samplerate) VALUES ('".time()."','"."<img src=\"images/ajax-loader.gif\">"."','".$filename."','".$quellid."','0',8000)");
            mysql_query("INSERT into durchsagen_warteschlange (durchsage_id) VALUES ('".mysql_insert_id()."')");
            set_settings("durchsagen_change_id",time());
            exec("chmod 777 /var/www/durchsagen/".$filename.".wav");

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