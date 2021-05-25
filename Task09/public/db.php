<?php
try {
	$pdo = new PDO('sqlite:../data/vehicle_inspection.db');
} catch (PDOException $e) {
	die('Ошибка соединения с базой'.$e->getMessage());
}