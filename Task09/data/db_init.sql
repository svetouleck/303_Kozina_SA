.open vehicle_inspection.db

DROP TABLE IF EXISTS service_unique;
DROP TABLE IF EXISTS auto_category;
DROP TABLE IF EXISTS service;
DROP TABLE IF EXISTS service_master;
DROP TABLE IF EXISTS master;
DROP TABLE IF EXISTS work_accounting;
DROP TABLE IF EXISTS work_schedule;
DROP TABLE IF EXISTS week_days;
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
  status CHAR DEFAULT 'работает',
  gender CHAR,
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

CREATE TABLE week_days (
  id INTEGER PRIMARY KEY,
  day CHAR
);
		
CREATE TABLE work_schedule (
  id INTEGER PRIMARY KEY,
  id_master INTEGER,
  id_day INTEGER,
  start_time TIME NULL DEFAULT '08:00',
  end_time TIME NULL DEFAULT '18:00',
  
  FOREIGN KEY (id_master) REFERENCES master (id),
  FOREIGN KEY (id_day) REFERENCES week_days (id)
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


INSERT INTO week_days(day) VALUES
('пн'),
('вт'),
('ср'),
('чт'),
('пт'),
('сб'),
('вс');


 INSERT INTO master (id, last_name, name, patronymic, gender, id_specialization, percent, status) VALUES
(0,'Костин','Даниил','Германович','М',1,13,'работает'),
(1,'Волков','Максим','Львович','М',3,11,'уволен'),
(2,'Морозов', 'Роман', 'Даниилович','М',2,20,'работает'),
(3,'Белова', 'Анастасия', 'Демидовна','Ж',2,11,'уволен'),
(4,'Игнатьев', 'Егор', 'Иванович','М',1,11,'работает'),
(5,'Волкова', 'Кристина', 'Михайловна','Ж',1,19,'работает'),
(6,'Абрамов', 'Владимир', 'Михайлович','М',1,30,'работает'),
(7,'Софронов', 'Иван', 'Андреевич','М',3,11,'работает'),
(8,'Максимов', 'Максим', 'Матвеевич','М',3,14,'уволен'),
(9,'Симонова', 'Ольга', 'Егоровна','Ж',1,21,'работает'),
(10,'Ульянов', 'Георгий', 'Андреевич','М',2,17,'работает'),
(11,'Попов', 'Станислав', 'Макарович','М',2,22,'работает'),
(12,'Алексеев', 'Николай', 'Федоровия','М',2,19,'работает');


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


INSERT INTO work_schedule (id, id_master, id_day, start_time, end_time) VALUES
(0, 0, 7, '08:00', '13:00'),
(1, 0, 1, '08:00', '18:00'),
(2, 0, 2, '12:00', '18:00'),
(3, 0, 3, '08:00', '18:00'),
(4, 0, 4, '12:00', '18:00'),
(5, 0, 5, '08:00', '13:00'),
(6, 0, 6, '08:00', '18:00'),

(7, 1, 7, '08:00', '13:00'),
(8, 1, 1, '08:00', '18:00'),
(9, 1, 2, '12:00', '18:00'),
(10, 1, 3, '08:00', '18:00'),
(11, 1, 4, '12:00', '18:00'),
(12, 1, 5, '08:00', '13:00'),
(13, 1, 6, '08:00', '18:00'),

(14, 2, 7, '08:00', '13:00'),
(15, 2, 1, '08:00', '18:00'),
(16, 2, 2, '12:00', '18:00'),
(17, 3, 3, '08:00', '18:00'),
(18, 3, 4, '12:00', '18:00'),
(19, 3, 5, '08:00', '13:00'),

(20, 4, 7, '08:00', '13:00'),
(21, 4, 1, '08:00', '18:00'),
(22, 4, 2, '12:00', '18:00'),
(23, 5, 3, '08:00', '18:00'),
(24, 5, 4, '12:00', '18:00'),
(25, 5, 5, '08:00', '13:00'),
(26, 5, 6, '08:00', '18:00'),

(27, 6, 7, '12:00', '18:00'),
(28, 6, 1, '08:00', '18:00'),
(29, 6, 2, '12:00', '18:00'),
(30, 6, 3, '08:00', '18:00'),
(31, 7, 4, '12:00', '18:00'),
(32, 7, 5, '08:00', '13:00'),
(33, 7, 6, '08:00', '18:00'),

(34, 8, 7, '08:00', '13:00'),
(35, 9, 1, '13:00', '18:00'),
(36, 10, 2, '13:00', '18:00'),
(37, 8, 3, '08:00', '13:00');
