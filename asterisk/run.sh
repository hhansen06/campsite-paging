#!/bin/bash

#       - SIP_SERVER=172.18.5.2
#       - SIP_USER=zeltlagerkram
#       - SIP_PASSWD=asdqwe123

echo "[general]
disallow=all
allow=ulaw
allow=gsm
port = 5060
bindaddr = 0.0.0.0
context = others
context=default
externip=${SERVER_IP}
register => ${SIP_REGISTER}
localnet=${SIP_LOCALNET}
;prematuremedia=no
;progressinband=yes


[${SIP_SERVER}]
type = peer
host = ${SIP_SERVER}
outboundproxy=${SIP_SERVER}
port = 5060
defaultUser = ${SIP_USER}
fromuser = ${SIP_USER}
fromdomain = default
secret = ${SIP_PASSWORD}
dtmfmode = rfc2833
insecure = port,invite
canreinvite = no
registertimeout = 600
disallow=all
allow=alaw
allow=ulaw" > /etc/asterisk/sip.conf


echo "[default]
exten => s,1,AGI(record.php)
exten => s,2,Hangup()" > /etc/asterisk/extensions.conf


echo "<?php
\$ms = new mysqli('database',\"${MYSQL_USER}\",\"${MYSQL_PASSWORD}\",\"${MYSQL_DATABASE}\");

if (\$ms -> connect_errno) {
  echo \"Failed to connect to MySQL: \" . \$ms -> connect_error;
  exit();
}
" >/usr/share/asterisk/agi-bin/mysql.php

asterisk -cv