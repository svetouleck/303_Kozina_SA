<!--Редактирование элемента графика-->

<div class="modal fade" id="editModalSh<?=$value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Редактировать запись </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="?id=<?=$value['id']?>&master_id=<?=$master['id']?>" method="post">

            <div class="form-group ">
                <select name="day" class="form-control" required>
                  <option value="" disabled selected> День недели </option>
                  <?php foreach ($week_days as $day) {
                    
                      $selected = ($day['day']==$value['week_day']['day']) ? 'selected' : ''; 
                      ?>
                  <option value='<?=$day['id']?>' <?=$selected ?>> <?=$day['day']?> </option>
                  <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-check-label">Начало работы</label>
                <input class="form-control" type="time" name='start_time' value="<?=$value['start_time']?>">
            </div>

            <div class="form-group">
                <label class="form-check-label">Окончание работы</label>
                <input class="form-control" type="time" name='end_time' value="<?=$value['end_time']?>">
            </div>

        	<div class="modal-footer">
        		<button type="submit" name="edit-submit-sh" class="btn btn-primary">Обновить</button>
        	</div>
        </form>	
      </div>
    </div>
  </div>
</div>


<!-- Удаление элемента графика -->
<div class="modal fade" id="deleteModalSh<?=$value['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Удалить запись </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <form action="?id=<?=$value['id']?>&master_id=<?=$master['id']?>" method="post">
        <button type="submit" name="delete_submit_sh" class="btn btn-danger">Удалить</button>
    	</form>
      </div>
    </div>
  </div>
</div>
