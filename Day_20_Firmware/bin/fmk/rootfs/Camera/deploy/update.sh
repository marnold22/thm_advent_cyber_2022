#!/bin/bash
name="../src/url.txt"

content=$(cat "$name")
echo "$content"
ffmpeg -re -stream_loop -1 -i ../src/sample-mp4-file-small.mp4 -c copy -f rtsp "$content" &
