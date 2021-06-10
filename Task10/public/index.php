<?php include 'foo.php';
session_start(); 
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css"> 
    <link rel='stylesheet' href='styles.css'>

    <title>Главная</title>
  </head>
  <body>
   <div class="container">
       <div class="row">
           <div class="col-12">
           <table class="table table-striped table-hover mt-3">
           <thead class="table-dark">
               <th>id</th>
               <th>Мастер</th>
               <th>Специализация</th>
               <th>Action</th>
           </thead>
           <tbody>
           <?php foreach ($masters as $value) {
             $fio = $value['last_name'].' '.$value['name']; 
             if  ($value['patronymic'] != 'NULL')
                $fio = $fio.' '.$value['patronymic'];
             ?>
						<tr>
							<td><?=$value['id'] ?></td>
							<td><?=$fio?></td>
							<td><?=$value['specialization']['name']?></td>
							<td>
								<a href="?edit=<?=$value['id'] ?>" class="btn btn-success btn-sm" 
                  data-toggle="modal" data-target="#editModal<?=$value['id'] ?>">
                  <i class="fa fa-edit"></i>
                </a> 
								<a href="?delete=<?=$value['id'] ?>" class="btn btn-danger btn-sm" 
                  data-toggle="modal" data-target="#deleteModal<?=$value['id'] ?>">
                    <i class="fa fa-trash"></i>
                </a>
                <a href="schedule.php?master_id=<?=$value['id']?> " class="btn ">График</a>
                <a href="works.php?master_id=<?=$value['id'] ?>" class="btn" >Выполненные работы</a>
                <?php require 'modal.php'; ?>
							</td>
						</tr> <?php } ?>
           </tbody>
           </table>

           <button class="btn btn-success mt-3" data-toggle="modal" data-target="#AddModal"><i class="fa fa-user-plus"></i></button>

           </div>
       </div>
   </div>

  <!-- Добавление -->
  <div class="modal fade" tabindex="-1" role="dialog" id='AddModal'>
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow">
      
        <div class="modal-header">
          <h5 class="modal-title" >Добавить запись</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form action="" method='post'>
              <div class="form-group">
                <input type="text" class="form-control" name = 'last_name' value='' 
                  placeholder='Фамилия' class="form-control" required="required" autocomplete="off">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" name = 'name' value='' placeholder='Имя'
                  class="form-control" required="required" autocomplete="off">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" name = 'patronymic' value='' 
                  placeholder='Отчество' autocomplete="off">
              </div>

              <div class="form-group ">
                <select name="specialization" class="form-control" required>
                  <option value="" disabled selected> Специализация </option>
                  <?php foreach ($spec_all as $spec) { ?>
                  <option value='<?=$spec['id']?>'> <?=$spec['name']?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group">
                <input type="number" class="form-control" name = 'percent' value='' 
                  placeholder='Процент от зарплаты' required="required" autocomplete="off">
              </div>
              
              <div class="form-group col-1">
              <div class="form-check">
                  <input class="form-check-input radio-inline" type="radio" name="gender" id="Male" value="Male" checked>
                  <label class="form-check-label" for="Male"> M </label>
                </div>
                <div class="form-check ">
                  <input class="form-check-input radio-inline" type="radio" name="gender" id="Female" value="Female">
                  <label class="form-check-label" for="Female"> Ж </label>
                </div>
              </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
              <button type="submit" class="btn btn-primary" name='add'>Сохранить</button>
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