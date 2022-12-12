# Day_11 [Memory-Forensics] Not all gifts are nice

+ Deployable Website: No
+ Deployable Container: Yes

Description: The elves in Santa's Security Operations Centre (SSOC) are hard at work checking their monitoring dashboards when Elf McDave, one of the workshop employees, knocks on the door. The elf says, "I've just clicked on something and now my workstation is behaving in all kinds of weird ways. Can you take a look?". Elf McSkidy tasks you, Elf McBlue, to investigate the workstation. Running down to the workshop floor, you see a command prompt running some code. Uh oh! This is not good. You immediately create a memory dump of the workstation and place this dump onto your employee-issued USB stick, returning to the SSOC for further analysis.

> IP = 10.10.223.8

## LEARNING OBJECTIVES

+ What is memory forensics?
+ Why is memory forensics useful?
+ Intro to the processes

## NOTES

1. What is Memory Forensics?
   1. Memory forensics is the analysis of the volatile memory that is in use when a computer is powered on.
   2. RAM is extremely quick and is the preferred method of storing and accessing data. However, it is limited compared to storage devices such as hard drives. This type of data is volatile because it will be deleted when the computer is powered off.
   3. We can analyse a computer's memory to see what applications (processes), what network connections were being made, and many more useful pieces of information.
2. Intro to Processes
   1. At the simplest, a process is a running program.

      ```text
         User Process -> These processes are programs that the user has launched. For example, text editors, web browsers, etc. -> notepad.exe, this is a text editor that is launched by the user.
         Background Process -> These processes are automatically launched and managed by the Operating System and are often essential to the Operating System behaving correctly. -> dwm.exe, this is an essential process for Windows that is responsible for displaying windows and applications on the computer.
      ```

3. Introducing Volatility
   1. Volatility is an open-source memory forensics toolkit written in Python.
   2. Some of the commands / flags include:

      ```text
         -f This argument is where you provide the name and location of the memory dump that you wish to analyse.
         -v This argument increases the verbosity of Volatility. This is sometimes useful to understand what Volatility is doing in cases of debugging.
         -p This argument allows you to override the default location of where plugins are stored.
         -o This argument allows you to specify where extracted processes or DLLs are stored.
      ```

## STEPS

1. Start machine

2. Download the workstation.vmem file for anaylsis on local machine
   1. > wget 10.10.253.182:9999/workstation.vmem

