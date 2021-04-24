<?php
$pdo = new PDO('sqlite:../database/vehicle_inspection.db');

$query_base = "SELECT master.id AS 'id',
                 master.last_name || ' ' || master.name || ' ' || master.patronymic AS 'master',
                 work_accounting.date AS 'date',
                 service_unique.name AS 'service_name',
                 service.price AS 'price'

    FROM work_accounting INNER JOIN service_master
    ON work_accounting.id_service_master = service_master.id
    INNER JOIN master
    ON service_master.id_master = master.id
    INNER JOIN service
    ON service_master.id_service = service.id
    INNER JOIN service_unique
    ON service.id_service_unique = service_unique.id \n";


$mode = $_GET['mode'];


switch ($mode){
    case 'masters_id':
        masters_id($pdo);
        echo "----";
        showDataBase($pdo, $query_base);
        break;
    case 'getByMastersId':
        $id = file_get_contents("php://input");
        if ($id == 'all') {
            showDataBase($pdo, $query_base);
        } else {
            masters_by_id($pdo, $query_base, $id);
        }
        break;
    default:
        echo "anything";
        break;
}



function masters_id($pdo){

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

function showDataBase($pdo, $query_base){
    
    $query = $query_base . " ORDER BY master, date";
    $statement = $pdo->query($query);
    $table = $statement->fetchAll();
    //print_r($rows);

    $columns_count = count($table[0])/2;
    $columns_names = new SplFixedArray($columns_count);

    for ($i = 0; $i < $columns_count*2; $i+=2){
        $columns_names[$i/2] = array_keys($table[0])[$i];
    }
    $item = '';
        foreach ($columns_names as $column){
            //print_r($column);
            $item = $item . " | " . $column;
        }
        print_r($item . "\n");

    foreach ($table as $rows){
        $item = '';
        foreach ($columns_names as $column){
            //print_r($column);
            $item = $item . " | " . $rows[$column];
        }
        print_r($item . "\n");
    }


    $statement->closeCursor();
}

function masters_by_id($pdo, $query_base, $check_id){

    $master_query = $query_base . "WHERE master.id = :check_id
                                  ORDER BY master, date ";

    $statement = $pdo->prepare($master_query);
    $statement->execute(['check_id' => $check_id]);
    $table = $statement->fetchAll();

    if (!empty($table)) {

        $columns_count = count($table[0])/2;
        $columns_names = new SplFixedArray($columns_count);

        for ($i = 0; $i < $columns_count*2; $i+=2){
            $columns_names[$i/2] = array_keys($table[0])[$i];
        }

        $item = '';
        foreach ($columns_names as $column){
            //print_r($column);
            $item = $item . " | " . $column;
        }
        print_r($item . "\n");

        foreach ($table as $rows){
            $item = '';
            foreach ($columns_names as $column){
                //print_r($column);
                $item = $item . " | " . $rows[$column];
            }
            print_r($item . "\n");
        }

    } else echo "NotFound";

    $statement->closeCursor();
}