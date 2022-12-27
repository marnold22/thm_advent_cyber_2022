# Day_18 [Sigma] Lumberjack Lenny Learns New Rules

+ Deployable Website: Yes
+ Deployable Container: Yes

Description: Compromise has been confirmed within the Best Festival Company Infrastructure, and tests have been conducted in the last couple of weeks. However, Santa’s SOC team wonders if there are methodologies that would help them perform threat detection faster by analysing the logs they collect. Elf McSkidy is aware of Sigma rules and has tasked you to learn more and experiment with threat detection rules.

+ Equipped with the knowledge about Sigma rules, your task is to complete the remaining two challenges by writing rules corresponding to the attack chain phases and IOCs. Santa is relying on you to beef up his security against adversaries attempting to stop Christmas this year. As a reminder, the required fields for the attacks are below:
  + Software Discovery: Category, EventID, Image, CommandLine.
  + Scheduled Jobs: Category, EventID, Image, CommandLine.

> IP: 10.10.60.191

## NOTES

1. Attack Scenario
   1. Elf McBlue obtained logs and information concerning the attack on the Best Festival Company by the Bandit Yeti. Through the various analysis of the previous days, it was clear that the logs pointed to a likely attack chain that the adversary may have followed and can be mapped to the Unified Kill Chain. Among the known phases of the UKC that were observed include the following:
      1. Persistence: The adversary established persistence by creating a local user account they could use during their attack.
      2. Discovery: The adversary sought to gather information about their target by running commands to learn about the processes and software on Santa’s devices to search for potential vulnerabilities.
      3. Execution: Scheduled jobs were created to provide an initial means of executing malicious code. This may also provide them with persistence or be part of elevating their privileges.

2. SIGMA APPLICATION
   1. Run - Submit your Sigma rule and see if it detects the malicious IOC.
   2. Text Editor - Write your Sigma rule in this section.
   3. Create Rule - Create a Sigma rule for the malicious IOC.
   4. View Log - View the log details associated with the malicious IOC.

3. Chopping Logs with Sigma Rules
   1. Sigma is an open-source generic signature language developed by Florian Roth & Thomas Patzke to describe log events in a structured format. The format involves using a markup language called YAML, a designed syntax that allows for quick sharing of detection methods by security analysts. The common factors to note about YAML files include the following:
      1. YAML is case-sensitive.
      2. Files should have the .yml extension.
      3. Spaces are used for indentation and not tabs.
      4. Comments are attributed using the # character.
      5. Key-value pairs are denoted using the colon : character.
      6. Array elements are denoted using the dash - character.

4. Sigma Rule Syntax
   1. Sigma Rule StructureSigma rules are guided by a given order of required/optional fields and values that create the structure for mapping needed queries. The attached image provides a skeletal view of a Sigma rule.
   2. Let’s use the first attack step challenge to define the syntax requirements, fill in the details into our skeletal rule, and detect the creation of local accounts. Use the text editor section of the SigHunt application to follow along.
      1. Title: Names the rule based on what it is supposed to detect.
      2. ID: A globally unique identifier that the developers of Sigma mainly use to maintain the order of identification for the rules submitted to the public repository, found in UUID format.
      3. Status: Describes the stage in which the rule maturity is at while in use. There are five declared statuses that you can use:
         1. Stable: The rule may be used in production environments and dashboards.
         2. Test: Trials are being done to the rule and could require fine-tuning.
         3. Experimental: The rule is very generic and is being tested. It could lead to false results, be noisy, and identify exciting events.
         4. Deprecated: The rule has been replaced and would no longer yield accurate results.
         5. Unsupported: The rule is not usable in its current state (unique correlation log, homemade fields).
      4. Description: Provides more context about the rule and its intended purpose. Here, you can be as detailed as possible to provide information about the detected activity.
      5. Logsource: Describes the log data to be used for the detection. It consists of other optional attributes:
          1. Product: Selects all log outputs of a certain product. Examples are Windows, Apache
          2. Category: Selects the log files written by the selected product. Examples are firewalls, web, and antivirus.
          3. Service: Selects only a subset of the logs. Examples are sshd on Linux or Security on Windows.
          4. Definition: Describes the log source and its applied configurations.
      6. Detection:  A required field in the detection rule describes the parameters of the malicious activity we need an alert for. The parameters are divided into two main parts:
         1. The search identifiers are the fields and values the detection should search for.
         2. The condition expression - sets the action to be taken on the detection, such as selection or filtering. The critical thing to look out for account creation on Windows is the Event ID associated with user accounts. In this case, Event ID: 4720 was provided for us on the IOC list, which will be our search identifier.
      7. FalsePositives: A list of known false positives that may occur based on log data.
      8. Level: Describes the severity with which the security team should take the activity under the written rule. The attribute comprises five levels: Informational -> Low -> Medium -> High -> Critical
      9. Tags: Adds information that can be used to categorise the rule. Common tags are associated with tactics and techniques from the MITRE ATT&CK framework. Sigma developers have defined a list of predefined tags.

