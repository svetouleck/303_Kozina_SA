<!-- Редактирование -->

<div class="modal fade" id="editModal<?=$value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Редактировать запись № <?=$value['id'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="?id=<?=$value['id'] ?>" method="post">
        	<div class="form-group">
        		<input type="text" class="form-control" name="edit_last_name" value="<?=$value['last_name'] ?>" placeholder="Фамилия">
        	</div>

            <div class="form-group">
        		<input type="text" class="form-control" name="edit_name" value="<?=$value['name'] ?>" placeholder="Имя">
        	</div>

            <div class="form-group">
                <input type="text" class="form-control" name = 'edit_patronymic' value='<?=$value['patronymic'] ?>' 
                  placeholder='Отчество' autocomplete="off">
              </div>

              <div class="form-group ">
                <select name="edit_specialization" class="form-control" required value='<?=$value['spec'] ?>'>
                  <option value="" disabled selected> Специализация </option>
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
                <input type="number" class="form-control" name = 'edit_percent' value='<?=$value['percent'] ?>' 
                  placeholder='Процент от зарплаты' required="required" autocomplete="off">
              </div>

              <div class="form-group col-1">
              <div class="form-check">
                  <input class="form-check-input radio-inline" type="radio" name="edit_gender" id="Male" value="Male" <?=($value['gender']=='М') ? 'checked' : ''?>>
                  <label class="form-check-label" for="Male"> M </label>
                </div>
                <div class="form-check ">
                  <input class="form-check-input radio-inline" type="radio" name="edit_gender" id="Female" value="Female" <?=($value['gender']=='Ж') ? 'checked' : ''?>>
                  <label class="form-check-label" for="Female"> Ж </label>
                </div>
              </div>

            <div class="form-check form-switch ">
                <input class="form-check-input" type="checkbox" name="edit_status" <?=($value['status']=='работает') ? 'checked' : ''?>>
                <label class="form-check-label" for="flexSwitchCheckChecked">Является сотрудником</label>
            </div>

        	<div class="modal-footer">
        		<button type="submit" name="edit-submit" class="btn btn-primary">Обновить</button>
        	</div>
        </form>	
      </div>
    </div>
  </div>
</div>


<!-- Удаление -->
<div class="modal fade" id="deleteModal<?=$value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Удалить запись № <?=$value['id'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <form action="?id=<?=$value['id'] ?>" method="post">
        	<button type="submit" name="delete_submit" class="btn btn-danger">Удалить</button>
    	</form>
      </div>
    </div>
  </div>
</div>
