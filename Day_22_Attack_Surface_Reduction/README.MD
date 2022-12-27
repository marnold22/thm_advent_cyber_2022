# Day_22 [Attack-Surface-Reduction] Threats are failing all around me

+ Deployable Website: Yes
+ Deployable Container: No

Description: McSkidy wants to improve the security posture of Santa's network by learning from the recent attempts to disrupt Christmas. As a first step, she plans to implement low-effort, high-value changes that improve the security posture significantly.

## LEARNING OBJECTIVES

+ Understand what an attack vector is.
+ Understand the concept of the attack surface.
+ Some practical examples of attack surface reduction techniques that McSkidy can utilize to strengthen Santa's network.

## NOTES

## STEPS

1. Launch Site
2. Match ASR Actions to following Attack Vectors

3. Attack Vector
   1. Bandit Yeti has identified an open SSH port on Santa’s webserver.
   2. A document file with malicious macros is opened by a user.
   3. Some sensitive data related to Santa’s server is found on a file-sharing website.
   4. Spoofed phishing emails are sent to Santa's employees.
   5. Password brute-forcing attack is attempted on Santa's account.
   6. Santa’s employee finds a USB flash drive in the parking lot; after he plugs it into his computer, malware installs on the machine.

4. ASR Actions
   1. Unneccessary ports are closed to avoid attack attempts
   2. Security policies are implemented to block macros on santas network
   3. Sensitive data from the file-sharing site is removed to avoid Bandit Yeti taking advantage of that data.
   4. Phishing protection is added to santas email server
   5. Strong password policy is enabled to thwart brute force attacks.
   6. Applocker is deployed network-wide to block execution of unknown/non-whitelisted applications.

## QUESTIONS

1. Follow the instructions in the attached static site to help McSkidy reduce her attack surface against attacks from the Yeti. Use the flag as an answer to complete the task.
   1. `THM{4TT4CK SURF4C3 R3DUC3D}`
