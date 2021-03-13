#!/usr/local/bin/python
# coding: utf-8

import re    

def parse_line(s):
    #выделение id
    ind = s.find(",")
    id = s[:ind]
    s = s[ind+1:]
    
    #выделение genres
    ind = s.rfind(",")
    genres = s[ind+1:]
    s = s[:ind]
    if genres == '(no genres listed)':
        genres = "NULL"
    
    title="NULL"
    year = "NULL"
    
    if re.search(r'[(]\d\d\d\d[)]',s) is None:
        title_end = s.rfind(",")
        title = s[: title_end]
    else:
        #выделение year
        year_end = re.search(r'[(]\d\d\d\d[)]',s).end()
        year_start = re.search(r'[(]\d\d\d\d[)]',s).start()
        year = s[year_start+1:year_end-1]
        #выделение title 
        title = s[:year_start-1]
        
    title = title.replace("'", "‘")
    title = title.replace("\"", "")
    if title[-1] == " ":
        title = title[:-1]
    return [id, title, year, genres]
    """
    #выделение year
    year = "NULL"
    title = "NULL"
    genres = "NULL"
    
    if re.search(r'[(]\d\d\d\d[)]',s) is None:
        if re.search(r'(no genres listed)', s) is None:
            title_end = s.rfind(",")
            title = s[id_end+1: title_end]
            genres = s[title_end+1:]
        else:
            title = s[id_end+1:]
    else:
        #выделение year
        year_end = re.search(r'[(]\d\d\d\d[)]',s).end()
        year_start = re.search(r'[(]\d\d\d\d[)]',s).start()
        year = s[year_start+1:year_end-1]
        #выделение genres  
        genres = s[year_end+1:]
        #выделение title 
        title = s[id_end+1:year_start-1]
        
    
    return [id, title, year, genres]
"""


with open("db_init.sql", "w", encoding="utf-8") as fout:
    print(".open movies_rating.db\n", file=fout)
    
    # удаление таблиц, если они уже существуют
    print("DROP TABLE IF EXISTS movies;\n", file=fout)
    print("DROP TABLE IF EXISTS ratings;\n", file=fout)
    print("DROP TABLE IF EXISTS tags;\n", file=fout)
    print("DROP TABLE IF EXISTS users;\n", file=fout)
    
    
    
    # создание таблиц
    print("CREATE TABLE movies (id INTEGER RRIMARY KEY, title VARCHAR, year INTEGER, genres VARCHAR);\n",
          file=fout)
    print("CREATE TABLE ratings (id INTEGER RRIMARY KEY, user_id INTEGER, movie_id INTEGER, rating FLOAT, timestamp INTEGER);\n", 
          file=fout)
    print("CREATE TABLE tags (id INTEGER RRIMARY KEY, user_id INTEGER, movie_id INTEGER, tag VARCHAR, timestamp INTEGER);\n", 
          file=fout)
    print("CREATE TABLE users (id INTEGER RRIMARY KEY, name VARCHAR, email VARCHAR, gender VARCHAR, register_date DATE, occupation VARCHAR);\n", 
          file=fout)
    
    
  
    # парсинг файла movies.csv и заполнение таблицы movies
    print("INSERT INTO movies (id, title, year, genres)\n", file=fout)
    print(f"VALUES ", file=fout)

    with open("movies.csv", "r", encoding="utf-8") as f:
        file = f.readlines()[1:]
        #data = []
        for i in range(len(file)):
            #data.append(parse_line(row[:-1]))
            item = parse_line(file[i][:-1])
            if i != len(file)-1:
                print(f"({item[0]}, '{item[1]}', {item[2]}, '{item[3]}'),\n", file=fout)
            else:
                print(f"({item[0]}, '{item[1]}', {item[2]}, '{item[3]}');\n", file=fout) 
                
             
    # парсинг файла ratings.csv и заполнение таблицы ratings
    print("INSERT INTO ratings (id, user_id, movie_id, rating, timestamp)\n", file=fout)
    print(f"VALUES", end=" ", file=fout)
    
    with open("ratings.csv", "r") as fl:
        file = fl.readlines()[1:]
        for i in range(len(file)):
            item = file[i][:-1].split(",")
            if i != len(file)-1:
                print(f"({i}, {item[0]}, {item[1]}, {item[2]}, {item[3]}),\n", file=fout)
            else:
                print(f"({i}, {item[0]}, {item[1]}, {item[2]}, {item[3]});\n", file=fout) 
                
      
        
    # парсинг файла tags.csv и заполнение таблицы tags           
    print("INSERT INTO tags (id, user_id, movie_id, tag, timestamp)\n", file=fout)
    print(f"VALUES", end=" ", file=fout)
    
    with open("tags.csv", "r") as fl:
        file = fl.readlines()[1:]
        for i in range(len(file)):
            item = file[i][:-1]
            item = item.replace("'", "‘")
            item = item.split(",")
            if i != len(file)-1:
                print(f"({i}, {item[0]}, {item[1]}, '{item[2]}', {item[3]}),\n", file=fout)
            else:
                print(f"({i}, {item[0]}, {item[1]}, '{item[2]}', {item[3]});\n", file=fout) 
                
            
            
    # парсинг файла users.txt и заполнение таблицы users            
    print("INSERT INTO users (id, name, email, gender, register_date, occupation)\n", file=fout)
    print(f"VALUES", end=" ", file=fout)
    
    with open("users.txt", "r") as fl:
        file = fl.readlines()
        for i in range(len(file)):
            item = file[i][:-1]
            item = item.replace("'", "‘")
            item = item.split("|")
            if i != len(file)-1:
                print(f"( {item[0]}, '{item[1]}', '{item[2]}', '{item[3]}', '{item[4]}', '{item[5]}'),\n", file=fout)
            else:
                print(f"( {item[0]}, '{item[1]}', '{item[2]}', '{item[3]}', '{item[4]}', '{item[5]}');\n", file=fout) 
                      
                