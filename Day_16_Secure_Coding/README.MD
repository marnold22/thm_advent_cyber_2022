# Day_16 [Secure-Coding] SQLi’s the king, the carolers sing

+ Deployable Website: Yes
+ Deployable Container: Yes

Description: Set to have all their apps secured, the elves turned towards the one Santa uses to manage the present deliveries for Christmas. Elf McSkidy asked Elf Exploit and Elf Admin to assist you in clearing the application from SQL injections. When presented with the app's code, both elves looked a bit shocked, as none of them knew how to make any sense of it, let alone fix it. "We used to have an Elf McCode, but he founded a startup and helps us no more", said Admin. After a bit of talk, it was decided. The elves returned carrying a pointy hat and appointed you as the new Elf McCode. Congratulations on your promotion! In addition to the code editor, you will have access to a chat to communicate with Elf Exploit and Elf Admin. While they don't speak too much, you can request them to check the application for you. If you remember correctly, they don't know a thing about coding. However, Elf Exploit will help you identify parts of the app that are vulnerable to SQLi. Elf Admin, on the other hand, will check that the application is running as expected, so if you make a change that breaks the application somehow, he will let you know so you can roll back and try again. In combination, they will tell you if your changes solve vulnerabilities while avoiding altering how the app is supposed to work.

> IP: 10.10.142.69

## LEARNING OBJECTIVES

## NOTES

1. SQL
   1. Structured Query Language (SQL) is the traditional language used to ask databases for information. When you build any application that relies on a database, the app will need to create SQL sentences on the fly and send them to the database engine to retrieve the required information for your app to work.
   2. MySQL stores information in structures called tables. Think of them as any table in a spreadsheet document where you have columns and rows.
   3. Each row is a single entity
   4. Each column is a field that contains data related to the entity

2. PHP
   1. We can establish connections to databases using PHP
   2. Similarly we can have web applications use PHP to send queries and allow us to view and manipulate data

   3. Connect to database

      ```PHP
         $server="db";
         $user="logistics_user";
         $pwd="somePass123";
         $schema="logistics";

         $db=mysqli_connect($server,$user,$pwd,$schema);
      ```

   4. Queries

      ```PHP
         $query="select * from users where id=1";
         $elves_rs=mysqli_query($db,$query);
      ```

   5. Dynamic Queries (from web url)
      1. Basic URL example: `http://10-10-142-69.p.thmlabs.com/webapp/elf.php?id=2`
      2. We can break this down to the following query

         ```PHP
            $query="select * from users where id=".$_GET['id'];
            $elves_rs=mysqli_query($db,$query);
         ```

      3. By changing the id value we could get a different user profile

3. SQL INJECTION
   1. The problem with the method shown before is that it takes untrusted input from the user and concatenates it to an SQL query without any questions asked. As seen in the previous day's task, our app should thoroughly validate any input the user sends before using it. If it doesn't, unexpected things may happen.
   2. In the case of SQL and our example, an attacker can send SQL syntax through one of the various parameters of the app's URLs, which might end up being concatenated to some SQL query in code, potentially changing its intended purpose.
   3. Let's get back to the elf's profile page to understand this better. Remember the application is creating a query by concatenating whatever is sent in the id parameter as part of the WHERE clause.
      1. BUT what if the attacker sends the following through the id parameter of the URL
         1. `http://10-10-142-69.p.thmlabs.com/webapp/elf.php?id=-1 OR id = 4`
         2. When PHP concatenates "-1 OR id = 4" to our SQL statement, it will end up with this query:
            1. `select * from users where id=-1 OR id = 4`
         3. If we read the resulting query string, we can see that our WHERE clause was modified to filter only the elves that either have id=-1 or id=4. Since the id values used by the database are likely all positive numbers, no elf will match id=-1. Therefore, the database will only return the elf with id=4.
   4. Now this is a simple and fairly harmless example but what if we use the following
      1. `http://10-10-142-69.p.thmlabs.com/webapp/elf.php?id=-1 union all select null,null,username,password,null,null,null from users`
      2. This will leak all the usernames and passwords of the application

4. FIX THE CODE!

## STEPS

1. Start machine
2. We need to update / fix the code so that our previous SQL-Injection doesn's leak important info
3. Navigate to : `http://10-10-142-69.p.thmlabs.com/`
   1. UN: coder
   2. PW: coder
4. Open up the code editor and get started

