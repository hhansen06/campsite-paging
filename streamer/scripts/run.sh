#!/bin/bash
while true
do
    sleep 100
done
gst-launch-1.0 jackaudiosrc client-name=my-stream  provide-clock=false ! shout2send ip=127.0.0.1 port=12000 password=pass mount=/test.webm 