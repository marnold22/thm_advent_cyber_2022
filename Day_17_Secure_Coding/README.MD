# Day_17 [Secure-Coding] Filtering for Order Amidst Chaos

+ Deployable Website: Yes
+ Deployable Container: Yes

Description: After handling unrestricted file uploads and SQLi vulnerabilities, McSkidy continued to review Santa's web applications. She stumbled upon user-submitted inputs that are unrecognizable, and some are even bordering on malicious! She then discovered that Santa's team hadn't updated these web applications in a long time, as they clearly needed more controls to filter misuse. Can you help McSkidy research and learn a useful technique to handle that in the future?

> IP:

## NOTES

1. Input Validation Foundations
   1. Generally, an effective way to validate input is first to know how a specific piece of data is going to be processed by the rest of your application. The Day 15 task is a beautiful example that shows more or less the mindset required to be able to effectively tackle the specific case of Unrestricted File Upload.
   2. Data then goes through syntax and semantic validation checks to ensure that the user-provided values are both proper in their syntax (the answer follows the proper context asked by the question) and logical value (the values make sense for the question).
   3. Going back to Day 15:
      1. You cannot  manually type your CV in the input field - the form asks for a file, so it’s simply not what’s being asked
      2. You cannot just upload any file - it should follow a set of very specific rules, the implementation of which was all discussed in the latter parts of the task.
   4. Then comes whitelisting, where you can be very specific with what your forms would accept and immediately strip or even drop the ones that don’t fit in the predefined allowed category.

2. HTML5
   1. HTML5’s built-in features help a lot with the validation of user-provided input, minimizing the need to rely on JavaScript for the same objective. The `<input>` element, specifically has an array of very helpful capabilities centered around form validation.
   2. For instance, the `<input>` type, which can be set to specifically filter for an email, a URL, or even a file, among others, promptly checks whether or not the user-provided input fits the type of data that the form is asking for, and so, feedback on its validity is immediately returned to the user as a result.
   3. For even more granular control of the input being provided, regular expressions (regex) can be integrated into the mix. Simply use it in the "pattern" attribute within the `<input>` element, and you’re all set. Here is a nice resource to get started with regular expressions. A couple of examples are shown below.
      1. ` <input type="text" id="uname" name="uname" pattern="[a-zA-Z0-9]+"> `
      2. ` <input type="email" id="email" name="email" pattern=".+@tryhackme\.com"> `
   4. The pattern in the first line of code above is easily one of the most foundational regular expression patterns one can use. The instruction here is to match any strings specifically composed of only letters and numbers - an alphanumeric pattern that is case-insensitive.
   5. The pattern in the second line of code above is a bit more pointed in its instruction, specifying that an email can have any characters at the beginning as long as it ends with "@tryhackme.com".
   6. Developing regular expressions can be very daunting as its nature is complex; however its capability to match very specific patterns is what makes it special. Well-built regular expressions introduce a great way to immediately filter out user-provided input that doesn't fit the specific requirements that you have set.

3. REGEX-101
   1. To match any lowercase character from the English alphabet, the regex pattern is [a-z]
      1. The square brackets indicate that you're trying to match one character within the set of characters inside of them. For example, if we're trying to match any vowel of the English alphabet, we construct our regex as follows: [aeiou]. The order of the characters doesn't matter, and it will match the same.
      2. Square brackets can also accept a range of characters by adding a hyphen, as seen in our original example.
      3. You can also mix and match sets of characters within the bracket. [a-zA-Z] means you want to match any character from the English alphabet regardless of case, while [a-z0-9] means you want to match any lowercase alphanumeric character.

## Exercise

1. We have prepared a regex exercise that you can access through the machine provided. First, let's start the Virtual Machine by pressing the Start Machine button at the top of this task. The machine will start in a split-screen view. In case the VM is not visible, use the blue Show Split View button at the top-right of the page.
2. To practice your regex, first, change your working directory to the RegExPractice folder using the command: cd ~/Desktop/RegExPractice then, you may either use egrep via the following syntax: egrep 'regex_pattern_here' strings, or the regex_checker.py script written for you via python: python3 regex_checker.py.
3. We are aware that some structured data are more complex than others, so we have set a specific syntax you may follow to make the exercise simpler. Have fun!

+ Filtering for Usernames: Alphanumeric, minimum of 6 characters, maximum of 12 characters, may consist of upper and lower case letters.

+ Filtering for Emails: Follows the form "local-part@domain" (without quotation marks); local-part is a random string, and the domain is in the form of "<domain name>.tld". All top-level domains (tld) are ".com"

+ Filtering for URLs: Starts with either http or https; some of the URLs have "www", and a TLD should exist.

## STEPS

1. Start machine
2. Download files to local machine
   1. > wget <http://10.10.71.121:8000/strings>
   2. > wget <http://10.10.71.121:8000/regex_checker.py>
3. REGEX
   1. Usernames_1:
   2. Usernames_2

## QUESTIONS

1. Filtering for Usernames: How many usernames fit the syntax above?
   1. ``
2. Filtering for Usernames: One username consists of a readable word concatenated with a number. What is it?
   1. ``
3. Filtering for Emails: How many emails fit the syntax above?
   1. ``
4. Filtering for Emails: How many unique domains are there?
   1. ``
5. Filtering for Emails: What is the domain of the email with the local-part "lewisham44"?
   1. ``
6. Filtering for Emails: What is the domain of the email with the local-part "maxximax"?
   1. ``
7. Filtering for Emails: What is the local-part of the email with the domain name "hotmail.com"?
   1. ``
8. Filtering for URLs: How many URLs fit the syntax provided?
   1. ``
9. Filtering for URLs: How many of these URLs start with "https"?
   1. ``
