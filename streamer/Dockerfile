FROM debian:11.1-slim

# No interactive frontend during docker build
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get -y install gstreamer1.0-tools gstreamer1.0-plugins-good

COPY scripts/run.sh /root/run.sh
CMD ["/root/run.sh"]
