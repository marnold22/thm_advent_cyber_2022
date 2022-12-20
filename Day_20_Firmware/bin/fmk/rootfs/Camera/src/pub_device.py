"""
An MQTT publisher using the Paho Python library to publish a device ID to an MQTT broker.
Author: Dominic Cunningham
"""
import random
import string
import time
from paho.mqtt import client as mqtt_client

broker = "127.0.0.1"
port = 1883
topic = "device/init"
client_id = f"{random.randint(0,1000)}"


def generate_id():
    random.seed(0)
    return "".join(
        random.SystemRandom().choice(string.ascii_uppercase + string.digits)
        for _ in range(20)
    )


id = generate_id()


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


def publish(client: mqtt_client):
    while True:
        time.sleep(10)
        msg = f"{id}"
        result = client.publish(topic, msg)
        status = result[0]
        if status == 0:
            print(f"Send `{msg}` to topic `{topic}`")
        else:
            print(f"Failed to send message to topic {topic}")


def main():
    client = connect_mqtt()
    client.loop_start()
    publish(client)


if __name__ == "__main__":
    main()
