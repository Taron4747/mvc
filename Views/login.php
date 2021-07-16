

<form action="/login" method="post">


	<div class="form-group col-md-5" >
    <label for="name">Login</label>
    <input type="text" name="name" class="form-control <?php echo $error ? " is-invalid" : '' ?>" id="name" placeholder="Login">

  </div>

<div class="form-group col-md-5" >
    <label for="task">Password</label>
    <input type="password" name="password" class="form-control <?php echo $error ? " is-invalid" : '' ?>" id="task" placeholder="Password">

    <div class="invalid-feedback"><?php echo $error ?></div>

  </div>

<div class="form-group col-md-5" >

  <button type="submit" class="btn btn-primary">Login</button>
</div>
</form>

