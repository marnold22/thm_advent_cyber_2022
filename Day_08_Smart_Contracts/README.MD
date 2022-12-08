# Day_08 [Smart-Contracts] Last Christmas I gave you my ETH

+ Deployable Website: No
+ Deployable Container: No
+ Downloadable Content: Yes

Description: After it was discovered that Best Festival Company was now on the blockchain and attempting to mint their cryptocurrency, they were quickly compromised. Best Festival Company lost all its currency in the exchange because of the attack. It is up to you as a red team operator to discover how the attacker exploited the contract and attempt to recreate the attack against the same target contract.

## LEARNING OBJECTIVES

+ Explain what smart contracts are, how they relate to the blockchain, and why they are important.
+ Understand how contracts are related, what they are built upon, and standard core functions.
+ Understand and exploit a common smart contract vulnerability.

## NOTES

+ What is a Blockchain?
  + Informally, a blockchain acts as a database to store information in a specified format and is shared among members of a network with no one entity in control.
  + By definition, a blockchain is a digital database or ledger distributed among nodes of a peer-to-peer network. The blockchain is distributed among "peers" or members with no central servers, hence "decentralized." Due to its decentralized nature, each peer is expected to maintain the integrity of the blockchain. If one member of the network attempted to modify a blockchain maliciously, other members would compare it to their blockchain for integrity and determine if the whole network should express that change.

+ Introduction to Smart Contracts
  + Smart contracts are most commonly used as the backbone of DeFi applications (Decentralized Finance applications) to support a cryptocurrency on a blockchain.
  + A smart contract is a program stored on a blockchain that runs when pre-determined conditions are met.

+ Functionality of a Smart Contract
  + Smart Contract greatly contrasts with core object-oriented programming concepts.
  + A contract commonly has several functions that act similarly to accessors and mutators, such as checking balance, depositing, and withdrawing currency.

+ How do Vulnerabilities in Smart Contracts Occur?
  + Most smart contract vulnerabilities arise due to logic issues or poor exception handling.
  + Most vulnerabilities arise in functions when conditions are insecurely implemented through the previously mentioned issues.

+ The Re-entrancy Attack
  + Re-entrancy occurs when a malicious contract uses a fallback function to continue depleting a contract's total balance due to flawed logic after an initial withdraw function occurs.

## STEPS

1. Download the files
2. Unzip these files
   1. > unzip AoCSmartContracts.zip
      1. EtherStore.sol, Attack.sol
3. Remix IDE
   1. Go to <https://remix.ethereum.org/> to open up our contracts in a safe environment
   2. Import both .sol files
   3. Click on both EtherStore.sol & Attack.sol (this will open each in a tab)
   4. Compile the contracts
      1. On EtherStore.sol
         1. Go to `solidity compiler`, and select 0.8.10+commitfc41..... from the dropdown compiler menu.
         2. Press Compile
      2. On Attack.sol
         1. Go to `solidity compiler`, and select 0.8.10+commitfc41..... from the dropdown compiler menu.
         2. Press Compile
      3. Now both contracts have been compiled lets use them
   5. `Deploy & Run` (Click on this on the left hand toolbar)
      1. Lets deploy the EtherStore
         1. In the `Contract` dropdown menu
            1. Select EtherStore.sol
            2. Then press `Deploy`
               1. A deployed contract shows up underneath
               2. Copy this memory address as we will need it later
                  1. ADDR: `0xd9145CCE52D386f254917e481eB44e9943F39138`
      2. Lets now deplot the Attack
         1. Click on the Attack.sol tab at the top
         2. CHANGE ACCOUNT - This is a crucial step
            1. In the `Account` dropdown menu select the next account in the list (not the one we deployed with the EtherStore)
         3. In the `Contract` dropdown menu select Attack.sol
            1. **BEFORE YOU CLICK DEPLOY** you need to make sure and paste in the address of the EtherStore contract that we copied in the previous steps
               1. Paste in ADDR: `0xd9145CCE52D386f254917e481eB44e9943F39138`
               2. Deploy
      3. Now at the bottom we can see both the EtherStore Contract & Attack Contract deployed
   6. Interact & Exploit
      1. Just like in the steps talking about the re-entrancy Attack we need to first make sure there is Ether deposited
      2. Deposit Ether to Store
         1. Click on the EtherStore contract at the bottom (make sure you're on the original address)
         2. In the value box input 1 ETH
         3. Then in the deployed contract use the `DEPOSIT` function
            1. We can see we now have a balance > 0
      3. Run Attack
         1. Click on the Attack contract at the bottom (make sure we are at the secondary address from the dropdown menu)
         2. In the value box input 1 ETH
         3. Then in the deployed contract use the `ATTACK` function
         4. This will then run the attack
   7. Results
      1. In the terminal at the bottom we see the console.log with the flag: `flag{411_ur_37h_15_m1n3}`

## QUESTIONS

1. If not already completed, download the zip folder attached to this task, and open Remix in your preferred browser.
2. What flag is found after attacking the provided EtherStore Contract?
   1. `flag{411_ur_37h_15_m1n3}`
