# Day_19 [Hardware-Hacking] Wiggles go brrr

+ Deployable Website: No
+ Deployable Container: Yes

Description: Elf McSkidy was doing a regular sweep of Santa's workshop when he discovered a hardware implant! The implant has a web camera attached to a microprocessor and another chip. It seems like someone was planning something malicious... We must try to understand what this implant was trying to do! We will deal with the microprocessor and the web camera in future tasks; for now, let's try to uncover what that other chip is being used for. The elves realise that these chips probably use digital communication that can be intercepted with a Logic Analyser. Looking at the wires between the chips, we see a black wire connected to a pin called GND and a red wire connected to a pin called VIN. Elf Forensic knows from experience that this would be the Ground and Voltage IN wires, respectively, meaning these wires are used to provide power to the chip. That then leaves the green and purple wires that would be used for data transmission. Seeing that they are connected to the RX0 and TX0 pins, Elf Recon can deduce that this refers to the Transmit and Receive lines of USART communication. Hence we are pretty sure that the protocol used for communication is USART. Armed with this information, the elves connect the probes of the Logic Analyser to the green and purple wires before powering on the implant. Immediately, super-fast signals are seen on the analyser! The elves create a logic data dump from the signals, and McSkidy is asking you to investigate what is actually being transmitted!

> IP:

## LEARNING OBJECTIVES

+ How data is sent via electrical wires in low-level hardware
+ Hardware communication protocols
+ How to analyse hardware communication protocols
+ Reading USART data from a logic capture

## NOTES

1. The Electrical Heartbeat
   1. In the world of microchips, we often don't have this luxury. To make sure our communication protocols are efficient as possible, we need to keep them as simple as possible. To do that, we need to enter the world of 0s and 1s. This then begs the question, how does hardware take electricity and generate signals? In this task, we will focus on digital communication. For hardware communication, we use a device called a Logic Analyser to analyse the signals. This device can be connected to the actual electrical wires that are used for communication between two devices that will capture and interpret the signals being sent. In this task, we will use a logic analyser to determine the communication between two devices in the rogue implant.
   2. Back to our question, if we have electricity, how can we generate a digital signal? -> The Electrical Heartbeat
   3. The approach most hardware components take is to simply turn the power on and off. If the power is on, we are transmitting a digital 1. If the power is off, we are transmitting a digital 0. We call these 1s and 0s bits. To perform communication, we simply turn the power on and off in a specific sequence to transmit a bunch of 0s and 1s. If we send 8 bits, we are sending a single byte!

2. **USART**
   1. Universal Synchronous/Asynchronous Receiver-Transmitter (USART) communication, or as it is better known, serial communication, is a protocol that uses two wires. One wire is used to transmit (TX) data from device A to device B, and the other wire is used to receive (RX) data on device A from device B. In essence, we connect the transmit port from one device to the receive port from the other device and vice versa.
   2. What is interesting about this protocol is that there is no clock line that synchronises the communication of the devices. Without a clock, the devices have to agree to the configuration of communication, such as the following:
      1. Communication speed - This is also called the baud rate or bit rate and dictates how fast bytes of data can be sent. Agreeing to a specific rate tells each device how fast it should sample the data to get accurate communication. While there are fixed standards for baud rates, devices can choose to use any other rate as long as both devices support it.
      2. Bits per transmission - This is normally set to 8 bits which makes a byte, but it can be configured to something else, such as 16 bits.
      3. Stop and Start bits - Since there is no clock, one device has to send a signal to the other device before it can send or end a data transmission. The configuration of the start and stop bits dictate how this is done.
      4. Parity bits - Since there can be errors in the communication, parity bits can be used to detect and correct such errors in the transmission.
   3. There are a couple of caveats, however. The devices don't really have a way to determine if the other device is ready for communication. To solve this, some USART connections will use two additional lines called Clear To Send (CTS) and Request to Send (RTS) to communicate to the other device whether it is ready to receive or ready to transmit. Furthermore, to agree upon what voltage level is a binary 1 or 0, a third wire called the Ground (GND) wire is required to allow the devices to have the same voltage reference.
   4. However, despite all of this, USART is an incredibly popular protocol in microprocessors due to its simplicity.

3. **SPI**
   1. The Serial Peripheral Interface (SPI) communication protocol is mainly used for communication between microprocessors and small peripherals such as a sensor or an SD card. While USART communication has the clock built into the TX and RX lines, SPI uses a separate clock wire. Separating the clock (SCK) from the data (DATA) line allows for synchronous communication, which is faster and more reliable. So the trade-off is adding an additional wire, but we gain a speed and reliability boost.
   2. The clock line tells the receiving device exactly when it needs to read the data line. Two-way communication is also possible, but quite a bit more complex than serial communication. Essentially, one of the devices is labelled the controller. This is the only device that is allowed to send clock signals. All other devices become secondary devices that must follow the controller's clock signal to transmit data back. If two-way communication is used, instead of having a single data line, two lines are used, namely Peripheral-In Controller-Out (PICO), which means communication is sent from the controller, and Peripheral-Out Controller-In (POCI), which means communication is sent from the secondary device back to the controller. Using this, the controller sends a clock signal and a command out to the device using the PICO line and then keeping the clock signal, the controller receives data back on the PICO line
   3. There is one additional change that can be made. While there can only be one controller, there can be multiple secondary devices. To save wires and ports, all devices can use the same SCK, PICO, and POCI lines. A fourth wire, called the Chip Select (CS) wire, is used to distinguish the device that the communication is meant for. The controller can use this line to indicate to the specific device that it wants to communicate to it
   4. SPI communication is a fair bit more complex than USART, but having a dedicated clock line increases the speed at which we can communicate and improves reliability.

