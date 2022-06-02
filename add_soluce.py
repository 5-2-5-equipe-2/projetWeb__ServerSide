from dataclasses import dataclass
import mysql.connector
import random
import json

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="my_secret_password",
  database="app_db"
)

fp=open("liste_francais.txt","r")
buf=fp.read().split("\n")
del buf[-1]
fp.close()
mycursor = mydb.cursor()

sql = "INSERT INTO games_soluce (id, game_code, soluce) VALUES (0, %s, %s)"
end=[]
for b in buf:
    if "?" not in b:
        end.append(b)

for i in range(0,10000):
    n=random.choices(end,k=3)
    b=json.dumps({"w1":n[0],"w2":n[1],"w3":n[2]})
    val = (3, b)
    mycursor.execute(sql, val)

mydb.commit()