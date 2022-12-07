# Day_03 [Scanning] Scanning through the snow

+ Deployable Website: No
+ Deployable Container: Yes

Description: During the investigation of the downloaded GitHub repo (OSINT task), elf Recon McRed identified a URL qa.santagift.shop that is probably used by all the elves with admin privileges to add or delete gifts on the Santa website. The website has been pulled down for maintenance, and now Recon McRed is scanning the server to see how it's been compromised. Can you help McRed scan the network and find the reason for the website compromise?

Let's connect to the Samba service using the credentials we found through the source code (OSINT task). Type the following command smb://MACHINE_IP in the address bar and use the following username and password:
Username: `ubuntu`
Password: `S@nta2022`

## NOTES

Scanning Techniques

1. Network Scanning
   + A network is usually a collection of interconnected hosts or computers to share information and resources. Network scanning helps to discover and map a complete network, including any live computer or hosts, open ports, IP addresses, and services running on any live host and operating system. Once the network is mapped, an attacker executes exploits as per the target system and services discovered. For example, a computer in a network with an outdated Apache version enables an attacker to launch an exploit against a vulnerable Apache server.
2. Port Scanning
   + Per Wikipedia, "In computer networking, a port is a number assigned to uniquely identify a connection endpoint and to direct data to a specific service. At the software level, within an operating system, a port is a logical construct that identifies a specific process or a type of network service". 
   + Port scanning is a conventional method to examine open ports in a network capable of receiving and sending data. First, an attacker maps a complete network with installed devices/ hosts like firewalls, routers, servers etc., then scans open ports on each live host. Port number varies between 0 to 65,536 based on the type of service running on the host. Port scanning results fall into the following three categories:
     + Closed Ports: The host is not listening to the specific port.
     + Open Ports: The host actively accepts a connection on the specific port.
     + Filtered Ports: This indicates that the port is open; however, the host is not accepting connections or accepting connections as per certain criteria like specific source IP address.
3. Vulnerability Scanning
   + The vulnerability scanning proactively identifies the network's vulnerabilities in an automated way that helps determine whether the system may be threatened or exploited. Free and paid tools are available that help to identify loopholes in a target system through a pre-build database of vulnerabilities. Pentesters widely use tools such as Nessus and Acunetix to identify loopholes in a system.

## LAUNCH THE INSTANCE

1. Start the machine
2. > nikto -host 10.10.172.227

   ```nikto
   - Nikto v2.1.6
   ---------------------------------------------------------------------------
   + Target IP:          10.10.172.227
   + Target Hostname:    10.10.172.227
   + Target Port:        80
   + Start Time:         2022-12-05 12:08:07 (GMT-8)
   ---------------------------------------------------------------------------
   + Server: Apache/2.4.29 (Ubuntu)
   + The anti-clickjacking X-Frame-Options header is not present.
   + The X-XSS-Protection header is not defined. This header can hint to the user agent to protect against some forms of XSS
   + The X-Content-Type-Options header is not set. This could allow the user agent to render the content of the site in a different fashion to the MIME type
   + No CGI Directories found (use '-C all' to force check all possible dirs)
   + Server may leak inodes via ETags, header found with file /, inode: 2aa6, size: 5eca7b0d75572, mtime: gzip
   + Apache/2.4.29 appears to be outdated (current is at least Apache/2.4.37). Apache 2.2.34 is the EOL for the 2.x branch.
   ```

3. In the Nikto scan we can seethe Server is `apache`
4. > nmap -sS 10.10.172.227 > initial/nmap
   1. In our nmap scan we can see the following ports availible:
      1. 22/tcp  open  ssh
      2. 80/tcp  open  http
      3. 139/tcp open  netbios-ssn
      4. 445/tcp open  microsoft-ds
5. Now let's connect to the SMB share
   1. Open file browser
   2. In address bar connect to `smb://IP_ADDRESS` & enter the username/password
   3. Once connected I can see 3 main folders (let's start in admin)
      1. admins
      2. print
      3. sambashare
6. Inside of admins we see:
   1. flag.txt
   2. userlist.txt
   3. Let's download these to explore
7. In the flag.txt we get:
   1. `{THM_SANTA_SMB_SERVER}`
8. In the userslist.txt we get:
   1. `santahr:santa25`

## QUESTIONS

1. What is the name of the HTTP server running on the remote host?
   1. `apache`
2. What is the name of the service running on port 22 on the QA server?
   1. `ssh`
3. What flag can you find after successfully accessing the Samba service?
   1. `{THM_SANTA_SMB_SERVER}`
4. What is the password for the username santahr?
   1. `santa25`
