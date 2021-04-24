

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


$mode = 10;

while ($mode != 0){
    echo "\n";
    echo "Выберите операцию: \n";
    echo "1. Вывести всю информацию об оказанных услугах \n";
    echo "2. Вывести информацию об услугах конкретного мастера \n";
    echo "0. Выход \n";
    echo "\n";

    $mode = readline();

    switch ($mode){
        case 1:
            $query = $query_base . " ORDER BY 'master', 'date'";
            $statement = $pdo->query($query);
            $rows = $statement->fetchAll();
            draw_table($rows);
            $statement->closeCursor();
            break;

        case 2:
            $query_master_id = "SELECT id AS 'master_id',
                                        last_name || ' ' || name || ' ' || patronymic AS 'master'
                                FROM master";

            $statement = $pdo->query($query_master_id);
            $masters_id = $statement->fetchAll();
            draw_table($masters_id);
            $statement->closeCursor();                    

            $check_id = readline("id мастера: ");

            if (!id_validation($check_id, $masters_id)) {
                echo "\n Мастера с таким id в базе данных нет \n";
                break;
            }

            echo "\n";
            $master_query = $query_base . "WHERE master.id = :check_id
                                      ORDER BY 'master', 'date' ";
            
            $statement = $pdo->prepare($master_query);
            $statement->execute(['check_id' => $check_id]);
            $rows = $statement->fetchAll();

            if (!empty($rows))
                draw_table($rows);
            else echo "Информации об оказанных услугах этого мастера у нас нет \n";
            $statement->closeCursor();
            break;

        default:
            break;

    }
}


function draw_table($table) {

    $columns_count = count($table[0])/2;
    $max_column_length = new SplFixedArray($columns_count);
    $columns_names = new SplFixedArray($columns_count);
    $table_width = 0;

    // получение название таблиц
    for ($i = 0; $i < $columns_count*2; $i+=2){
        $columns_names[$i/2] = array_keys($table[0])[$i];
        $max_column_length[$i/2] = iconv_strlen(array_keys($table[0])[$i]);
    }

    // Определение максимальной ширины каждого столбца

    foreach ($table as $row) {
        for ($i=0; $i<$columns_count; $i++) {
            if (iconv_strlen($row[$i]) > $max_column_length[$i]) {
                        $max_column_length[$i] = iconv_strlen($row[$i]);
            }
        }
    }

    // определение общей ширины таблицы
    for ($i = 0; $i < $columns_count; $i++){
        $table_width += $max_column_length[$i] + 2;
    }

    // Отрисовка таблицы с помощью псевдографики

    empty_line($columns_count, $max_column_length, 1);
    line_with_data($columns_count, $max_column_length, $columns_names);
    empty_line($columns_count, $max_column_length, 2);

    for ($i = 0; $i < count($table); $i++){
        line_with_data($columns_count, $max_column_length, $table[$i]);
    }

    empty_line($columns_count, $max_column_length, 3);

}

function empty_line($columns_count, $max_column_length, $mode){
    $middle_sep = '';
    $start_sep = '';
    $end_sep = '';

    switch ($mode){
        case 1:
            $middle_sep = "┬";
            $start_sep = '┌';
            $end_sep = '┐';
            break;

        case 2:
            $middle_sep = "┼";
            $start_sep = '├';
            $end_sep = '┤';
            break;

        case 3:
            $middle_sep = "┴";
            $start_sep = '└';
            $end_sep = '┘';
            break;
    }

    print_r($start_sep);
    for ($i = 0; $i < $columns_count; $i++){
        if ($i != $columns_count-1)
            print_r(str_repeat('─', $max_column_length[$i]+2) . $middle_sep );
        else
        print_r(str_repeat('─', $max_column_length[$i]+2) . $end_sep );
    }
    print_r("\n");
}

function line_with_data($columns_count, $max_column_length, $data){
    print_r("│");

    for ($i = 0; $i < $columns_count; $i++){
        $space_count = $max_column_length[$i]+1 - iconv_strlen($data[$i]);
        print_r(" " . $data[$i] . str_repeat(' ', $space_count) . "│");
    }
    print_r("\n");
}

function id_validation($id, $database){
    if (!is_numeric($id)) return FALSE;

    foreach ($database as $rows){
        if ($rows['master_id'] == $id) return True;
    }

    return FALSE;
}