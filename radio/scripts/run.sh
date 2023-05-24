#!/bin/bash
sleep 15
mpv --ao=jack ${RADIO_URL} --jack-port="Non-Mixer/AutoMusik"
