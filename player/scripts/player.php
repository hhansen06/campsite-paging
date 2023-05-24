<?php
include("config.php");
include("osc.php");
include("functions.php");

ini_set("display_errors","1");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // DEBUG
set_time_limit(0);


echo "Delay startup 30 seconds ... \n";
#sleep(30);
echo "Looking for new durchsagen ...\n";

$jingle_played = false;
$mc = msql();
while(true)
{
    $res = $mc->query("SELECT * FROM durchsagen_warteschlange as dw, durchsagen as d where dw.durchsage_id = d.id AND dw.play_time < '".time()."'");
    if($mc->affected_rows > 0)
    {
        echo "Found new entry in durchsagen_warteschlange.. \n";
        if(!$jingle_played)
        {
            music(false);
            play("/root/jingle.mp3");
            $jingle_played = true;
        }

        while($row = $res->fetch_array())
        {
          play("/durchsagen/".$row["filename"].".wav");
          set_settings("durchsagen_change_id",time());
          $mc->query("delete from durchsagen_warteschlange where play_id ='".$row["play_id"]."'");
        }
    }
    else
    {
        if ($jingle_played)
        {
            music(true);
            $jingle_played = false;
        }

    }
    sleep(1);
}