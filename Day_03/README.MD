# Day_03 [OSINT] Nothing escapes detective McRed

+ Deployable Website: No
+ Deployable Container: No

Description: As the elves are trying to recover the compromised santagift.shop website, elf Recon McRed is trying to figure out how it was compromised in the first place. Can you help him in gathering open-source information against the website?

## NOTES

What is OSINT?

OSINT is gathering and analysing publicly available data for intelligence purposes, which includes information collected from the internet, mass media, specialist journals and research, photos, and geospatial information. The information can be accessed via the open internet (indexed by search engines), closed forums (not indexed by search engines) and even the deep and dark web. People tend to leave much information on the internet that is publicly available and later on results in impersonation, identity theft etc.

OSINT Techniques:

1. Google Dorks
   + Google Dorking involves using specialist search terms and advanced search operators to find results that are not usually displayed using regular search terms. You can use them to search specific file types, cached versions of a particular site, websites containing specific text etc.  Bad actors widely use it to locate website configuration files and loopholes left due to bad coding practices. Some of the widely used Google dorks are mentioned below:
   inurl: Searches for a specified text in all indexed URLs. For example, inurl:hacking will fetch all URLs containing the word "hacking".
   filetype: Searches for specified file extensions. For example, filetype:pdf "hacking" will bring all pdf files containing the word "hacking".
   site: Searches all the indexed URLs for the specified domain. For example, site:tryhackme.com will bring all the indexed URLs from  tryhackme.com.
   cache: Get the latest cached version by the Google search engine. For example, cache:tryhackme.com. For example, you can use the dork site:github.com "DB_PASSWORD" to search only in github.com and look for the string DB_PASSWORD (possible database credentials). You can learn more about Google dorks through this free room.
2. WHOIS Lookup
   + WHOIS database stores public domain information such as registrant (domain owner), administrative, billing and technical contacts in a centralised database. The database is publicly available for people to search against any domain and enables acquiring Personal Identifiable Information (PII) against a company, like an email address, mobile number etc., of technical contact. Bad actors can, later on, use the information for profiling, spear phishing campaigns (targeting selected individuals) etc. Nowadays, registrars offer Domain Privacy options that allow users to keep their WHOIS information private from the general public and only accessible to certain entities like designated registrars.
3. Robots.txt
   + The robots.txt is a publicly accessible file created by the website administrator and intended for search engines to allow or disallow indexing of the website's URLs. All websites have their robots.txt file directly accessible through the domain's main URL. It is a kind of communication mechanism between websites and search engine crawlers. Since the file is publicly accessible, it doesn't mean anyone can edit or modify it. You can access robots.txt by simply appending robots.txt at the end of the website URL. For example, in the case of Google, we can access the robots.txt file by clicking this URL.
4. Breached Database Search
   + Major social media and tech giants have suffered data breaches in the past.  As a result, the leaked data is publicly available and, most of the time contains PII like usernames, email addresses, mobile numbers and even passwords. Users may use the same password across all the websites; that enables bad actors to re-use the same password against a user on a different platform for a complete account takeover. Many web services offer to check if your email address or phone number is in a leaked database; HaveIBeenPwned is one of the free services.
5. Searching GitHub Repos
   + GitHub is a renowned platform that allows developers to host their code through version control. A developer can create multiple repositories and set the privacy setting as well. A common flaw by developers is that the privacy of the repository is set as public, which means anyone can access it. These repositories contain complete source code and, most of the time, include passwords, access tokens, etc.

## LAUNCH THE INSTANCE

1. > whois -I "santagift.shop" > whois
   1. In here we can see the Registrar information
   2. `Registrar: Namecheap, Inc.`
