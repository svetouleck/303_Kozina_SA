<?php
require 'rb-sqlite.php';

R::setup('sqlite:../data/vehicle_inspection.db');
R::useFeatureSet( 'novice/latest' );
session_start();

$id = $_GET['master_id'];
$works = R::findLike('work_accounting', array('master_id' => $id));


foreach ($works as $work){
    $work->master = R::load('master', $work->master_id);
    $work->service = R::load('service', $work->service_id);
    $work->service_name = R::load('service_unique', $work['service']->service_unique_id);
  }
//print_r(length($works));
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"> 

    <title>Выполненные работы </title>
    <link rel='stylesheet' href='styles.css'>
  </head>
  <body>
   <div class="container">
       <div class="row">
           <div class="col-10">
           <a href="index.php"><button class="btn btn-submit mt-3" ><i class="fa fa-arrow-circle-left"></i></button></a>
           <h3 class='headers mt'>Выполненные работы</h2>
           <table class="table table-striped table-hover mt-4">
           <thead class="table-dark">
               <th>id</th>
               <th>Мастер</th>
               <th>Услуга</th>
               <th>Дата</th>
               <th>Стоимость</th>
           </thead>
           <tbody>
              <?php foreach ($works as $value) {
                $fio = $value['master']['last_name'].' '.$value['master']['name']; 
                if ($value['master']['patronymic'] != 'NULL')
                   $fio = $fio.' '.$value['master']['patronymic'];
                ?>
              <tr>
				<td><?=$value['id'] ?></td>
				<td><?=$fio ?></td>
				<td><?=$value['service_name']['name']?></td>
                <td><?=$value['date']?></td>
                <td><?=$value['service']['price']?></td>
              </tr> <?php } ?>
           </tbody>
           </table>

           </div>
       </div>
   </div>

  </body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
