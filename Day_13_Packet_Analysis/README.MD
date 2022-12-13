# Day_13 [Packet-Analysis] Simply having a wonderful pcap time

+ Deployable Website: No
+ Deployable Container: Yes

Description: After receiving the phishing email on Day 6 and investigating malware on Day 12, it seemed everything was ready to go back to normal. However, monitoring systems started to show suspicious traffic patterns just before closing the case. Now Santa's SOC team needs help in analysing these suspicious network patterns.

> IP = 10.10.153.197

## LEARNING OBJECTIVES

+ Learn what traffic analysis is and why it still matters.
+ Learn the fundamentals of traffic analysis.
+ Learn the essential Wireshark features used in case investigation.
+ Learn how to assess the patterns and identify anomalies on the network.
+ Learn to use additional tools to identify malicious addresses and conduct further analysis.
+ Help the Elf team investigate suspicious traffic patterns.

## NOTES

+ What are packets?
  + **Packets** are the most basic unit of the network data transferred over the network.
+ Why is packet analysis important?
  + Network traffic is a pure and rich data source. A Packet Capture (PCAP) of network events provides a rich data source for analysis. Capturing live data can be focused on traffic flow, which only provides statistics on the network traffic.
+ Points to consider when analyzing PCAP files
  + Network and standard protocols knowledge.
  + Familiarity with attack and defence concepts.
  + Practical experience in analysis tools.
+ Useful tool for analysis: WIRESHARK

## STEPS

1. Start machine
2. Download the PCAP file to local machine for analysis
   1. > wget 10.10.153.197:8000/"AoC'22-Day13.pcapng"
3. Open PCAP file in WireShark
4. WIRESHARK ANALYSIS
   1. Click 'Statistics' -> 'Protocol Heirarchy'
      1. Under Hypertext Transfer Protocol
         1. In here we see `0.3` for Percent-Packets
   2. Click 'Statistics' -> 'Conversations'
      1. Click on TCP tab
         1. In here we see 1125 packets sent to port `3389`
         2. This port is normally associated with `RDP`
   3. Go back to main menu
   4. Click the 'Protocol' column to sort
      1. Search for DNS
         1. There are 4 entries and in here we see the following DNS's:
            1. `cdn.bandityeti.thm`
            2. `bestfestivalcompany.thm`
         2. Lets DEFANG these
            1. `bandityeti[.]thm`
            2. `bestfestivalcompany[.]thm`
      2. Search for HTTP
         1. There are 4 eneteries and in here we see the following:
            1. `GET /mysterygift.exe`
            2. `GET /favicon.ico`
         2. Lets DEFANG these
            1. `mysterygift[.]exe`
            2. `favicon[.]ico`
      3. Looking at the same HTTP cluster if we look at the source column we can see IP addresses that connected for this packet
         1. Open the packet
            1. Under SOURCE we see:
               1. `10.10.29.186`
               2. DEFANG
               3. `10[.]10[.]29[.]186`
            2. Under HOST we see:
               1. `cdn.bandityeti.thm`
               2. DEFANG
               3. `cdn[.]bandityeti[.]thm`
            3. Under USER-AGENT we see:
               1. `Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.17763.592`
               2. `Nim httpclient/1.6.8`
   5. EXPORT OBJECTS
      1. Click 'File' -> 'Export Objects' -> 'HTTP'
      2. THis brings up the window that shows both files previously mentioned
      3. Click 'Save All'
   6. HASH-BASED FILE REPUTATION
      1. Now we have the 2 files lets check thier SHA256SUM
         1. > sha256sum mysterygift.exe
            1. `0ce160a54d10f8e81448d0360af5c2948ff6a4dbb493fe4be756fc3e2c3f900f`
      2. Now run this through virustotal
         1. Search based on hash '0ce160a54d10f8e81448d0360af5c2948ff6a4dbb493fe4be756fc3e2c3f900f'
         2. Click behavior tab
         3. Under IP-TRAFFIC we see:
            1. 20.99.133.109:443 (TCP)
            2. 20.99.184.37:443 (TCP)
            3. 23.216.147.76:443 (TCP)
            4. 8.8.8.8:53 (UDP)
            5. a83f:8110:0:0:10:0:0:0:53 (UDP)
         4. Of these we only want the connected ones (TCP)
         5. DEFANG
            1. `20[.]99[.]133[.]109`
            2. `20[.]99[.]184[.]37`
            3. `23[.]216[.]147[.]76`

## QUESTIONS

1. View the "Protocol Hierarchy" menu. What is the "Percent Packets" value of the "Hypertext Transfer Protocol"?
   1. `0.3`
2. View the "Conversations". Navigate to the TCP section. Which port number has received more than 1000 packets?
   1. `3389`
3. What is the service name of the used protocol that received more than 1000 packets?
   1. `RDP`
4. Filter the DNS packets. What are the domain names? Enter the domains in alphabetical order and defanged format. (format: domain[.]zzz,domain[.]zzz)
   1. `bandityeti[.]thm,bestfestivalcompany[.]thm`
5. Filter the HTTP packets. What are the names of the requested files? Enter the names in alphabetical order and in defanged format. (format: file.xyz,file.xyz)
   1. `favicon[.]ico,mysterygift[.]exe`
6. Which IP address downloaded the executable file? Enter your answer in defanged format.
   1. `10[.]10[.]29[.]186`
7. Which domain address hosts the malicious file? Enter your answer in defanged format.
   1. `cdn[.]bandityeti[.]thm`
8. What is the "user-agent" value used to download the non-executable file?
   1. `Nim httpclient/1.6.8`
9. Export objects from the PCAP file. Calculate the file hashes. What is the sha256 hash value of the executable file?
   1. `0ce160a54d10f8e81448d0360af5c2948ff6a4dbb493fe4be756fc3e2c3f900f`
10. Search the hash value of the executable file on Virustotal. -> Navigate to the "Behaviour" section. There are multiple IP addresses associated with this file. What are the connected IP addresses? Enter the IP addressed defanged and in numerical order. (format: IPADDR,IPADDR)
    1. `20[.]99[.]133[.]109,20[.]99[.]184[.]37,23[.]216[.]147[.]76`
