    <div class="alert-success"><?php echo $task_created ?></div>
    <div class="alert-success" id="editMessage"><?php echo $task_edited ?></div>


<table id="example" class="display" style="width:100%">
        <thead>
           
        </thead>
        <tfoot>
           
        </tfoot>
    </table>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/edit-task" method="post">
    <div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" value="<?php echo $model->name?>" class='form-control <?php echo $model->hasError("name") ? " is-invalid" : '' ?> ' id="name" placeholder="Enter name">
    <div class="invalid-feedback"><?php echo $model->hasError("name") ?></div>
  </div>
  <div class="form-group">
    <label for="email">Email </label>
    <input type="email" name="email" value="<?php echo $model->email?>" class="form-control <?php echo $model->hasError("email") ? " is-invalid" : '' ?> " id="email" aria-describedby="emailHelp" placeholder="Enter email">
  <div class="invalid-feedback"><?php echo $model->hasError("email") ?></div>
  </div>
<div class="form-group">
    <label for="task">Task</label>
    <input type="text" name="task" value="<?php echo $model->task?>" class="form-control <?php echo $model->hasError("task") ? " is-invalid" : '' ?> " id="task" placeholder="Enter task">
    <div class="invalid-feedback"><?php echo $model->hasError("task") ?></div>

  </div>

          <input type="hidden" id="taskid" value="" name="id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
        </form>

    </div>
  </div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
             var columns = <?php echo $columns ?>;
            $('#example').DataTable( {
                "processing": true,
                "serverSide": true,
                "bLengthChange": false,
                "searching": false,
                'serverMethod': 'post',
                "pageLength": 3,
                 "bInfo" : false,
                "ajax": "/get-tasks",
                'columns': columns,
            } );


        $('body').on('click', '.is-done', function () {
            var id = $(this).data('id')
            var checked = $(this).prop('checked')
            $.ajax({
                type: "POST",
                url: "/change-status",
                data: {id: id,checked:checked},
                dataType:'JSON', 
                success: function(response){
                    console.log(response);
                    console.log(response.message);
                    $('#editMessage').html(response.message)
                    // put on console what server sent back...
                }
            })
        })
        $('body').on('click', '.edit-button', function () {
            var name =$(this).data('name')
            var email =$(this).data('email')
            var task =$(this).data('task')
            var id =$(this).data('id')
            $('#name').val(name)
            $('#taskid').val(id)
            $('#email').val(email)
            $('#task').val(task)
        })

            $('#editModal').on('shown.bs.modal', function () {

            })
        } );



    </script>