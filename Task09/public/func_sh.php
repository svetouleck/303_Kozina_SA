<?php

include 'db.php';

// Чтение дней недели
$sql = $pdo->prepare("SELECT * FROM `week_days`");
$sql->execute();
$week_days = $sql->fetchAll();

// Чтение ФИО мастера
$master_id = $_GET['master_id'];
            $sql = $pdo->prepare("SELECT id, last_name || ' ' || name || ' ' || patronymic AS 'master' FROM master
                                    WHERE id = ?");
            $sql->execute([$master_id]);
            $fio = $sql->fetchAll();

// Добавление элемента графика
if (isset($_POST['addSh'])) {

    $id_day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

	$sql = ("INSERT INTO work_schedule (`id_master`, `id_day`,
                `start_time`, `end_time`) VALUES(?,?,?,?)");
	$query = $pdo->prepare($sql);
	$query->execute([$master_id, $id_day, $start_time, $end_time]);

    header("Location: ".$_SERVER['REQUEST_URI']);
	
}


//Отображение таблицы графика работы
$sql = $pdo->prepare("SELECT work_schedule.id AS id,
                         work_schedule.id_master AS id_master,
                         week_days.day AS day,
                         work_schedule.start_time AS start_time, 
                         work_schedule.end_time AS end_time
                      FROM work_schedule INNER JOIN week_days
                      ON work_schedule.id_day = week_days.id
                      WHERE id_master = ?
                      ORDER BY week_days.id");
$sql->execute([$master_id]);
$result = $sql->fetchAll();


// Обновление записи графика работы
if (isset($_POST['edit-submit-sh'])) {

    $id_day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $id = $_GET['id'];

	$sqll = "UPDATE work_schedule SET id_master=?, id_day=?,
                start_time=?, end_time=?
            WHERE id=?";

	$querys = $pdo->prepare($sqll);
	$querys->execute([$master_id, $id_day, $start_time, $end_time, $id]);

    header("Location: ".$_SERVER['REQUEST_URI']);
}

// Удаление элемента графика из базы
if (isset($_POST['delete_submit_sh'])) {
    $id = $_GET['id'];

	$sql = "DELETE FROM work_schedule WHERE id=?";
	$query = $pdo->prepare($sql);
	$query->execute([$id]);
	 header("Location: ".$_SERVER['REQUEST_URI']);
}