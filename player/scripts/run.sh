#!/bin/bash

echo "<?php
define (\"MYSQL_USER\",\"${MYSQL_USER}\");
define (\"MYSQL_PASSWORD\",\"${MYSQL_PASSWORD}\");
define (\"MYSQL_DATABASE\",\"${MYSQL_DATABASE}\");

" >/root/config.php


php /root/player.php