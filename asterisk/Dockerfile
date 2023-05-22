FROM debian
MAINTAINER Henrik Hansen <hhansen06@googlemail.com>

RUN apt-get update

RUN apt-get install asterisk php -y

copy conf/sip.conf /etc/asterisk/sip.conf
COPY run.sh /run.sh


RUN chmod +x /run.sh
CMD ["/run.sh"]