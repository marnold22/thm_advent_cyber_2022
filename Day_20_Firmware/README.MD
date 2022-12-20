# Day_20 [Firmware] Binwalkin’ around the Christmas tree

+ Deployable Website: No
+ Deployable Container: Yes

Description: We can now learn more about the mysterious device found in Santa's workshop. Elf Forensic McBlue has successfully been able to find the device ID. Now that we have the hardware device ID, help Elf McSkidy reverse the encrypted firmware and find interesting endpoints for IoT exploitation.

> IP: 10.10.90.154
> Use Password: `Santa1010` if sudo is needed

## LEARNING OBJECTIVES

+ What is firmware reverse engineering
+ Techniques for extracting code from the firmware
+ Extracting hidden keys from an encrypted firmware
+ Modifying and rebuilding a firmware

## NOTES

1. What is Firmware Reverse Engineering?
   1. Every embedded system, such as cameras, routers, smart watches etc., has pre-installed firmware, which has its own set of instructions running on the hardware's processor. It enables the hardware to communicate with other software running on the device. The firmware provides low-level control for the designer/developer to make changes at the root level.
   2. Reverse engineering is working your way back through the code to figure out how it was built and what it does. Firmware reverse engineering is extracting the original code from the firmware binary file and verifying that the code does not carry out any malicious or unintended functionality like undesired network communication calls. Firmware reversing is usually done for security reasons to ensure the safe usage of devices that may have critical vulnerabilities leading to possible exploitation or data leakage. Consider a smart watch whose firmware is programmed to send all incoming messages, emails etc., to a specific IP address without any indication to the user.

2. Firmware Reversing Steps
   1. The firmware is first obtained from the vendor's website or extracted from the device to perform the analysis.
   2. The obtained/extracted firmware, usually a binary file, is first analysed to figure out its type (bare metal or OS based).
   3. It is verified that the firmware is either encrypted or packed. The encrypted firmware is more challenging to analyse as it usually needs a tricky workaround, such as reversing the previous non-encrypted releases of the firmware or performing hardware attacks like Side Channel Attacks (SCA) to fetch the encryption keys.
   4. Once the encrypted firmware is decrypted, different techniques and tools are used to perform reverse engineering based on type.

3. Types of Firmware Analysis
   1. Firmware analysis is carried out through two techniques, Static & Dynamic.

4. STATIC ANALYSIS
   1. Static analysis involves an essential examination of the binary file contents, performing its reverse engineering, and reading the assembly instructions to understand the functionality. This is done through multiple commonly used command line utilities and binary analysis tools such as:
      1. **BinWalk**: A firmware extraction tool that extracts code snippets inside any binary by searching for signatures against many standard binary file formats like zip, tar, exe, ELF, etc. Binwalk has a database of binary header signatures against which the signature match is performed. The common objective of using this tool is to extract a file system like Squashfs, yaffs2, Cramfs, ext*fs, jffs2, etc., which is embedded in the firmware binary. The file system has all the application code that will be running on the device.
      2. **Firmware ModKit** (FMK): FMK is widely used for firmware reverse engineering. It extracts the firmware using binwalk and outputs a directory with the firmware file system. Once the code is extracted, a developer can modify desired files and repack the binary file with a single command.
      3. **FirmWalker**: Searches through the extracted firmware file system for unique strings and directories like etc/shadow, etc/passwd, etc/ssl, special keywords like admin, root, password, etc., vulnerable binaries like ssh, telnet, netcat etc.

5. DYNAMIC ANALYSIS
   1. Firmware dynamic analysis involves running the firmware code on actual hardware and observing its behaviour through emulation and hardware/ software based debugging. One of the significant advantages of dynamic analysis is to analyse unintended network communication for identifying data pilferage. The following tools are also commonly used for dynamic analysis:
      1. **Qemu**: Qemu is a free and open-source emulator and enables working on cross-platform environments. The tool provides various ways to emulate binary firmware for different architectures like Advanced RISC Machines (ARM), Microprocessors without Interlocked Pipelined Stages (MIPS), etc., on the host system. Qemu can help in full-system emulation or a single binary emulation of ELF (Executable and Linkable Format) files for the Linux system and many different platforms.
      2. **Gnu DeBugger (GDB)**: GDB is a dynamic debugging tool for emulating a binary and inspecting its memory and registers. GDB also supports remote debugging, commonly used during firmware reversing when the target binary runs on a separate host and reversing is carried out from a different host.

