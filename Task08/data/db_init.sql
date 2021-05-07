.open vehicle_inspection.db

DROP TABLE IF EXISTS service_unique;
DROP TABLE IF EXISTS auto_category;
DROP TABLE IF EXISTS service;
DROP TABLE IF EXISTS service_master;
DROP TABLE IF EXISTS master;
DROP TABLE IF EXISTS work_accounting;
DROP TABLE IF EXISTS person_schedules;
DROP TABLE IF EXISTS specialization;

PRAGMA foreign_keys=ON;
	
CREATE TABLE service_unique (
  id INTEGER PRIMARY KEY,
  name CHAR
);

CREATE TABLE auto_category (
  id INTEGER PRIMARY KEY,
  name CHAR,
  description CHAR
);
		
CREATE TABLE service (
  id INTEGER PRIMARY KEY,
  id_auto_category INTEGER,
  id_service_unique INTEGER,
  timing_min INTEGER,
  price INTEGER,
  FOREIGN KEY (id_auto_category) REFERENCES auto_category (id),
  FOREIGN KEY (id_service_unique) REFERENCES service_unique (id)

);
		
CREATE TABLE service_master (
  id INTEGER PRIMARY KEY,
  id_service INTEGER,
  id_master INTEGER,
  FOREIGN KEY (id_master) REFERENCES master (id),
  FOREIGN KEY(id_service)  REFERENCES service(id)
);
		
CREATE TABLE specialization (
  id INTEGER PRIMARY KEY,
  name CHAR
);
		
CREATE TABLE master (
  id INTEGER PRIMARY KEY,
  name CHAR,
  last_name CHAR,
  patronymic CHAR,
  id_specialization INTEGER,
  percent FLOAT,
  id_person_schedules INTEGER,
  status CHAR DEFAULT 'работает',
  gender CHAR,
  FOREIGN KEY (id_person_schedules) REFERENCES person_schedules (id)
  FOREIGN KEY (id_specialization) REFERENCES specialization (id),
  CHECK (status = 'работает' OR status = 'уволен'),
  CHECK (gender = 'М' OR gender = 'Ж'),
  CHECK (percent >= 0 AND percent <= 100)
);
		
CREATE TABLE work_accounting (
  id INTEGER PRIMARY KEY,
  date DATE,
  time TIME,
  id_service_master INTEGER,
  FOREIGN KEY (id_service_master) REFERENCES service_master (id)
);
		
		
CREATE TABLE person_schedules (
  id INTEGER PRIMARY KEY,
  mon_start TIME NULL DEFAULT '9:00',
  mon_end TIME NULL DEFAULT '17:00',
  tue_start TIME NULL DEFAULT '9:00',
  tue_end TIME NULL DEFAULT '17:00',
  wed_start TIME NULL DEFAULT '9:00',
  wed_end TIME NULL DEFAULT '17:00',
  thu_start TIME NULL DEFAULT '9:00',
  thu_end TIME NULL DEFAULT '17:00',
  fri_start TIME NULL DEFAULT '9:00',
  fri_end TIME NULL DEFAULT '17:00',
  sat_start TIME NULL DEFAULT '9:00',
  sat_end TIME NULL DEFAULT '17:00',
  sun_start TIME NULL DEFAULT '9:00',
  sun_end TIME NULL DEFAULT '17:00'
);

INSERT INTO auto_category (id,name,description) VALUES
(0,'L1', 'Двухколёсные мопеды/мотовелосипеды'),
(1, 'L2', 'Трёхколёсные мопеды/мотовелосипеды'),
(2, 'L3', 'Двухколёсные мотоциклы/мотороллеры'),
(3, 'L4', 'Трициклы с ассиметричными относительно средней продольной плоскости колёсами'),
(4, 'L5', 'Трициклы с симметричными относительно средней продольной плоскости колёсами'),
(5, 'L6', 'Квадрициклы с ненагруженной массой меньше 350 кг'),
(6, 'L7', 'Квадрициклы с ненагруженной массой меньше 400 кг'),
(7, 'M1', 'Легковые автомобили'),
(8, 'M2', 'Автобусы и троллейбусы с технически допустимой макс.массой не более 5 тонн'),
(9, 'M3', 'Автобусы и троллейбусы с технически допустимой макс.массой более 5 тонн'),
(10, 'N1', 'Грузовые автомобили с грузоподъёмностью не более 3.5 тонн'),
(11, 'N2', 'Грузовые автомобили с грузоподъёмностью более 3.5 тонн и не более 12 тонн'),
(12, 'N3', 'Грузовые автомобили с грузоподъёмностью более 12 тонн'),
(13, 'O1', 'Прицепы с грузоподъёмностью не более 0.75 тонн'),
(14, 'O2', 'Прицепы с грузоподъёмностью более 0.75 тонн и не более 3.5 тонн'),
(15, 'O3', 'Прицепы с грузоподъёмностью более 3.5 тонн и не более 10 тонн'),
(16, 'O4', 'Прицепы с грузоподъёмностью более 10 тонн');


INSERT INTO service_unique (id,name) VALUES
 (0,'Химчистка салона'),
 (1,'Полировка фар'),
 (2,'Восстановительная полировка кузова'),
 (3,'Слесарный ремонт автомобилей'),
 (4,'Оценка ущерба после ДТП'),
 (5,'Установка сигнализации'),
 (6,'Ремонт коммерческого транспорта'),
 (7,'Автомойка'),
 (8,'Замена амортизатора'),
 (9,'Замена масла'),
 (10,'Замена сцепления'),
 (11,'Установка камер заднего вида'),
 (12,'Ремонт бампера'),
 (13,'Замена свечей зажигания'),
 (14,'Замена масляного фильтра'),
 (15,'Промывка инжектора');


 INSERT INTO specialization (id,name) VALUES
 (0, 'Автомеханик'),
 (1, 'Автоэлектрик'),
 (2, 'Мастер кузовного ремонта'),
 (3, 'Автомаляр');


