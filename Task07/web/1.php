<?php
$pdo = new PDO('sqlite:../database/vehicle_inspection.db');

$query_base = "SELECT master.id AS 'master_id',
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


$mode = $_GET;
#print_r($_GET['mode'] . "\n");
print_r($mode);
print_r($_GET['mode']);