2. Now let's try and find the source code on GitHub
   1. CLUE: McRed, the recon master, searched various terms on GitHub to find something useful like SantaGiftShop, SantaGift, SantaShop etc. Luckily, one of the terms worked, and he found the website's complete source code publicly available through OSINT.
   2. search for 'SantaGiftShop' on github
      1. We get the repo: muhammadthm/SantaGiftShop
      2. Let's explore this
      3. In here we see what looks to be a wordpress site (everything is prefixed with wp-)
      4. Knowing it is WordPress let's look for a `config.php` file or a wp-config.php file
      5. In here we see a flag variable and two different envirements QA and PROD
         1. config.php

            ```php
               $FLAG = '{THM_OSINT_WORKS}';

               $ENV = "PROD"; //santagift.shop - Incase of QA, it will be qa.santagift.shop

               if($ENV = "QA"){
               // ** Database settings - You can get this info from your web host ** //
               /** The name of the database for WordPress */
               define( 'DB_NAME', 'SantaGiftShop' );
               /** Database username */
               define( 'DB_USER', 'ubuntu' );
               /** Database password */
               define( 'DB_PASSWORD', 'S@nta2022' );
               /** Database hostname */
               define( 'DB_HOST', 'qa.santagift.shop' );
               /** Database charset to use in creating database tables. */
               define( 'DB_CHARSET', 'utf8' );
               /** The database collate type. Don't change this if in doubt. */
               define( 'DB_COLLATE', '' );
               }

               if($ENV = "PROD"){
               // ** Database settings - You can get this info from your web host ** //
               /** The name of the database for WordPress */
               define( 'DB_NAME', 'SantaGiftShop' );
               /** Database username */
               define( 'DB_USER', 'ubuntu' );
               /** Database password */
               define( 'DB_PASSWORD', 'S@nta2022' );
               /** Database hostname */
               define( 'DB_HOST', 'santagift.shop' );
               /** Database charset to use in creating database tables. */
               define( 'DB_CHARSET', 'utf8' );
               /** The database collate type. Don't change this if in doubt. */
               define( 'DB_COLLATE', '' );
               }
            ```

         2. wp-congig.php

            ```php
               $FLAG = '{THM_OSINT_WORKS}';

               $ENV = "PROD"; //santagift.shop - Incase of QA, it will be qa.santagift.shop

               if($ENV = "QA"){
               // ** Database settings - You can get this info from your web host ** //
               /** The name of the database for WordPress */
               define( 'DB_NAME', 'SantaGiftShop' );
               /** Database username */
               define( 'DB_USER', 'root' );
               /** Database password */
               define( 'DB_PASSWORD', 'S@ntaIsG00dB0yProdQA' );
               /** Database hostname */
               define( 'DB_HOST', 'qa.santagift.shop' );
               /** Database charset to use in creating database tables. */
               define( 'DB_CHARSET', 'utf8' );
               /** The database collate type. Don't change this if in doubt. */
               define( 'DB_COLLATE', '' );
               }

               if($ENV = "PROD"){
               // ** Database settings - You can get this info from your web host ** //
               /** The name of the database for WordPress */
               define( 'DB_NAME', 'SantaGiftShop' );
               /** Database username */
               define( 'DB_USER', 'root' );
               /** Database password */
               define( 'DB_PASSWORD', 'S@ntaIsG00dB0yProd' );
               /** Database hostname */
               define( 'DB_HOST', 'santagift.shop' );
               /** Database charset to use in creating database tables. */
               define( 'DB_CHARSET', 'utf8' );
               /** The database collate type. Don't change this if in doubt. */
               define( 'DB_COLLATE', '' );
               }
            ```

      6. The flag variable is set to `{THM_OSINT_WORKS}`
      7. If trying to access the QA environment the web address would be: `qa.santagift.shop`
      8. As for shared passwords between PROD & QA we can see that in the wp-config.php file there are different passwords. However in the config.php file we can see that the PROD & QA passwords are the same: `S@nta2022`

## QUESTIONS

1. What is the name of the Registrar for the domain santagift.shop?
   1. `Namecheap Inc`
2. Find the website's source code (repository) on github.com and open the file containing sensitive credentials. Can you find the flag?
   1. `{THM_OSINT_WORKS}`
3. What is the name of the file containing passwords?
   1. `config.php`
4. What is the name of the QA server associated with the website?
   1. `qa.santagift.shop`
5. What is the DB_PASSWORD that is being reused between the QA and PROD environments?
   1. `S@nta2022`