## STEPS

1. Start machine
2. Navigate to website: <http://IP>
3. SIGMA
   1. LOCAL ACCOUNT CREATION
      1. Create Query

         ```yaml
            title: Suspicious Local Account Creation
            id: 1 
            status: experimental
            description: Detects the creation of a local user account on a computer.
            author:
            date:
            modified:

            logsource:
            product: windows 
            service: security 
            category: # firewall, web, antivirus, process_creation, network_connection, file_access.
            detection:
            selection:
               EventID: 4720 # Change me

            condition: selection # Action to be taken. Can use condition operators such as OR, AND, NOT when using multiple search identifiers.

            falsepositives: # Legitimate services or use.

            level:  low # informational, low, medium, high or critical.

            tags: # Associated TTPs from MITRE ATT&CK
            - {attack.tactic} # MITRE Tactic
            - {attack.technique} # MITRE Technique 
         ```

         1. RESPONSE: `THM{n0t_just_your_u$ser}`
      2. Check Log
         1. USER CREATED: `BanditYetiMini`

   2. SOFTWARE DISCOVERY
      1. Create Query

         ```yaml
            title: Software Discovery
            id: 2 # UUID
            status: # experimental, test, stable, deprecated, unsupported.
            description:
            author:
            date:
            modified:

            logsource:
            product: windows
            service: sysmon
            category: process_creation
            detection:
            selection:
               EventID:
               - 1
               Image|endswith: 
               - reg.exe
               CommandLine|contains|all: 
               - reg
               - query
               - /v
               - svcVersion

            condition: selection # Action to be taken. Can use condition operators such as OR, AND, NOT when using multiple search identifiers.

            falsepositives: # Legitimate services or use.

            level:  # informational, low, medium, high or critical.

            tags: # Associated TTPs from MITRE ATT&CK
            - {attack.tactic} # MITRE Tactic
            - {attack.technique} # MITRE Technique 
         ```

         1. RESPONSE: `THM{wh@t_1s_Runn1ng_H3r3}`
      2. Check Log
         1. USER PATH: `SIGMA_AOC2022\Bandit Yeti`

   3. SCHEDULED TASK
      1. Create Query

         ```yaml
            title: Scheduled Task
            id: 3 # UUID
            status: # experimental, test, stable, deprecated, unsupported.
            description:
            author:
            date:
            modified:

            logsource: # Outlines target source of the logs based on operating system, service being run, category of logs.
            product: windows #, linux, macos.
            service: sysmon # sshd for Linux, Security for Windows, applocker, sysmon.
            category: process_creation # firewall, web, antivirus, process_creation, network_connection, file_access.
            detection:
            selection:
               EventID: 1 # Change me
               Image|endswith:
               - schtasks.exe
               CommandLine|contains|all:
               - schtasks
               - /create

            condition: selection # Action to be taken. Can use condition operators such as OR, AND, NOT when using multiple search identifiers.

            falsepositives: # Legitimate services or use.

            level:  # informational, low, medium, high or critical.

            tags: # Associated TTPs from MITRE ATT&CK
            - {attack.tactic} # MITRE Tactic
            - {attack.technique} # MITRE Technique 
         ```

         1. RESPONSE: `THM{sch3dule_0npo1nt_101}`
      2. Check Log
         1. MD5 `2F6CE97FAF2D5EEA919E4393BDD416A7`

## QUESTIONS

1. What is the Challenge #1 flag?
   1. `THM{n0t_just_your_u$ser}`
2. From the Challenge 1 log, what user account was created?
   1. `BanditYetiMini`
3. What is the Challenge #2 flag?
   1. `THM{wh@t_1s_Runn1ng_H3r3}`
4. What was the User's path in the Challenge #2 log file?
   1. `SIGMA_AOC2022\Bandit Yeti`
5. What is the Challenge #3 flag?
   1. `THM{sch3dule_0npo1nt_101}`
6. What was the MD5 hash associated with Challenge #3 logs?
   1. `2F6CE97FAF2D5EEA919E4393BDD416A7`
