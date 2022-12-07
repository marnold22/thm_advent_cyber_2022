# Day_06 [Email-Analysis] It's beginning to look a lot like phishing

+ Deployable Website: No
+ Deployable Container: Yes

Description: Elf McBlue found an email activity while analysing the log files. It looks like everything started with an email...

> IP = 10.10.186.242

## LEARNING OBJECTIVES

+ Learn what email analysis is and why it still matters.
+ Learn the email header sections.
+ Learn the essential questions to ask in email analysis.
+ Learn how to use email header sections to evaluate an email.
+ Learn to use additional tools to discover email attachments and conduct further analysis.
+ Help the Elf team investigate the suspicious email received.

## NOTES

1. What is email analysis?
   + Email analysis is the process of extracting the email header information to expose the email file details. The email header contains the technical details of the email like sender, recipient, path, return address and attachments. Usually, these details are enough to determine if there is something suspicious/abnormal in the email and decide on further actions on the email, like filtering/quarantining or delivering. This process can be done manually and with the help of tools.
   + Two main concerns in email analysis
      1. **Security issues**: Identifying suspicious/abnormal/malicious patterns in emails.
      2. **Performance issues**: Identifying delivery and delay issues in emails.
   + TERMS:
     + **Social engineering**: Social engineering is the psychological manipulation of people into performing or divulging information by exploiting weaknesses in human nature. These "weaknesses" can be curiosity, jealousy, greed, kindness, and willingness to help someone.
     + **Phishing**: Phishing is a sub-section of social engineering delivered through email to trick someone into either revealing personal information and credentials or executing malicious code on their computer.
2. How to analyze emails
   1. From: The sender's address.
   2. To: The receiver's address, including CC and BCC.
   3. Date: Timestamp, when the email was sent.
   4. Subject: The subject of the email.
   5. Return Path: The return address of the reply, a.k.a. "Reply-To". If you reply to an email, the reply will go to the address mentioned in this field.
   6. Domain Key and DKIM Signatures: Email signatures are provided by email services to identify and authenticate emails.
   7. SPF: Shows the server that was used to send the email. It will help to understand if the actual server is used to send the email from a specific domain.
   8. Message-ID: Unique ID of the email.
   9. MIME-Version: Used MIME version. It will help to understand the delivered "non-text" contents and attachments.
   10. X-Headers: The receiver mail providers usually add these fields. Provided info is usually experimental and can be different according to the mail provider.
   11. X-Received: Mail servers that the email went through.
   12. X-Spam Status: Spam score of the email.
   13. X-Mailer: Email client name.
3. Quick Analysis - Questions to ask & Evaluation
   1. Do the "From", "To", and "CC" fields contain valid addresses?: Having invalid addresses is a red flag.
   2. Do the "From" and "To" fields are the same?: Having the same sender and recipient is a red flag.
   3. Do the "From" and "Return-Path" fields are the same?: Having different values in these sections is a red flag.
   4. Was the email sent from the correct server?: Email should have come from the official mail servers of the sender.
   5. Does the "Message-ID" field exist, and is it valid?: Empty and malformed values are red flags.
   6. Do the hyperlinks redirect to suspicious/abnormal sites?: Suspicious links and redirections are red flags.
   7. Do the attachments consist of or contain malware?: Suspicious attachments are a red flag. File hashes marked as suspicious/malicious by sandboxes are a red flag.
4. Useful Tools:
   1. Email Header Parser
   2. Text editor (sublime, vscode, etc.) for viewing email files without opening/executing them
   3. emlAnalyzer: tool to view the body of the email and analyse the attachments
   4. OSINT Tools
      1. VirusTotal: A service that provides a cloud-based detection toolset and sandbox environment.
      2. InQuest: A service provides network and file analysis by using threat analytics.
      3. IPinfo.io: A service that provides detailed information about an IP address by focusing on geolocation data and service provider.
      4. Talos Reputation: An IP reputation check service is provided by Cisco Talos.
      5. Urlscan.io: A service that analyses websites by simulating regular user behaviour.
      6. Browserling: A browser sandbox is used to test suspicious/malicious links.
      7. Wannabrowser: A browser sandbox is used to test suspicious/malicious links.

## LAUNCH THE INSTANCE

1. Start the machine
2. Split pane view to open attack box
3. On the attackbox lets spin up a python server to serve the email so we can download it to our machine for analysis
   1. > python -m SimpleHTTPServer
4. On main machine navigate to website
   1. <http://10.10.223.205:8000>
   2. Download Urgent:.eml
5. Email Analysis
   1. > emlAnalyzer -i Urgent_.eml --header --html -u --text --extract-all > email_analysis.log
      1. Examine the log file we can see the FROM: `From........................Chief Elf <chief.elf@santaclaus.thm>`
      2. Examine the log file we can see the RETURN: `Return-Path.................<murphy.evident@bandityeti.thm>`
      3. Examine the log file we can see the X-PM-SPAMSCORE: `X-Pm-Spamscore..............3`
      4. Examine the log file we can see the MESSAGE-ID: `Message-Id..................<QW9DMjAyMl9FbWFpbF9BbmFseXNpcw==>`
         1. This looks like base64
            1. > echo "QW9DMjAyMl9FbWFpbF9BbmFseXNpcw==" | base64 -d
            2. RESPONSE: `AoC2022_Email_Analysis`
   2. Go to email reputaion check <https://emailrep.io/>
      1. Input the sender's email
         1. RESPONSE: RISKY
   3. Attachments
      1. Examine the log file we can see [+] Attachment: `Division_of_labour-Load_share_plan.doc`
      2. Lets get the HASH value of this file
         1. > sha256sum Division_of_labour-Load_share_plan.doc
            1. RESPONSE: `0827bb9a2e7c0628b82256759f0f888ca1abd6a2d903acdb8e44aca6a1a03467`
      3. Go to Virus Total to check hash
         1. Input hash value in search
         2. Navigate to 'Behavior Section'
         3. Looking at the Mitre ATT&CK section we can see that the second option is: `Defense Evasion`
      4. Go to InQuest site to check hash <https://labs.inquest.net>
         1. Indicator Lookup - input the hash value
         2. Click on the results
         3. Under subcategory we see `macro_hunter`

## QUESTIONS

1. What is the email address of the sender?
   1. `chief.elf@santaclaus.thm`
2. What is the return address?
   1. `murphy.evident@bandityeti.thm`
3. On whose behalf was the email sent?
   1. `Chief Elf`
4. What is the X-spam score?
   1. `3`
5. What is hidden in the value of the Message-ID field?
   1. `AoC2022_Email_Analysis`Division_of_labour-Load_share_plan.doc
6. Visit the email reputation check website provided in the task. What is the reputation result of the sender's email address?
   1. `RISKY`
7. Check the attachments. What is the filename of the attachment?
   1. `Division_of_labour-Load_share_plan.doc`
8. What is the hash value of the attachment?
   1. `0827bb9a2e7c0628b82256759f0f888ca1abd6a2d903acdb8e44aca6a1a03467`
9. Visit the Virus Total website and use the hash value to search. Navigate to the behaviour section. What is the second tactic marked in the Mitre ATT&CK section?
   1. `Defense Evasion`
10. Visit the InQuest website and use the hash value to search. What is the subcategory of the file?
    1. `macro_hunter`
