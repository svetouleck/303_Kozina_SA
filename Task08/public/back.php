<?php
$pdo = new PDO('sqlite:../data/vehicle_inspection.db');

$mode = $_GET['mode'];

switch ($mode){
    case 'services':
        get_services($pdo);
        //echo "----";
        break;
    case 'specialization':
        get_specialization($pdo);
        //echo "----";
        break;
    case 'submit':
        $master = json_decode(file_get_contents("php://input"), true);
        add_master_to_dase($master, $pdo);
        //echo "----";
        break;
    default: 
        echo "anything";
        break;
}

function add_master_to_dase($master, $pdo){
    $query_for_max_id = "SELECT max(`id`) as `maxid` from master";

    $statement = $pdo->query($query_for_max_id);
    $data = $statement->fetchAll();
    $master_id = $data[0][0] + 1;
    $statement->closeCursor();

    $query = "INSERT INTO master (id, last_name, name, patronymic, gender, id_specialization, percent) VALUES
              (:id, :last_name, :name, :patronymic, :gender, :id_specialization, :percent)";
    
    $statement = $pdo->prepare($query);
    $statement->execute([
        'id' => $master_id, 
        'last_name' => $master['lastName'], 
        'name' => $master['name'], 
        'patronymic'  => $master['patronymic'], 
        'gender' => $master['gender'] ,
        'id_specialization' => $master['spec'],
        'percent' => $master['percent']
    ]);
    $statement->closeCursor();

    

    foreach ($master['services'] as $service) {
        print_r($service);
        $query = "INSERT INTO service_master (id_master, id_service) VALUES
        (:id_master, :id_service)";

        $statement = $pdo->prepare($query);
        $statement->execute([
            'id_master' => $master_id, 
            'id_service' => $service
        ]);
        $statement->closeCursor();
    }




}

function get_services($pdo){
    $query_services = "SELECT * FROM service_unique";

    $statement = $pdo->query($query_services);
    $masters_id = $statement->fetchAll();

    foreach ($masters_id as $rows){
        print_r($rows['id'] . "|". $rows['name'] . "\n");
    }

    $statement->closeCursor();
    
}

function get_specialization($pdo){
    $query_services = "SELECT * FROM specialization";

    $statement = $pdo->query($query_services);
    $masters_id = $statement->fetchAll();

    foreach ($masters_id as $rows){
        print_r($rows['id'] . "|". $rows['name'] . "\n");
    }

    $statement->closeCursor();
    
}