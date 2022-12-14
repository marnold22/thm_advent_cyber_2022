# Day_14 [Web-Applications] I'm dreaming of secure web apps

+ Deployable Website: No
+ Deployable Container: Yes

Description: Elf McSkidy was sipping her coffee when she saw on her calendar that it was time to review the web application’s security. An internal web application is being developed to be used internally and manage the cyber security team. She calls Elf Exploit McRed and asks him to check the in-development web application for common vulnerabilities. Elf Exploit McRed discovers that the local web application suffers from an Insecure Direct Object References (IDOR) vulnerability. After doing some tests, Elf Exploit McRed was able to find multiple IDOR vulnerabilities. After logging in, a user can access the profile pages of other users. Moreover, files can be accessed by guessing their sequential number.

> IP: 10.10.108.202
> UN: mcskidy
> PW: devtest

## LEARNING OBJECTIVES

+ Web Applications
+ The Open Web Application Security Project (OWASP) Top 10
+ IDOR

## NOTES

+ Web Application
  + A web application is a piece of software that we can use via a web browser. Unlike computer programs and smartphone applications, a web application does not require any installation on the user’s system to make use of it. To use a web application, we only need a web browser
  + Examples:
    + Webmail: Examples include Tutanota, ProtonMail, StartMail, and Gmail.
    + Online Shopping: Some examples are Etsy, Amazon, eBay, and AliExpress.
    + Online Banking: Modern banks increasingly allow clients to carry out their banking operations from a web browser.
    + Online Office Suite: Consider, for instance, Microsoft Office 365, Zoho Office, and Google Drive.

+ Databases
  + When discussing web applications, it is essential to mention database systems. Many web applications need to access vast amounts of data. Even the most basic online shopping web application requires saving information about available products, customer details, and purchases. We must find a solution to hold such information and to read from and write to the existing data efficiently. The answer lies in using a database system.
  + There are two popular database models:
    + Relational Database: It stores the data in tables.
    + Non-Relational Database: It is any database that does not use tables. It might store the data in documents, and graph nodes, among other types.

+ Access Control
  + Access control is a security element that determines who can access certain information and resources. After authentication (covered in Day 5), access control enforces the appropriate access level.

+ Web App Vulnerabilities
  + The OWASP was established to improve software security. The OWASP Top 10 list aims to raise awareness regarding common security issues that plague web applications.
  + IDOR refers to the situation where a user can manipulate the input to bypass authorization due to poor access control.

+ IDOR
  + Example: Let’s say that a user of ID `132` is directed to the following URL after logging in to the system: 'http://santagift.shop/account/user_id=132'
  + BUT what happens if we change that `user_id` value to someone elses value?
    + Although the system should deny them access to the new URL due to lack of authorization, an IDOR vulnerability will let the user display such unauthorized pages.
  + This same concept could be applied to other things such as changing passwords or other potenially dangerous thigns

## STEPS

1. Start machine
2. Exploiting an IDOR Vulnerability
   1. Access website 'http://MACHINE_IP:8080'
   2. Login
      1. UN: mcskidy
      2. PW: devtest
   3. Once logged in we see:
      1. Elf McSkiddy, Chief Information Security Officer, Office Number: 110, Mobile: 07700 900008
   4. However if we look at the URL we see:
      1. /users/101.html
   5. Lets try plugging in a different value for /user/`VALUE_HERE`.html
      1. Attempt 100 -> 404 Error
      2. Attempt 102 -> Elf Log McBlue, System Analyst, Office Number: 121, Mobile: 07700 900016
      3. Attempt 103 -> Elf Forensic McBlue, Digital Forensics Investigator, Office Number: 122, Mobile: 07700 900032
      4. Attempt 104 -> Elf Admin McBlue, System Administrator, Office Number: 123, Mobile: 07700 900064
      5. Attempt 105 -> Elf Pivot McRed, Penetration Tester, Office Number: 134, Mobile: 07700 900128
      6. Attempt 106 -> Elf Exploit McRed, Penetration Tester, Office Number: 135, Mobile: 07700 900256
      7. Attempt 107 -> Elf Recon McRed, Penetration Tester, Office Number: 136, Mobile: 07700 900512
      8. Attempt 108 -> 404 Error
   6. So after looking at all the potential profiles what other paths could be compromised/exploited
      1. Inspecting the page each profile has an image with the path `../images/XYZ.png`
   7. Lets try pluggin int a different value for /images/`VALUE_HERE`.png
      1. Attempt 102 -> Elf Log McBlue's picture
      2. Attempt 103 -> Elf Forensic McBlue's picture
      3. Attempt 104 -> Elf Admin McBlue's picture
      4. Attempt 105 -> Elf Pivot McRed's picture
      5. Attempt 106 -> Elf Exploit McRed's picture
      6. Attempt 107 -> Elf Recon McRed's picture
      7. No luck so lets try one out of our previous bounds
         1. Attempt 101 -> `THM{CLOSE_THE_DOOR}`

## QUESTIONS

1. What is the office number of Elf Pivot McRed?
   1. `134`
2. Not only profile pages but also stored images are vulnerable. Start with a URL of a valid profile image; what is the hidden flag?
   1. `THM{CLOSE_THE_DOOR}`
