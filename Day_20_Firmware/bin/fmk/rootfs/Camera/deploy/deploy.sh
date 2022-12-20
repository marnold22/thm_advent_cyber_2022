python3 ../src/pub_device.py &
sleep 5
python3 ../src/sub_device.py &
sleep 30
python3 ../src/pub_rtsp.py &
sleep 5
