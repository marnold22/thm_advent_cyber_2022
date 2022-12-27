def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        payload = msg.payload.decode()
        topic = msg.topic
        print("Topic:", topic)
        print("Payload:", payload)
        print("Parsing payload...")
        payload = payload.replace("{", "")
        payload = payload.replace("}", "")
        payload = payload.split(",")
        CMD = 0
        URL = 1
        command_payload = payload[CMD]
        url_payload = payload[URL]
        print(command_payload)
        print(url_payload)
        target_cmd = "10"
        CMD_NAME = 0
        CMD_VALUE = 1
        URL_NAME = 0
        URL_VALUE = 1
        command_payload = command_payload.split(":")
        url_payload = url_payload.split(":", 1)
        if command_payload[CMD_NAME].lower() == "cmd":
            if command_payload[CMD_VALUE] == target_cmd:
                print("Command value match")
                if url_payload[URL_NAME].lower() == "url":
                    print("RTSPS URL match:", url_payload[URL_VALUE])
                    try:
                        f = open("../src/url.txt", "x")
                        f.write(url_payload[URL_VALUE])
                        f.close()
                    except:
                        f = open("../src/url.txt", "w")
                        f.write(url_payload[URL_VALUE])
                        f.close()
                        
                    subprocess.call("../deploy/update.sh")

    client.subscribe(topic)
    client.on_message = on_message 