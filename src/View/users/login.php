<?php

//redirect to home if session active
if(isset($_SESSION['email'])){
    header("Location: index.php");
    die();
}


//successful registration message
if(isset($_GET['regsuccess'])){
    $success_msg = "Successful registration!";
}





?>






<?php include_once '../src/View/layouts/header.php';?>

<div class="m-auto bg-secondary-subtle py-5 px-3 rounded">
  <div class="login_form">
    <h2 class="text-center"> Login </h2>
    <form method="POST" action="index.php?target=user&action=login">

      <p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
      <input type="email" name="email" placeholder="Email" class="m-2" value="<?=$email;?>" autofocus><br>
      <input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br>

      <div class="text-center">
	<input type="submit" name="login_btn" value="Login" class="m-2">
      </div>
    </form>
  </div>
</div>

<?php include_once '../src/View/layouts/footer.php';?>