## STEPS

1. Start machine
2. Download file to local machine for analysis
3. ANALYSIS
   1. Step 1: Verifying Encryption
      1. Verify whether the binary firmwarev2.2-encrypted.gpg is encrypted through file entropy analysis
         1. > binwalk -E -N firmwarev2.2-encrypted.gpg
            1. RESPONSE: Rising entropy edge (0.989903)
            2. The rising entropy edge means that the file is probably encrypted and has increased randomness
   2. Step 2: Finding Unencrypted Older Version
      1. Since the latest version is encrypted, McSkidy found an older version of the same firmware. The version is located in the bin-unsigned folder. Why was she looking for an older version? Because she wants to find encryption keys that she may use to decrypt the original firmware and reverse engineer it. McSkidy has decided to use the famous FMK tool for this purpose.
         1. > cd bin-unsigned
         2. > ~/TOOLS/firmware-mod-kit/extract-firmware.sh firmwarev1.0-unsigned
   3. Step 3: Finding Encryption Keys
      1. The original firmware is gpg protected, which means that we need to find a public, and private key and a paraphrase to decrypt the originally signed firmware. We know that the unencrypted firmware is extracted successfully and stored in the fmk folder. The easiest way to find keys is by using the grep command. The -i flag in the grep command ignores case sensitivity while the -r operator recursively searches in the current directory and subdirectories.
         1. > grep -ir key
            1. RESPONSE:

               ```text
                  fmk/rootfs/gpg/private.key:-----BEGIN PGP PRIVATE KEY BLOCK-----
                  fmk/rootfs/gpg/private.key:-----END PGP PRIVATE KEY BLOCK-----
                  fmk/rootfs/gpg/public.key:-----BEGIN PGP PUBLIC KEY BLOCK-----
                  fmk/rootfs/gpg/public.key:-----END PGP PUBLIC KEY BLOCK-----
               ```

            2. In here we see the gpg private key & public key
      2. Now we need the password or paraphrase
         1. > grep -ir paraphrase
            1. RESPONSE: fmk/rootfs/gpg/secret.txt:PARAPHRASE: `Santa@2022`
            2. BINGO! Now we can use this to decrypt the encrypted file
   4. Step 4: Decrypting the Encrypted Firmware
      1. Now that we have the keys lets import them
         1. PRIVATE-KEY
            1. > gpg --import fmk/rootfs/gpg/private.key
            2. Enter the password found earlier
         2. PUBLIC-KEY
            1. > gpg --import fmk/rootfs/gpg/public.key
         3. List Secret Keys
            1. > gpg --list-secret-keys
      2. Decrypt the Firmware
         1. > cd ../bin
         2. > gpg firmwarev2.2-encrypted.gpg
         3. Enter password from before
         4. > ls -l
            1. RESPONSE: firmwarev2.2-encrypted, firmwarev2.2-encrypted.gpg
            2. We now have the decrypted file (non .gpg file)
   5. Step 5: Reversing the Original Encrypted Firmware
      1. This is the simplest step, and we can use binwalk or FMK to extract code from the recently unencrypted firmware. In this example, we will be using FMK to extract the code.
         1. > ~/TOOLS/firmware-mod-kit/extract-firmware.sh firmwarev2.2-encrypted
         2. SUCCESS!
      2. The Camera folder in the fmk/rootfs directory will contain all the necessary files we will be using in the next task.
4. FLAG
   1. Navigate to fmk/rootfs
   2. > cat flag.txt
      1. RESPONSE: `THM{WE_GOT_THE_FIRMWARE_CODE}`
   3. Looking for build number
      1. > grep -ir build
         1. `lib/pkgconfig/libiptc.pc:prefix=/workspace/jenkins/workspace/model_qca/build/../rootfs.build.2.6.31`
            `lib/pkgconfig/xtables.pc:prefix=/workspace/jenkins/workspace/model_qca/build/../rootfs.build.2.6.31`
         2. Bingo! We know the build number is `2.6.31`

## QUESTIONS

1. What is the flag value after reversing the file firmwarev2.2-encrypted.gpg? Note: The flag contains underscores - if you're seeing spaces, the underscores might not be rendering.
   1. `THM{WE_GOT_THE_FIRMWARE_CODE}`
2. What is the Paraphrase value for the binary firmwarev1.0_unsigned?
   1. `Santa@2022`
3. After reversing the encrypted firmware, can you find the build number for rootfs?
   1. `2.6.31`
