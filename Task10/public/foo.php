<?php
require 'rb-sqlite.php';

R::setup('sqlite:../data/vehicle_inspection.db');
R::useFeatureSet( 'novice/latest' );
//R::freeze(true);


if (!R::testConnection()) {
    die('Не удалось подключиться к БД.');
}

// Отображение таблицы мастеров
$masters = R::findAll('master');
foreach ($masters as $master){
    $master->specialization = R::load('specialization', $master->specialization_id);
}

// Отображение списка специализаций
$spec_all = R::findAll('specialization');


// Удаление мастера из базы
if (isset($_POST['delete_submit'])) {

    $id = $_GET['id'];
    $master = R::load('master', $id);
    R::trash($master);
    header("Location: ".$_SERVER['REQUEST_URI']);
}

// Добавление мастера в БД
if (isset($_POST['add'])) {

    $gender_dict = array(
        "Male" => "М",
        "Female" => "Ж"
    );
    $new_master = R::dispense('master');

    $new_master->name = $_POST['name'];
    $new_master->last_name = $_POST['last_name'];
    $new_master->patronymic = $_POST['patronymic'];
    $new_master->specialization_id = $_POST['specialization'];
    $new_master->percent = $_POST['percent'];
    $new_master->gender = $gender_dict[$_POST['gender']];

    R::store($new_master);
    header("Location: ".$_SERVER['REQUEST_URI']);
	
}


// Обновление записи о мастере
if (isset($_POST['edit-submit'])) {

    $gender_dict = array(
        "Male" => "М",
        "Female" => "Ж"
    );

    $status_dict = array(
        1 => "работает",
        0 => "уволен"
    );
    $edit_master = R::dispense('master');

    $edit_master->name = $_POST['edit_name'];
    $edit_master->last_name = $_POST['edit_last_name'];
    $edit_master->patronymic = $_POST['edit_patronymic'];
    $edit_master->specialization_id = $_POST['edit_specialization'];
    $edit_master->percent = $_POST['edit_percent'];
    $edit_master->gender = $gender_dict[$_POST['edit_gender']];
    $edit_master->status = $status_dict[$_POST['edit_status']];
    $edit_master->id = $_GET['id'];

    R::store($edit_master);
    header("Location: ".$_SERVER['REQUEST_URI']);
}