5. CODE
   1. ELF.PHP
      1. Data type validation
         1. Make sure the ID that is read in is actaully an integer using .intval()
         2. Run check with elves
            1. RESPONSE: `Flag1: THM{McCode, Elf McCode}`
   2. SEARCH-TOYS.PHP
      1. Fixing SQLi Using Prepared Statements
         1. While in some cases, you may secure your code with a simple validator, there are situations where you need to allow the user to pass arbitrary strings through a parameter. One example of this can be seen in the search bar of our application.

            ```PHP
               $query="select * from toys where name like '%".$_GET['q']."%' or description like '%".$_GET['q']."%'";
               $toys_rs=mysqli_query($db,$query);
            ```

         2. Here, the q parameter gets concatenated twice into the same SQL sentence. Notice that in both cases, the data in q is wrapped around single quotes, which is how you represent a string in SQL. The problem with having PHP build the query is that the database has no other option but to trust what it is being given. If an attacker somehow injects SQL, PHP will blindly concatenate the injected payload into the query string, and the database will execute it.
         3. Prepared statements allow you to separate the syntax of your SQL sentence from the actual parameters used on your WHERE clause. Instead of building a single string by concatenation, you will first describe the structure of your SQL query and use placeholders to indicate the position of your query's parameters. You will then bind the parameters to the prepared statement in a separate function call.
         4. Instead of providing a single SQL query string, we will send any dynamic parameters separately from the query itself, allowing the database to stick the pieces together securely without depending on PHP or the programmer. Let's see how this looks in the code
         5. First, we will modify our initial query by replacing any parameter with a placeholder indicated with a question mark (?). This will tell the database we want to run a query that takes two parameters as inputs. The query will then be passed to the mysqli_prepare() function instead of our usual mysqli_query(). mysqli_prepare() will not run the query yet but will indicate to the database to prepare the query with the given syntax. This function will return a prepared statement.

            ```PHP
               $query="select * from toys where name like ? or description like ?";
               $stmt = mysqli_prepare($db, $query);
            ```

         6. To execute our query, MySQL needs to know the value to put on each placeholder we defined before. We can use the mysqli_stmt_bind_param() function to attach variables to each placeholder. This function requires you to send the following function parameters: The first parameter should be a reference to the prepared statement to which to bind the variables. The second parameter is a string composed of one letter per placeholder to be bound, where letters indicate each variable's data type. Since we want to pass two strings, we put "ss" in the second parameter, where each "s" represents a string-typed variable. You can also use the letters "i" for integers or "d" for floats. You can check the full list in PHP's documentation. After that, you will need to pass the variables themselves. You must add as many variables as placeholders defined with ? in your query, which in our case, are two. Notice that, in our example, both parameters have the same content, but in other cases, it may not be so. The resulting code for this would be as follows:

            ```PHP
               $q = "%".$_GET['q']."%";
               mysqli_stmt_bind_param($stmt, 'ss', $q, $q);
            ```

         7. Once we have created a statement and bound the required parameters, we will execute the prepared statement using mysqli_stmt_execute(), which receives the statement $stmt as its only parameter.

            ```PHP
               mysqli_stmt_execute($stmt);
            ```

         8. Finally, when a statement has been executed, we can retrieve the corresponding result set using the mysqli_stmt_get_result(), passing the statement as the only parameter. We'll assign the result set to the $toys_rs variable as in the original code.

            ```PHP
               $toys_rs=mysqli_stmt_get_result($stmt);
            ```

      2. Run check with elves
         1. RESPONSE: `Flag2: THM{KodeNRoll}`

   3. TOY.PHP
      1. Check with elves
         1. RESPONSE: `Flag3: THM{Are we secure yet?}`

   4. LOGIN.PHP
      1. Check with elves
         1. I can bypass the login screen at: /login.php with SQL injection in the username. If you want to try it, just use any password with this as your username: ' OR 1=1-- x. It seems the login is sending the user and password via POST, so you may want to search for parameters sent using the $_POST[] array instead of $_GET (not that I would know any coding at all)
         2. Make sure to change var names to $username & $password
      2. Check with elves
         1. RESPONSE: `Flag4: THM{SQLi_who???}`


## QUESTIONS

1. What is the value of Flag 1?
   1. `THM{McCode, Elf McCode}`
2. What is the value of Flag 2?
   1. `THM{KodeNRoll}`
3. What is the value of Flag 3?
   1. `THM{Are we secure yet?}`
4. What is the value of Flag 4?
   1. `THM{SQLi_who???}`
