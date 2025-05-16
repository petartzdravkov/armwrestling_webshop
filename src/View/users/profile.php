<?php

//redirect to shop if session active
// if($session_active){
//     header("Location: " . $_SERVER['PHP_SELF'] . "?page=shop");
//     die();
// }


?>

<?php include_once '../src/View/layouts/header.php';?>

<div class="m-auto bg-secondary-subtle py-5 px-3 rounded">
  <div class="login_form">
    <h2 class="text-center"> Profile </h2>
    <form method="POST" action="index.php?target=user&action=profile">

      <p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
      <!-- <input type="email" name="email" placeholder="Email" class="m-2" value="<?=$email;?>" autofocus><br>
	   <input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br> -->

      <div class="text-center">
	<input type="submit" class="btn btn-secondary" name="logout_btn" value="Logout" class="m-2">
      </div>
    </form>
  </div>
</div>

<?php include_once '../src/View/layouts/footer.php';?>