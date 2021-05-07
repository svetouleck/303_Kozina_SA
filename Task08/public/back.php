<?php
$pdo = new PDO('sqlite:../data/vehicle_inspection.db');

$mode = $_GET['mode'];

switch ($mode){
    case 'services':
        get_services($pdo);
        break;
    case 'specialization':
        get_specialization($pdo);
        break;
    case 'masters':
        get_masters($pdo);
        break;
    case 'service-for-master':
        $master_id = json_decode(file_get_contents("php://input"), true)['id'];
        get_services_for_master($pdo, $master_id);
        break;
    case 'get_times':
        get_times($pdo);   
        break;
    case 'submit':
        $master = json_decode(file_get_contents("php://input"), true);
        print_r($master);
        add_master_to_base($master, $pdo);
        break;
    case 'submit_work':
        $work = json_decode(file_get_contents("php://input"), true);
        print_r($work);
        echo "=============";
        add_work_to_base($work, $pdo);
        break;
    default: 
        echo "anything";
        break;
}

function add_master_to_base($master, $pdo){
    $query_for_max_id = "SELECT max(`id`) as `maxid` from master";

    $statement = $pdo->query($query_for_max_id);
    $data = $statement->fetchAll();
    $master_id = $data[0][0] + 1;
    $statement->closeCursor();


    $query_for_max_id = "SELECT max(`id`) as `maxid` from person_schedules";

    $statement = $pdo->query($query_for_max_id);
    $data = $statement->fetchAll();
    $shed_id = $data[0][0] + 1;
    $statement->closeCursor();

    
    $query = "INSERT INTO person_schedules (id, 
                 tue_start, tue_end,
                 mon_start, mon_end,
                 wed_start, wed_end,
                 thu_start, thu_end,
                 fri_start, fri_end,
                 sat_start, sat_end,
                 sun_start, sun_end) VALUES
              (:id, :tue_start, :tue_end,
                 :mon_start, :mon_end,
                 :wed_start, :wed_end,
                 :thu_start, :thu_end,
                 :fri_start, :fri_end,
                 :sat_start, :sat_end,
                 :sun_start, :sun_end)";

    $week = array(
        1 => 'mon',
        2 => 'tue',
        3 => 'wed',
        4 => 'thu',
        5 => 'fri',
        6 => 'sat',
        7 => 'sun'
    );
 
    $statement = $pdo->prepare($query);
    $to_execute = array();
    $to_execute += ['id' => $shed_id];
    print_r($to_execute);

    for ($i=1; $i<8; $i++){
        echo($week[$i]);
        print_r("$week[$i]_start");
        //print_r($master['shedule'][$week[$i]]);

        $to_execute += ["$week[$i]_start" => $master['shedule'][$week[$i]][0]];
        $to_execute += ["$week[$i]_end" => $master['shedule'][$week[$i]][1]];
    }
    print_r($to_execute);
    $statement->execute($to_execute);
    $statement->closeCursor();


    $query = "INSERT INTO master (id, last_name, name, patronymic, gender, id_specialization, percent, id_person_schedules) VALUES
              (:id, :last_name, :name, :patronymic, :gender, :id_specialization, :percent, :id_person_schedules)";
    
    $statement = $pdo->prepare($query);
    $statement->execute([
        'id' => $master_id, 
        'last_name' => $master['lastName'], 
        'name' => $master['name'], 
        'patronymic'  => $master['patronymic'], 
        'gender' => $master['gender'] ,
        'id_specialization' => $master['spec'],
        'percent' => $master['percent'],
        'id_person_schedules' => $shed_id
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
    $service_id = $statement->fetchAll();

    $services = array();

    foreach ($service_id as $rows){
        $services += [$rows['id'] => $rows['name']];
        //print_r($rows['id'] . "|". $rows['name'] . "\n");
    }
    print_r(json_encode(['services' => $services]));

    $statement->closeCursor();
    
}

function get_specialization($pdo){
    $query_services = "SELECT * FROM specialization";

    $statement = $pdo->query($query_services);
    $spec_id = $statement->fetchAll();

    $speces = array();

    foreach ($spec_id as $rows){
        $speces += [$rows['id'] => $rows['name']];
        //print_r($rows['id'] . "|". $rows['name'] . "\n");
    }
    print_r(json_encode(['specializations' => $speces]));

    $statement->closeCursor();
    
}

function get_masters($pdo){
    $query = "SELECT id AS 'id',
        last_name || ' ' || name || ' ' || patronymic AS 'master'
        FROM master";

    $statement = $pdo->query($query);
    $masters_id = $statement->fetchAll();

    $masters = array();

    foreach ($masters_id as $rows){
        $masters += [$rows['id'] => $rows['master']];
        //print_r($rows['id'] . "|". $rows['master'] . "\n");
    }
    print_r(json_encode(['masters' => $masters]));

    $statement->closeCursor();
}

function get_services_for_master($pdo, $master_id){

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

    $services = array();

    foreach ($masters_id as $rows){
        $services += [$rows['id'] => $rows['service']];
        //print_r($rows['id'] . "|". $rows['service'] . "\n");
    }
    print_r(json_encode(['services' => $services]));

    $statement->closeCursor();

}

function get_times($pdo){

    $input_data = json_decode(file_get_contents("php://input"), true);
    $master_id = $input_data['master_id'];
    $service_id = $input_data['service_id'];
    $date = $input_data['date'];
    $week_day = $input_data['week_day'];

    $week = array(
        1 => 'mon',
        2 => 'tue',
        3 => 'wed',
        4 => 'thu',
        5 => 'fri',
        6 => 'sat',
        7 => 'sun'
    );
    
    $query_work_day = "SELECT master.id AS 'master_id',
        person_schedules.{$week[$week_day]}_start AS 'start',
        person_schedules.{$week[$week_day]}_end AS 'end'
        FROM master INNER JOIN person_schedules
        ON master.id_person_schedules = person_schedules.id
        WHERE master_id = :master_id";

    $statement = $pdo->prepare($query_work_day);
    $statement->execute(['master_id' => $master_id]);
    $table = $statement->fetchAll();

    $start_time = $table[0]['start'];
    $end_time = $table[0]['end'];
    //print_r($start_time . " " . $end_time);
    
    //print_r($table);
    if ($start_time == 'NULL' || $end_time == 'NULL'){
        print_r(json_encode(['error' => 'Данный мастер сегодня не работает']));
        return;
    }

    $statement->closeCursor();

    // Определение длительности необходимой пользователю услуги

    $query = "SELECT service_master.id_service AS id_service,
                service_master.id_master AS id_master,
                service.timing_min AS 'timing'
        FROM service_master INNER JOIN service
        ON service_master.id_service = service.id
        WHERE id_service = :service_id
          AND id_master = :master_id";

    $statement = $pdo->prepare($query);
    $statement->execute([
        'master_id' => $master_id,
        'service_id' => $service_id
        ]);
    $timing = $statement->fetchAll();
    $timing = $timing[0]['timing'];

    // получение пред. записей на текущую дату
    $query = "SELECT master.id AS 'master_id',
            service.id AS 'service_id',
            work_accounting.date AS 'date',
            work_accounting.time AS 'time',
            service.timing_min AS 'timing'
        FROM work_accounting INNER JOIN service_master
        ON work_accounting.id_service_master = service_master.id
        INNER JOIN master ON
        service_master.id_master = master.id
        INNER JOIN service ON
        service_master.id_service = service.id
        WHERE master_id = :master_id 
          AND date = :date
          
        ";
            //AND 'data' = :data
    //print_r($date . " ". $master_id . " ");

    $statement = $pdo->prepare($query);
    $statement->execute([
        'master_id' => $master_id,
        'date' => $date
        ]);
    $table = $statement->fetchAll();
    //print_r($table);

    $statement->closeCursor();
    get_timing_periods($table, $start_time, $end_time, (int)$timing);
    
}

function get_timing_periods($table, $start, $end, $timing){
    $start_min = time2min($start);
    $end_min = time2min($end);

    $periods = array();

    $N = floor(($end_min - $start_min) / $timing);

    for ($i=0; $i<$N-1; $i++){
        $start_period = $start_min + $i*$timing;
        $end_period = $start_min + ($i+1)*$timing;
        $k = 0;
        
        foreach ($table as $accord){
            if ($accord['time'] == 'NULL' || $accord['time'] == 'all'){
                print_r('{}');
                return;
            }
            $accord_start = time2min($accord['time']);
            $accord_timing = $accord['timing'];
            $accord_end = $accord_start + $accord_timing;



            if (inRange($accord_start, $start_period, $end_period) && 
                inRange($accord_end, $start_period, $end_period) || 
                outOfRange($accord_start, $start_period, $end_period) && 
                inRange($accord_end-1, $start_period, $end_period) ||
                inRange($accord_start+1, $start_period, $end_period) && 
                outOfRange($accord_end, $start_period, $end_period) ||
                $accord_start <= $start_period && $accord_end >= $end_period){
                    $k++;
                    //echo "******" . $k . "*********\n";
                    
                }
        }
        if ($k == 0){
            $periods += [ count($periods) => [
                'start' => min2time($start_period),
                'end' => min2time($end_period)
            ]];
            //echo min2time($start_period) . " - " . min2time($end_period) . "\n";
        }
    }
    print_r(json_encode(['periods' => $periods]));
}

function time2min($time){
    $x = explode(":", $time);
    return($x[0]*60 + $x[1]);
}

function inRange($x, $a, $b){
    return ($x>=$a && $x<=$b);
}

function outOfRange($x, $a, $b){
    return ($x<$a || $x>$b);
}

function min2time($min){
    $hour = intdiv($min, 60);
    return(substr("0".$hour, -2) . ":" . substr("0" . ($min - $hour*60), -2));
}

function add_work_to_base($work, $pdo){

    /*'master_id' : master_id,
    'service_id' : service_id,
    'time' : period_start, 
    'date' : str_date*/

    // поиск id в service_master по master_id и service_id
    $query = "SELECT id, id_master, id_service
              FROM service_master
              WHERE id_master = :master_id
                AND id_service = :service_id";

    $statement = $pdo->prepare($query);
    $statement->execute([
        'master_id' => $work['master_id'],
        'service_id' => $work['service_id'],
        ]);

    $ID_SM = $statement->fetchAll();
    $ID_SM = $ID_SM[0]['id'];

    $statement->closeCursor();
    //добавление записи
    $query = "INSERT INTO work_accounting (date, time, id_service_master) VALUES
              (:date, :time, :id_service_master)";
    
    $statement = $pdo->prepare($query);
    $statement->execute([
        'date' => $work['date'], 
        'time' => $work['time'], 
        'id_service_master'  => $ID_SM
    ]);
    $statement->closeCursor();
}