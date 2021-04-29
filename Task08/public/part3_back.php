<?php
$pdo = new PDO('sqlite:../data/vehicle_inspection.db');

$mode = $_GET['mode'];

switch ($mode){
    case 'masters':
        get_masters($pdo);
        //echo "----";
        break;
    case 'service':
        get_services($pdo);
        //echo "----";
        break;
    default: 
        echo "anything";
        break;
}

function get_masters($pdo){
    
    $query_master_id = "SELECT id AS 'id',
        last_name || ' ' || name || ' ' || patronymic AS 'master'
        FROM master";


    $statement = $pdo->query($query_master_id);
    $masters_id = $statement->fetchAll();

    foreach ($masters_id as $rows){
        print_r($rows['id'] . "|". $rows['master'] . "\n");
    }

    $statement->closeCursor();
}

function get_services($pdo){
    $master_id = json_decode(file_get_contents("php://input"), true)['id'];

    $query_services = "SELECT service_unique.id AS 'id',
                              service_unique.name as 'service'
                       FROM service_unique INNER JOIN service_master
                       ON service_unique.id = service_master.id_service
                       INNER JOIN master
                       ON service_master.id_master = master.id
                       WHERE  master.id = :master_id";

    $statement = $pdo->prepare($query_services);
    $statement->execute(['master_id' => $master_id]);
    $masters_id = $statement->fetchAll();

    foreach ($masters_id as $rows){
        print_r($rows['id'] . "|". $rows['service'] . "\n");
    }

    $statement->closeCursor();
    
}