import re

f = open('strings').readlines()

regex = input("Enter your regex here: ")

for i in f:
    i = i.strip()
    x = re.match(regex, i)
    if x:
        print(x.string)
