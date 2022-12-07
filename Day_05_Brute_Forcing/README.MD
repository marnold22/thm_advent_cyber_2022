# Day_05 [Brute-Forcing] He knows when you're awake

+ Deployable Website: No
+ Deployable Container: Yes

Description: Elf McSkidy asked Elf Recon McRed to search for any backdoor that the Bandit Yeti APT might have installed. If any such backdoor is found, we would learn that the bad guys might be using it to access systems on Santa’s network.

## LEARNING OBJECTIVES

+ Learn about common remote access services.
+ Recognize a listening VNC port in a port scan.
+ Use a tool to find the VNC server’s password.
+ Connect to the VNC server using a VNC client.

## NOTES

1. Remote Access Services
   1. SSH - Secure Shell: It was initially used in Unix-like systems for remote login. It provides the user with a command-line interface (CLI) that can be used to execute commands.
   2. RDP - Remote Desktop Protocol: It provides a graphical user interface (GUI) to access an MS Windows system. When using Remote Desktop, the user can see their desktop and use the keyboard and mouse as if sitting at the computer.
   3. VNC - Virtual Network Computing: It provides access to a graphical interface which allows the user to view the desktop and (optionally) control the mouse and keyboard. VNC is available for any system with a graphical interface, including MS Windows, Linux, and even macOS, Android and Raspberry Pi.
2. Attacking Passwords
   1. **Shoulder Surfing**: Looking over the victim’s shoulder might reveal the pattern they use to unlock their phone or the PIN code to use the ATM. This attack requires the least technical knowledge.
   2. **Password Guessing**: Without proper cyber security awareness, some users might be inclined to use personal details, such as birth date or daughter’s name, as these are easiest to remember. Guessing the password of such users requires some knowledge of the target’s personal details; their birth year might end up as their ATM PIN code.
   3. **Dictionary Attack**: This approach expands on password guessing and attempts to include all valid words in a dictionary or a word list.
   4. **Brute Force Attack**: This attack is the most exhaustive and time-consuming, where an attacker can try all possible character combinations.

## LAUNCH THE INSTANCE

1. Start the machine
2. Let's spin up hydra to attack the password for `10.10.197.5`
   1. > hydra -P /opt/rockyou.txt vnc://10.10.197.5 -V
      1. RESPONSE: `[5900][vnc] host: 10.10.197.5   password: 1q2w3e4r`
      2. So now we know the password is `1q2w3e4r`
3. Now that we have the password let's connect to the VNC
   1. For this I will use REMMINA
      1. > remmina
      2. In here I will change it to VNC and input the IP `10.10.197.5`
      3. Input the password `1q2w3e4r`
      4. Once logged in we can see the flag on the desktop wallpaper
         1. `THM{I_SEE_YOUR_SCREEN}`

## QUESTIONS

1. Use Hydra to find the VNC password of the target with IP address MACHINE_IP. What is the password?
   1. `1q2w3e4r`
2. Using a VNC client on the AttackBox, connect to the target of IP address MACHINE_IP. What is the flag written on the target’s screen?
   1. `THM{I_SEE_YOUR_SCREEN}`
