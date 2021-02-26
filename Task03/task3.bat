#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo 1. Составить список фильмов, имеющих хотя бы одну оценку. Список фильмов отсортировать по году выпуска и по названиям. В списке оставить первые 10 фильмов.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT movies.title AS 'Movie', movies.year AS 'Year', COUNT(*) AS 'Ratings count' FROM movies INNER JOIN ratings ON movies.id = ratings.movie_id WHERE movies.year IS NOT NULL  GROUP BY movies.id ORDER BY movies.year, movies.title  LIMIT 10;"
echo " "

echo 2. Вывести список всех пользователей, фамилии (не имена!) которых начинаются на букву 'A'. Полученный список отсортировать по дате регистрации. В списке оставить первых 5 пользователей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT name AS 'User', register_date AS 'Date' FROM users WHERE instr(name, ' A') <> 0 ORDER BY register_date LIMIT 5;"
echo " "

echo 3. Написать запрос, возвращающий информацию о рейтингах в более читаемом формате: имя и фамилия эксперта, название фильма, год выпуска, оценка и дата оценки в формате ГГГГ-ММ-ДД. Отсортировать данные по имени эксперта, затем названию фильма и оценке. В списке оставить первые 50 записей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT users.name AS 'Expert', movies.title AS 'Movie', movies.year AS 'Year', ratings.rating AS "Rating", DATE(ratings.timestamp,'unixepoch') AS 'Ratings Date' FROM ratings INNER JOIN users ON ratings.user_id = users.id INNER JOIN movies ON ratings.movie_id = movies.id ORDER BY users.name, movies.title, ratings.rating LIMIT 50;" 
echo " "

echo 4. Вывести список фильмов с указанием тегов, которые были им присвоены пользователями. Сортировать по году выпуска, затем по названию фильма, затем по тегу. В списке оставить первые 40 записей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT movies.title AS 'Movie', movies.year AS 'Year', tags.tag FROM tags INNER JOIN movies ON tags.movie_id = movies.id WHERE movies.year IS NOT NULL  ORDER BY movies.year, movies.title, tags.tag LIMIT 40;"
echo " "

echo 5. Вывести список самых свежих фильмов. В список должны войти все фильмы последнего года выпуска, имеющиеся в базе данных. Запрос должен быть универсальным, не зависящим от исходных данных (нужный год выпуска должен определяться в запросе, а не жестко задаваться).
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo " SELECT movies.title AS 'Movie', movies.year AS 'Year' FROM movies WHERE year = (SELECT MAX(year) FROM movies)"
echo " "