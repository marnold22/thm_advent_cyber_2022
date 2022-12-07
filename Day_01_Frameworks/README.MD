# Day_01 [Frameworks] Someone's coming to town

+ Deployable Website: Yes
+ Deployable Container: No

Description: Best Festival Company Compromised - Someone is trying to stop Christmas this year and stop Santa from delivering gifts to children who were nice this year. The Best Festival Company’s website has been defaced, and children worldwide cannot send in their gift requests. There’s much work to be done to investigate the attack and test other systems! The attackers have left a puzzle for the Elves to solve and learn who their adversaries are. McSkidy looked at the puzzle and recognised some of the pieces as the phases of the Unified Kill Chain, a security framework used to understand attackers. She has reached out to you to assist them in recovering their website, identifying their attacker, and helping save Christmas. Saving The Best Festival Company - Having gone through the UKC with Santa’s security team, it is evident that better defensive strategies must be implemented to raise resilience against attacks. Your task is to help the Elves solve a puzzle left for them to identify who is trying to stop Christmas. Click the View Site button at the top of the task to launch the static site in split view. You may have to open the static site on a new window and zoom in for a clearer view of the puzzle pieces.

## LEARNING OBJECTIVES

+ Security Frameworks

## NOTES

**Security frameworks** are documented processes that define policies and procedures organisations should follow to establish and manage security controls. They are blueprints for identifying and managing the risks they may face and the weaknesses in place that may lead to an attack.

The framework focuses on five essential functions: Identify -> Protect -> Detect -> Respond -> Recover.

Identifying adversary plans of attack can be challenging to embark on blindly. They can be understood through the behaviours, methods, tools and strategies established for an attack, commonly known as Tactics, Techniques and Procedures (TTPs). The MITRE ATT&CK framework is a knowledge base of TTPs

A key concept of this framework was adopted from the military with the terminology kill chain, which describes the structure of an attack and consists of target identification, decision and order to attack the target, and finally, target destruction. Developed by Lockheed Martin, the cyber kill chain describes the stages commonly followed by cyber attacks and security defenders can use the framework as part of intelligence-driven defence.

## LAUNCH THE INSTANCE

1. Hello Santa. For years, you have delivered happiness and joy to millions around the world during the festivities. However, you have remained untested and the time has finally arrived to turn the Best Festival Company into Grumpy and Miserable. I have taken over your website and warehouse, and it is time to turn Christmas into Chaos. So, want to play a game to know who is behind all the misery?
2. SOLVE FIRST PUZZLE - (This looks like Cyber Kill Chain / Cycle 1 'IN')
   1. Research is part of my ask, finding clues in public sources. -> `RECON`
   2. Simple documents I turn into malware. -> `WEAPONIZATION`
   3. A pizza, parcel or payload all have me as an action in common. -> `DELIVERY`
   4. A con is the name of my game, tricking you into believing a false identity. -> `SOCIAL ENGINEERING`
   5. Weaknesses are my go-to resources; through them, I make my presence felt. -> `EXPLOITATION`
   6. I am set up to let you back into the network after you leave. -> `PERSISTENCE`
   7. Deletion of evidence is part of my process. -> `DEFENSE EVASION`
   8. Communication with the compromised goes through me. -> `COMMAND & CONTROL`
3. SOLVE SECOND PUZZLE - (This looks like Cycle 2 'THROUGH')
   1. I am an anchor that lets you go on an adventure and explore. -> `PIVOTING`
   2. With me, you can locate new information to expand the attack. -> `DISCOVERY`
   3. Once a pawn, I became a King. -> `PRIVILEGE ESCALATION`
   4. My payloads are triggered to infect all they come in contact with. -> `EXECUTION`
   5. Passwords I collect are the keys to the mainframe. -> `CREDENTIAL ACCESS`
   6. Side to side, machine to machine, we hop. -> `LATERAL MOVEMENT`
4. SOLVE THIRD PUZZLE (This looks like Cycle 3 'OUT')
   1. In your vault, I am in. -> `ACCESS`
   2. Like a dragon, I gather all valuable loot. -> `COLLECTION`
   3. I export gathered treasures. -> `EXFILTRATION`
   4. With me, your reputation goes tumbling down. -> `IMPACT`
   5. Goals set, goals attained. I win! -> `OBJECTIVES`
5. FINISH -> Answers who is behind the attack and flag

## QUESTIONS

1. Who is the adversary that attacked Santa's network this year?
   1. `The Bandit Yeti!`
2. What's the flag that they left behind?
   1. `THM{IT'S A Y3T1 CHR1$TMA$}`
