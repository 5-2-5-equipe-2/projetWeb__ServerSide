import pandas as pd
from sqlalchemy import create_engine
import numpy as np
from PIL import Image
import pymysql
from time import sleep,time
pymysql.install_as_MySQLdb()
imgpil = Image.open("degradefunc.png")
img = np.array(imgpil)  # Transformation de l'image en tableau numpy

print("debut")

def to_hex(r, g, b):
    return '#{:02x}{:02x}{:02x}'.format(r, g, b)


dict = {"color_id": [], "id": [], "last_updated": [],
        "number_of_times_placed": [], "x_position": [], "y_position": []}
colorsdict = {"hex_code": ["#FFFFFF", "#000000"],
              "id": [6, 5], "name": ["white", "black"]}
i = 1
id = 7
t=time()
for i, j in enumerate(img):
    # print("a",end="")
    print(".", end="\n")
    sleep(0.1)
    for k, l in enumerate(j):
        if to_hex(*l) in colorsdict["hex_code"]:
            colid = colorsdict["id"][colorsdict["hex_code"].index(to_hex(*l))]
        else:
            colorsdict["hex_code"].append(to_hex(*l))
            colorsdict["id"].append(i+1)
            colorsdict["name"].append("?")
            id += 1
            colid = id
        dict["color_id"].append(colid)
        dict["id"].append(i)
        dict["last_updated"].append("2020-01-01")
        dict["number_of_times_placed"].append(1)
        dict["x_position"].append(k)
        dict["y_position"].append(i)
        i+=1
print("fin:",time()-t)
t=time()
df = pd.DataFrame(dict)
print(df)

df2 = pd.DataFrame(colorsdict)
print(df2)
print("fin2:",time()-t)
df.to_csv("triangle.csv", index=False)
df2.to_csv("colors.csv", index=False)
# engine = create_engine('mysql://root:my_secret_password@localhost:3307/app_db')
# df.to_sql("pixel", con=engine, if_exists="replace")
# df2.to_sql("color", con=engine, if_exists="replace")