4. **I2C**
   1. The Inter-Integrated Circuit (I2C) communication protocol was created to deal with the drawbacks of both the USART and SPI communication protocols. Because USART is asynchronous and has the clock built into the transmit and receive lines, devices have to agree ahead of time on the configuration of communication. Furthermore, speeds are reduced to ensure communication remains reliable. On the other hand, while SPI is faster and more reliable, it requires many more wires for communication, and every single additional peripheral requires one more Chip Select wire.
   2. I2C attempts to solve these problems. Similar to USART, I2C only makes use of two lines for communication. I2C uses a Serial Data (SDA) line and Serial Clock (SCL) line for communication. However, instead of using a Chip Select wire to determine which peripheral is being communicated to, I2C uses an Address signal that is sent on the SDA wire. This Address tells all controllers and peripherals which device is trying to communicate and to which device it is trying to communicate to. Once the signal is sent, a Data signal can be used to send the actual communication. To notify other controllers and peripherals that communication is taking place and prevent these devices from talking over each other, a Start and Stop signal is used. Each device can monitor these Start and Stop signals to determine if the lines are busy with communication.
   3. Since an external clock line is used, communication is still faster and more reliable than USART, and while it is slightly slower than SPI, the use of the Address signal means up to 1008 devices can be connected to the same two lines and will be able to communicate.

5. Probing the Logic
   1. Based on the diagram, it seems like there is a microprocessor that is connected to an ESP32 chip. Doing some research, we can see that the ESP32 chips allow microprocessors to communicate over WiFi and Mobile networks. So whatever this implant was doing, it was definitely communicating with someone else.
6. Analyzing the Logic
   1. In order to analyse the logic data dump, we will need to use a logic analyser tool called Saleae <https://www.saleae.com/>.

## STEPS

1. Start machine
2. Launch the 'Logic 2' application
3. Import the 'santa' data dump file to the software
   1. *Ignore the calibration popup error*
4. D0 and D1 refer to the digital channels of the two lines that were probed
5. A0 and A1 refer to the analogue data from the probers
6. Hover your mouse over the first thick line on D1 channel 1 and use Left-Ctrl and the mouse wheel to zoom in again; you should be able to see the entire signal transfer
7. What is very interesting from this screen is that you can see how the analogue voltage data corresponds to the digital signal that is seen. Looking at the A1 Channel 1 vs D1 Channel 1, you can see that there are slight breaks in the analogue data that have been corrected in the digital channel. Now that we can see the digital signal data, we can look to use a logic analyser to read the contents of the data. Click on the Analyzers tab to the right
8. Since we know the protocol is USART, let's look to configure an Async Serial analyser for both Channel 1 and 0. Let's configure Channel 1's analyser first. If we were true hardware reverse engineers, we would first have to figure out the rate at which data is being transmitted as well as the specific configuration such as parity bits and frames. However, to keep this simple Forensic Elf has already discovered this for you. Alter your configuration to match the following and click Save
9. Once saved, we can see that the data is being analysed. Click the little terminal Icon, and we can actually read the data being transmitted!
10. We see the initialisation sequence of the serial line and then three lines of data being sent:
    1. ACK REBOOT
    2. CMDX195837
    3. 9600
11. This doesn't yet mean anything since we are only seeing one side of the data. In order to see the other messages, we need to add another analyser to Channel 0. Click the plus icon next to Analysers and add another Async Serial analyser with the same configuration, except for Channel 0
12. Once added, click the Trash icon in the bottom right to remove all terminal data. Once done, click on the three dots next to each analyser and select Restart on both of them.
13. Now we are starting to piece together the information! It seems like the microprocessor is establishing a session with the ESP32 device to allow communication to the control server! We can now see the full discussion between the two devices. The processor asks the ESP32 to reboot its connection to the control server. The ESP32 is happy to oblige but requests a security code to be sent to allow connection to the control server. Once the security code has been transmitted, the ESP32 allows the microprocessor to negotiate a new baud rate to be used for communication. Interestingly, once this new baud rate is accepted, we cannot read the rest of the output! However, since we intercepted the baud rate, we can simply edit our Channel 0 analyser to a new baud rate to read the rest of the communication that was sent. Go make this change to get your flag!

## QUESTIONS

1. What device can be used to probe the signals being sent on electrical wires between two devices?
   1. `Logic Analyser`
2. USART is faster than SPI for communication? (Yea,Nay)
   1. `Nay`
3. USART communication uses fewer wires than SPI? (Yea,Nay)
   1. `Yea`
4. USART is faster than I2C for communication? (Yea,Nay)
   1. `Nay`
5. I2C uses more wires than SPI for communication? (Yea,Nay)
   1. `Nay`
6. SPI is faster than I2C for communication? (Yea,Nay)
   1. `Yea`
7. What is the maximum number of devices that can be connected on a single pair of I2C lines?
   1. `1008`
8. What is the new baud rate that is negotiated between the microprocessor and ESP32 chip?
   1. ``
9. What is the flag that is transmitted once the new baud rate was accepted?
   1. ``
