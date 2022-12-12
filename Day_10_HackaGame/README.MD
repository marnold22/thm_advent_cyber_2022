# Day_10 [Hack-a-game] You're a mean one, Mr. Yeti

+ Deployable Website: Yes
+ Deployable Container: Yes

Description: Santa's team have done well so far. The elves, blue and red combined, have been securing everything technological all around. The Bandit Yeti, unable to hack a thing, decided to go for eldritch magic as a last resort and trapped Elf McSkidy in a video game during her sleep. When the rest of the elves woke up, their leader was nowhere to be found until Elf Recon McRed noticed one of their screens, where Elf McSkidy's pixelated figure could be seen. By the screen, an icy note read: "Only by winning the unwinnable game shall your dear Elf McSkidy be reclaimed". Without their chief, the elves started running in despair. How could they run a SOC without its head? The game was rigged, and try after try, the elves would lose, no matter what. As struck by lightning, Elf Exploit McRed stood up from his chair and said to the others: "If we can't win it, we'll hack it!".

> IP = 10.10.107.241

## LEARNING OBJECTIVES

+ Learn how data is stored in memory in games or other applications.
+ Use simple tools to find and alter data in memory.
+ Explore the effects of changing data in memory on a running game.

## NOTES

1. The Memory of a Program
   1. Whenever we execute a program, all data will be processed somehow through the computer's RAM (Random Access Memory). If you think of a videogame, your HP, position, movement speed and direction are all stored somewhere in memory and updated as needed as the game goes.
   2. If you can modify the relevant memory positions, you could trick the game into thinking you have more HP than you should or even a higher score!
2. The Mighty Cetus
   1. Cetus is a simple browser plugin that works for Firefox and Chrome, allowing you to explore the memory space of Web Assembly games that run in your browser. The main idea behind it is to provide you with the tools to easily find any piece of data stored in memory and modify it if needed. On top of that, it will let you modify a game's compiled code and alter its behaviours if you want, although we won't need to go that deep for this task.

## STEPS

1. Start machine
2. Install Cetus browser extension
   1. Download the extension
      1. > wget 'https://github.com/Qwokka/Cetus/releases/download/v1.03.1/Cetus_v1.03.1.zip'
   2. Open up chome extensions
      1. chrome://extensions
   3. Enable developer mode
   4. Click Load unpacked
      1. Select the Cetus.zip file
3. Start up game
   1. Go to 'https://10.10.107.241/'
4. Open up Cetus from developer tools
   1. Rightclick inspect
   2. Click the `>>` dropdown menu and select Cetus
   3. Cetus Operators

      ```text
         EQ-Find all memory addresses with contents that are equal to our inputted value.
         NE-Find all memory addresses with contents that are not equal to our inputted value.
         LT-Find all memory addresses with contents that are lower than our inputted value.
         GT-Find all memory addresses with contents that are greater than our inputted value.
         LTE-Find all memory addresses with contents that are lower than or equal to our inputted value.
         GTE-Find all memory addresses with contents that are greater than or equal to our inputted value.
      ```

5. Start Playing the game
   1. Talk to the Guard
      1. If we guess the correct number between 1-99999999 he will let us out of the prison
   2. Guesing Numbers
      1. First guess 123 - incorrect guard's number was 22501308
         1. Based on this number we can use CETUS and the EQ operator to find all memory addresses containing that value
            1. Input the guard's value 22501308 -> Use EQ -> Use i32 (32bit integer) -> Search
               1. We find the memory address of: `0x0004ea34` with value: `0x015757bc`
               2. Bookmark this value
               3. Since we have the memory address bookmarked we can now see the generated number from the guard
      2. Talk to guard again **While watching our memory address**
         1. We see the new value of: `0x03e502c8` which is `65340104`
         2. Input this value for the guard
            1. The gate is open we can proceed
      3. Talk to the guard again
         1. He gives us the flag: `THM{5_star_Fl4gzzz}`
   3. Differential Search
      1. Crossing the bridge

         ```text
            While you are wondering what other data in memory could be changed to survive the bridge, Elf Recon McRed tells you that he read about differential search. **Differential Search**, he said, allows you to run successive searches in tandem, where each search will be scoped over the results of the last search only instead of the whole memory space. Elf Recon thinks this might be of help somehow.

            To help you better understand, he used the following example: suppose you want to find an address in memory, but you are not sure of the exact value it contains, but you can, however, manipulate it somehow by doing some actions in the game (you could manipulate the value of your position by moving, for example). I**nstead of doing a direct search by value as before, you can use differential search to look for memory positions based on specific variations on the value, rather than the value itself.**
         ```

      2. Our first searchneeds to be down with an empty value:
         1. Empty value -> EQ -> i32 -> search = `16777216 Results`
            1. This gives us the total number of addresses mapped by the game
      3. Now we want to know which values have changed since our last search
         1. Empty value -> LT (less than) -> i32 -> search = `1417 Results`
            1. This tells us 1417 memory addressess have changed out of the total 16777216
      4. Now we can repeat with the GT function to see how many addresses have increased since
         1. Empty value -> GT (great than) -> i32 -> search = `785 Results`
      5. Armed with these tools how can we find specific memory addresses?
   4. Crossing the Bridge
      1. Attempting to cross the bridge we see that our health decreases then -> `Health ran out ... if only we had more`
      2. So lets try and search for the memory address that holds our health value
      3. Differential Search Steps
         1. Get base line
         2. Take 1 Hit of damage
         3. Find LESSTHAN
            1. Our health should be a value that decreased so it should be in this
            2. However there are still ALOT of values
         4. Find LESSTHAN or EQUAL
            1. Move around **BUT NOT TAKING WHERE WE TAKE DAMAGE**
            2. Now by adding the EQUAL we can rule out other memory addresses becuase our health shouldn't change
            3. This limits it down to 5 results - lets book mark these to see whichone might be health
         5. Find LESSTHAN
            1. Go back to lessthan only and take another thing of damage
            2. This should narrow it down
            3. Pretty sure the memory address is: `0x00643048` and `0x0004b4a4`
            4. This could be a scenario of MAX_HEALTH & CURRENT_HEALTH?
         6. Add Health
            1. Change both values to 1000
            2. Woohoo we have more health again!
         7. Cross the bridge
         8. Face the Yeti
            1. Here is the flag: `THM{yetiyetiyetiflagflagflag}`

## QUESTIONS

1. What is the Guard's flag?
   1. `THM{5_star_Fl4gzzz}`
2. What is the Yeti's flag?
   1. `THM{yetiyetiyetiflagflagflag}`
