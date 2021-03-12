#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo 1. Найти все драмы, выпущенные после 2005 года, которые понравились женщинам (оценка не ниже 4.5). Для каждого фильма в этом списке вывести название, год выпуска и количество таких оценок.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT movies.title as 'Movie', movies.year as 'Year', COUNT(movies.title) FROM movies INNER JOIN ratings ON movies.id = ratings.movie_id INNER JOIN users ON ratings.user_id = users.id WHERE (movies.genres LIKE '%%Drama%%') AND (movies.year > 2005) AND (users.gender = 'female') AND (ratings.rating >= 4.5 ) GROUP BY movies.title;"
echo " "

echo 2. Провести анализ востребованности ресурса - вывести количество пользователей, регистрировавшихся на сайте в каждом году. Найти, в каких годах регистрировалось больше всего и меньше всего пользователей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " CREATE TEMP VIEW popular_year AS SELECT id, strftime('%%Y', users.register_date) AS year, COUNT(strftime('%%Y', users.register_date)) as 'count_' FROM users GROUP BY year ORDER BY count_; SELECT * FROM popular_year; SELECT year, count_ FROM (SELECT year, count_, MIN(count_)over() AS 'min_count', MAX(count_)over() AS 'max_count' FROM popular_year) WHERE (count_ = min_count) OR (count_ = max_count); DROP VIEW popular_year;"
echo " "

echo 3. Найти все пары пользователей, оценивших один и тот же фильм. Устранить дубликаты, проверить отсутствие пар с самим собой. Для каждой пары должны быть указаны имена пользователей и название фильма, который они ценили.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT Movies.title as Movie, User1.name as UserName1, User2.name as UserName2 FROM ratings Rating1 INNER JOIN ratings Rating2 ON Rating1.movie_id = Rating2.movie_id AND Rating1.id > Rating2.id INNER JOIN  movies Movies ON Rating1.movie_id = Movies.id INNER JOIN  users User1 ON Rating1.user_id = User1.id  INNER JOIN  users User2 ON Rating2.user_id = User2.id ORDER BY Movies.id LIMIT 50;"
echo " "

echo 4. Найти 10 самых старых оценок от разных пользователей, вывести названия фильмов, имена пользователей, оценку, дату отзыва в формате ГГГГ-ММ-ДД. 
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT DISTINCT DATE(ratings.timestamp, 'unixepoch') AS Date_, users.name, ratings.rating FROM ratings INNER JOIN users ON ratings.user_id = users.id GROUP BY users.name ORDER BY Date_ LIMIT 10;"
echo " "

echo 5. Вывести в одном списке все фильмы с максимальным средним рейтингом и все фильмы с минимальным средним рейтингом. Общий список отсортировать по году выпуска и названию фильма. В зависимости от рейтинга в колонке "Рекомендуем" для фильмов должно быть написано "Да" или "Нет"
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " CREATE TEMP VIEW AVG_rating AS SELECT movies.title AS Movie, movies.year AS Year_, AVG(ratings.rating) AS AVG_ FROM movies INNER JOIN ratings ON movies.id = ratings.movie_id GROUP BY movies.title; SELECT Movie, Year_, AVG_, CASE AVG_ WHEN max_AVG THEN 'YES' WHEN min_AVG THEN 'NO' END AS Recomended FROM (SELECT Movie, AVG_, Year_, MIN(AVG_)over() AS 'min_AVG', MAX(AVG_)over() AS 'max_AVG' FROM AVG_rating) WHERE ((AVG_ = min_AVG) OR (AVG_  = max_AVG)) AND (Year_ IS NOT NULL) ORDER BY Year_, Movie; DROP VIEW AVG_rating;"
echo " "

echo 6. Вычислить количество оценок и среднюю оценку, которую дали фильмам пользователи-мужчины в период с 2011 по 2014 год.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT SUM(Count_), AVG(AVG_) FROM (SELECT users.name AS User_, users.gender AS Gender, strftime('%%Y', DATE(ratings.timestamp,'unixepoch')) AS Year_, AVG(ratings.rating) AS AVG_, COUNT(users.name) AS Count_ FROM users INNER JOIN ratings ON users.id = ratings.user_id WHERE (users.gender = 'female') AND (Year_ IS NOT NULL) AND Year_ BETWEEN '2010' and '2012' GROUP BY users.name);"
echo " "

echo 7. Составить список фильмов с указанием средней оценки и количества пользователей, которые их оценили. Полученный список отсортировать по году выпуска и названиям фильмов. В списке оставить первые 20 записей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT movies.title AS Movie, movies.year AS YEAR_, COUNT(movies.title) AS COUNT_, AVG(ratings.rating) AS AVG_ FROM movies INNER JOIN ratings ON movies.id = ratings.movie_id GROUP BY movie_id ORDER BY movies.year, movies.title LIMIT 20;"
echo " "

echo 7. Определить самый распространенный жанр фильма и количество фильмов в этом жанре.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "CREATE VIEW Meow as WITH t(id,gen, rest) AS (SELECT id, null, genres FROM movies UNION ALL SELECT id, CASE WHEN instr(rest,'|') = 0 THEN rest ELSE substr(rest,1,instr(rest,'|')-1) END, CASE WHEN instr(rest,'|')=0 THEN NULL ELSE substr(rest,instr(rest,'|')+1) END FROM t WHERE rest is not NULL ORDER BY id) SELECT gen AS 'Genres', count(id) AS 'Number'  FROM t WHERE gen IS NOT NULL GROUP BY gen; SELECT Genres AS 'The Most Popular Genres', max(Number) AS 'Movies count' FROM Meow; DROP VIEW Meow;"
echo " "