3. Using Volatility to Analyse an Image

   1. INTITIAL SCAN / GATHER INFO:
      1. We need to confirm the Operating System of the device that the memory has been captured from. We need to confirm this because it will determine what plugins we can use in our investigation. To do this we will use `imageinfo`
         1. > cd volitality3
         2. > python3 vol.py -f workstation.vmem windows.info > ../INFO-SCAN
            1. Download this INFO-SCAN
            2. In this scan we can see
               1. `NtProductType: NtProductWinNt`
               2. `NtVersion: 10`
      2. Now that we know it is __ we can use these plugins

         ```text
            windows.pslist -> This plugin lists all of the processes that were running at the time of the capture. To discover what processes were running on the system.
            windows.psscan -> This plugin allows us to analyse a specific process further. To discover what a specific process was actually doing.
            windows.dumpfiles -> This plugin allows us to export the process, where we can perform further analysis (i.e. static or dynamic analysis). To export a specific binary that allows us further to analyse it through static or dynamic analysis.
            windows.netstat   -> This plugin lists all network connections at the time of the capture. To understand what connections were being made. For example, was a process causing the computer to connect to a malicious server? We can use this IP address to implement defensive measures on other devices. For example, if we know an IP address is malicious, and another device is communicating with it, then we know that device is also infected.
         ```

   2. WINDOWS.PSLIST
      1. > python3 vol.py -f workstation.vmem windows.pslist > ../PSLIST-SCAN
         1. Download the PSLIST-SCAN
         2. Examining the scan we can see a special binary left behind `mysterygift.exe`
         3. The PID of this file is `2040`

   3. WINDOWS.PSSCAN
      1. > python3 vol.py -f workstation.vmem windows.psscan > ../PS-SCAN
         1. Download the PS-SCAN
         2. Examining the scan we can see a special binary left behind `mysterygift.exe`
         3. The PID of this file is `2040`

   4. WINDOWS.DUMPFILES
      1. > python3 vol.py -f workstation.vmem windows.dumpfiles --pid 2040
      2. This will dump all the files from the PID of mysterygift.exe
      3. Counting the number of files we can see there are `` files

         ```text
            ImageSectionObject0xc00912e1f1f0mysterygift.exefile.0xc00912e1f1f0.0xc009119ab9b0.ImageSectionObject.mysterygift.exe.img
            ImageSectionObject0xc0090e8b9b50kernel32.dllfile.0xc0090e8b9b50.0xc0090bb58d70.ImageSectionObject.kernel32.dll.img
            ImageSectionObject0xc0090f3b7a50dnsapi.dllfile.0xc0090f3b7a50.0xc0090f3a4c40.ImageSectionObject.dnsapi.dll.img
            ImageSectionObject0xc0090fe50630FWPUCLNT.DLLfile.0xc0090fe50630.0xc0090fdb7c80.ImageSectionObject.FWPUCLNT.DLL.img
            ImageSectionObject0xc0090fe56bc0rasadhlp.dllfile.0xc0090fe56bc0.0xc0090ff8ed30.ImageSectionObject.rasadhlp.dll.img
            ImageSectionObject0xc0090f3b8d10IPHLPAPI.DLLfile.0xc0090f3b8d10.0xc0090f3d6010.ImageSectionObject.IPHLPAPI.DLL.img
            ImageSectionObject0xc0090e8b8250KernelBase.dllfile.0xc0090e8b8250.0xc0090e579620.ImageSectionObject.KernelBase.dll.img
            ImageSectionObject0xc0090f3b78c0mswsock.dllfile.0xc0090f3b78c0.0xc0090f3d0c40.ImageSectionObject.mswsock.dll.img
            ImageSectionObject0xc0090e8b9ce0bcrypt.dllfile.0xc0090e8b9ce0.0xc0090e5786d0.ImageSectionObject.bcrypt.dll.img
            ImageSectionObject0xc0090ba9c6a0msvcrt.dllfile.0xc0090ba9c6a0.0xc0090bb54d70.ImageSectionObject.msvcrt.dll.img
            ImageSectionObject0xc0090e8dcb50advapi32.dllfile.0xc0090e8dcb50.0xc0090e7b4ce0.ImageSectionObject.advapi32.dll.img
            ImageSectionObject0xc0090e8dc6a0rpcrt4.dllfile.0xc0090e8dc6a0.0xc0090e511c50.ImageSectionObject.rpcrt4.dll.img
            ImageSectionObject0xc0090e774510ws2_32.dllfile.0xc0090e774510.0xc0090bb3e8a0.ImageSectionObject.ws2_32.dll.img
            ImageSectionObject0xc0090e774830nsi.dllfile.0xc0090e774830.0xc0090bb55d70.ImageSectionObject.nsi.dll.img
            ImageSectionObject0xc0090e6611f0ntdll.dllfile.0xc0090e6611f0.0xc0090bb84bb0.ImageSectionObject.ntdll.dll.img
            ImageSectionObject0xc0090ba9cce0sechost.dllfile.0xc0090ba9cce0.0xc0090e4d4bb0.ImageSectionObject.sechost.dll.img
         ```

## QUESTIONS

1. What is the Windows version number that the memory image captured? Note: this initial scan may take up towards 10 minutes to complete. Why not grab some water or stretch your legs?
   1. `10`
2. What is the name of the binary/gift that secret Santa left?
   1. `mysterygift.exe`
3. What is the Process ID (PID) of this binary?
   1. `2040`
4. Dump the contents of this binary. How many files are dumped?
   1. `16`
