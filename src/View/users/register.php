<?php

//redirect to home if session active
if(isset($_SESSION['email'])){
    header("Location: index.php");
    die();
}else{
    header("Location: index.php?target=user&action=login");
    die();
}

?>

<?php include_once '../src/View/layouts/header.php';?>

<div class="m-auto bg-secondary-subtle py-5 px-3 rounded">
  <div class="register_form">
    <h2 class="text-center"> Register </h2>
    <form method="POST" action="index.php?target=user&action=register">

      <p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
      <input type="email" name="email" placeholder="Email" class="m-2" value="<?=$email;?>" autofocus><br>
      <input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br>

      <div class="text-center">
	<input type="submit" name="register_btn" value="Register" class="m-2">
      </div>
    </form>
  </div>
</div>

<?php include_once '../src/View/layouts/footer.php';?>