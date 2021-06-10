<?php include 'func_sh.php';
session_start(); 
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"> 

    <title>График работы</title>
    <link rel='stylesheet' href='styles.css'>
  </head>
  <body>
   <div class="container">
       <div class="row">
           <div class="col-7">
           <a href="index.php"><button class="btn btn-submit mt-3" ><i class="fa fa-arrow-circle-left"></i></button></a>
           <h3 class='headers '>График работы</h3>
           <h5 class='headers mt-3 '><?=$fio_1?></h5>
           <table class="table table-striped table-hover mt-4">
           <thead class="table-dark">
               <th>День недели</th>
               <th>Начало работы</th>
               <th>Окончание работы</th>
               <th>Action</th>
           </thead>
           <tbody>
           <?php foreach ($shedule as $value) {?>
              <tr>
				<td><?=$value['week_day']['day'] ?></td>
				<td><?=$value['start_time'] ?></td>
				<td><?=$value['end_time']?></td>
                <td>
				   <a href="?edit=<?=$value['id'] ?>" class="btn btn-success btn-sm" 
                     data-toggle="modal" data-target="#editModalSh<?=$value['id'] ?>">
                    <i class="fa fa-edit"></i>
                  </a> 
				  <a href="?delete=<?=$value['id'] ?>$master_id=<?$master['id']?>" class="btn btn-danger btn-sm" 
                     data-toggle="modal" data-target="#deleteModalSh<?=$value['id'] ?>">
                    <i class="fa fa-trash"></i>
                  </a>
                  <?php require 'modalSh.php'; ?>
                </td>
              </tr> <?php } ?>
           </tbody>
           </table>
           <button class="btn btn-success mt-3" data-toggle="modal" data-target="#AddShModal"><i class="fa fa-calendar-plus"></i></button>
           </div>
       </div>
   </div>

    <!-- Добавление элемента графика -->
  <div class="modal fade " tabindex="-1" role="dialog" id='AddShModal'>
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow">
      
        <div class="modal-header">
          <h5 class="modal-title" >Добавить запись</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body ">
          <form action="" method='post'>

            <div class="form-group ">
                <select name="day" class="form-control" required>
                  <option value="" disabled selected> День недели </option>
                  <?php foreach ($week_days as $day) { 
                      $k = 0;
                      foreach ($shedule as $value){
                        if ($value['week_day']['day'] == $day['day']) $k++;
                        
                      }
                      if ($k==0){
                        ?>
                      <option value='<?=$day['id']?>'> <?=$day['day']?></option>
                      <?php } 
                    } ?>
 
                </select>
            </div>

            <div class="form-group">
                <label class="form-check-label">Начало работы</label>
                <input class="form-control" type="time" name='start_time' value="09:00">
            </div>

            <div class="form-group">
                <label class="form-check-label">Окончание работы</label>
                <input class="form-control" type="time" name='end_time' value="18:00">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
              <button type="submit" class="btn btn-primary" name='addSh'>Сохранить</button>
            </div>
            
          </form>
        </div>

      </div>
    </div>
  </div>

  </body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
