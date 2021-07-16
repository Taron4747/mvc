<!-- 

<form action="/create-task" method="post">


	<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" class='form-control ' id="name" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="email">Email </label>
    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
<div class="form-group">
    <label for="task">Task</label>
    <input type="text" name="task" class="form-control" id="task" placeholder="Password">
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>

 -->



<form action="/create-task" method="post">


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

  <button type="submit" class="btn btn-primary">Submit</button>
</form>

