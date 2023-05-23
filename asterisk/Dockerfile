FROM debian
MAINTAINER Henrik Hansen <hhansen06@googlemail.com>

RUN apt-get update

RUN apt-get install asterisk php php-mysql php-curl -y
RUN apt-get install libsox-fmt-base -y

COPY run.sh /root/run.sh
RUN chmod +x /root/run.sh

copy script/* /usr/share/asterisk/agi-bin/
RUN chmod +x /usr/share/asterisk/agi-bin/record.php

CMD ["/root/run.sh"]