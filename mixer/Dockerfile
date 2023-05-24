FROM debian:11.1-slim

# No interactive frontend during docker build
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get install -y xvfb x11vnc fluxbox
RUN apt-get install -y xterm supervisor jackd
RUN apt-get install -y apt-transport-https gpgv wget

RUN wget https://launchpad.net/~kxstudio-debian/+archive/kxstudio/+files/kxstudio-repos_11.1.0_all.deb
RUN dpkg -i kxstudio-repos_11.1.0_all.deb
RUN apt-get update
RUN apt-get -y install non-mixer catia 
RUN apt-get -y install mpv git darkice
RUN apt-get -y install procps

RUN     git clone --branch master --single-branch https://github.com/novnc/noVNC.git /opt/novnc; \
        git clone --branch v0.11.0 --single-branch https://github.com/novnc/websockify.git /opt/novnc/utils/websockify; \
        ln -s /opt/novnc/vnc.html /opt/novnc/index.html



ENV TERM xterm

RUN mkdir -p /var/log/supervisor
RUN chmod 777 /var/log/supervisor

COPY non-mixer/* /root/non-mixer/
COPY scripts/run.sh /root/run.sh
COPY scripts/darkice-with-music.cfg /root/darkice-with-music.cfg
COPY scripts/darkice-without-music.cfg /root/darkice-without-music.cfg

COPY scripts/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/root/run.sh"]