INSERT INTO person_schedules (id,mon_start,mon_end,tue_start,tue_end,wed_start,wed_end,thu_start,thu_end,fri_start,fri_end,sat_start,sat_end,sun_start,sun_end) VALUES
(0,'9:00','16:00','9:00','16:00','9:00','16:00','9:00','16:00','9:00','16:00','NULL','NULL','NULL','NULL'),
(1,'NULL','NULL','9:00','16:00','14:00','16:00','9:00','16:00','9:00','16:00','14:00','16:00','14:00','16:00'),
(2,'15:00','22:00','15:00','22:00','15:00','22:00','15:00','22:00','15:00','22:00','NULL','NULL','NULL','NULL'),
(3,'16:00','22:00','9:00','16:00','16:00','22:00','9:00','16:00','16:00','22:00','12:00','17:00','12:00','17:00'),
(4,'12:00','21:00','NULL','NULL','12:00','21:00','NULL','NULL','12:00','21:00','12:00','17:00','NULL','NULL'),
(5,'NULL','NULL','13:00','21:00','NULL','NULL','NULL','NULL','13:00','21:00','NULL','NULL','13:00','21:00'),
(6,'8:00','12:00','8:00','12:00','8:00','12:00','8:00','12:00','8:00','12:00','NULL','NULL','NULL','NULL'),
(7,'8:00','17:00','8:00','17:00','8:00','17:00','8:00','17:00','8:00','17:00','10:00','16:00','12:00','17:00'),
(8,'16:00','22:00','16:00','22:00','16:00','22:00','16:00','22:00','16:00','22:00','12:00','17:00','NULL','NULL'),
(9,'14:00','16:00','14:00','16:00','14:00','16:00','14:00','16:00','14:00','16:00','14:00','16:00','12:00','17:00'),
(10,'12:00','21:00','12:00','21:00','12:00','21:00','12:00','21:00','12:00','21:00','NULL','NULL','NULL','NULL');


 INSERT INTO master (id, last_name, name, patronymic, gender, id_specialization, percent, id_person_schedules, status) VALUES
(0,'Костин','Даниил','Германович','М',1,13,0,'работает'),
(1,'Волков','Максим','Львович','М',3,11,1,'уволен'),
(2,'Морозов', 'Роман', 'Даниилович','М',2,20,2,'работает'),
(3,'Белова', 'Анастасия', 'Демидовна','Ж',2,11,3,'уволен'),
(4,'Игнатьев', 'Егор', 'Иванович','М',1,11,4,'работает'),
(5,'Волкова', 'Кристина', 'Михайловна','Ж',1,19,5,'работает'),
(6,'Абрамов', 'Владимир', 'Михайлович','М',1,30,6,'работает'),
(7,'Софронов', 'Иван', 'Андреевич','М',3,11,7,'работает'),
(8,'Максимов', 'Максим', 'Матвеевич','М',3,14,8,'уволен'),
(9,'Симонова', 'Ольга', 'Егоровна','Ж',1,21,9,'работает'),
(10,'Ульянов', 'Георгий', 'Андреевич','М',2,17,10,'работает'),
(11,'Попов', 'Станислав', 'Макарович','М',2,22,2,'работает'),
(63,'Алексеев', 'Николай', 'Федоровия','М',2,19,2,'работает');


INSERT INTO service (id,id_auto_category,id_service_unique,timing_min,price) VALUES
 (0, 1, 14, 40, 5000),
 (1, 4, 1, 50, 400),
 (2, 6, 4, 20, 300),
 (3, 2, 13, 45, 200),
 (4, 11, 15, 30, 2000),
 (5, 14, 7, 30, 1000),
 (6, 15, 8, 60, 600),
 (7, 7, 9, 70, 100),
 (8, 9, 11, 20, 300),
 (9, 5, 10, 40, 1500),
 (10, 1, 6, 120, 2050),
 (11, 14, 2, 30, 700),
 (12, 15, 3, 60, 600),
 (13, 7, 5, 70, 1000),
 (14, 9, 12, 20, 300),
 (15, 5, 10, 40, 1500),
 (16, 1, 6, 120, 2500);


INSERT INTO service_master (id,id_service,id_master) VALUES
 (0, 1, 0),
 (1, 5, 2),
 (2, 6, 3),
 (3, 1, 4),
 (4, 3, 5),
 (5, 10, 6),
 (6, 3, 7),
 (7, 9, 8),
 (8, 8, 9),
 (9, 7, 10),
 (10, 5, 11),
 (11, 6, 2),
 (12, 4, 3),
 (13, 0, 4),
 (14, 2, 5);



-- статус "выполнено", "в процессе", "не начато" будет присваиваться при составлении запроса
--  в зависимости от текущей даты/времени и даты/времени начала оказания услуги


INSERT INTO work_accounting (id,date,time,id_service_master) VALUES
(0,'2021-05-04','16:00', 0),
(1,'2021-05-04','14:30', 1),
(2,'2021-05-04','13:00', 2),
(3,'2021-05-04','11:30', 3),
(4,'2021-05-04','9:00', 4),
(5,'2021-04-04','8:00', 5),
(6,'2021-04-04','10:00', 6),
(7,'2021-04-03','16:40', 7),
(8,'2021-04-09','15:00', 8),
(9,'2021-04-08','12:00', 9),
(11,'2021-04-03','18:00', 5),
(10,'2021-04-07','11:00', 10),
(12,'2021-05-04','10:00', 2);


