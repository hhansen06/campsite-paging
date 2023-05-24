FROM debian:11.1-slim

# No interactive frontend during docker build
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get -y install mpv
RUN apt-get install asterisk php php-mysql php-curl -y

COPY scripts/*.php /root/

COPY scripts/run.sh /root/run.sh
CMD ["/root/run.sh"]
