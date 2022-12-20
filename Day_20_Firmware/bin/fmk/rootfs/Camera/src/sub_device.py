"""
An MQTT subscriber using the Paho Python library to subscribe to device/init to obtain a device ID from an MQTT broker.
Author: Dominic Cunningham
"""

import random
from paho.mqtt import client as mqtt_client

broker = "127.0.0.1"
port = 1883
topic = "device/init"
client_id = f"{random.randint(0,100)}"


def connect_mqtt() -> mqtt_client:
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            print("Connected to MQTT Broker")
        else:
            print("Failed to connect, return code %d\n", rc)

    client = mqtt_client.Client(client_id)
    client.on_connect = on_connect
    client.connect(broker, port)
    return client


def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        global message
        global device_topic
        payload = msg.payload.decode()
        topic = msg.topic
        device_topic = topic
        message = payload
        print("Device ID", message, "obtained from", device_topic)
        try:
            f = open("../src/id.txt", "x")
            f.write(message)
            f.close()
        except:
            f = open("../src/id.txt", "w")
            f.write(message)
            f.close()

    client.subscribe(topic)
    client.on_message = on_message


def main():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()


if __name__ == "__main__":
    main()
