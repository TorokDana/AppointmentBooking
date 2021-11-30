<?php

include('auth.php');

function validate($post, &$data, &$errors) {
    if (!isset($post['email'])) {
        $errors['email'] = 'Az emailcím megadása kötelező';
      }
      else if (trim($post['email']) === '') {
        $errors['email'] = 'Az emailcím megadása kötelező';
      }
      else if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Az emailcím nem megfelelő';
      }else{
          $data['email'] = $post['email'];
          
      }

      if (!isset($post['password'])) {
        $errors['password'] = 'Az jelszó megadása kötelező';
      }
      else if (trim($post['password']) === '') {
        $errors['password'] = 'Az jelszó megadása kötelező';
      }
      else {
            $data['password'] = $post['password'];  
      }
        
  
    return count($errors) === 0;
  }
  
  session_start();
  $user_storage = new UserStorage();
  $auth = new Auth($user_storage);

if ($_GET){
   
         $auth->logout();
         unset($_GET["ki"]);
   
}


 
  $data = [];
  $errors = [];
  if ($_POST) {
    if (validate($_POST, $data, $errors)) {
      $auth_user = $auth->authenticate($data['email'], $data['password']);
      if (!$auth_user) {
        $errors['global'] = "Nem összeillő email-jelszó páros";
      } else {
        $auth->login($auth_user);
        if (isset($_GET['date']) && $auth->authenticated_user()['appointment']==""){
          $url=$_GET['date'];
          print_r($url);
          unset($_GET['date']);
          redirect("confirmation.php?date=$url");
        }
        redirect('index.php');
      }
    }
  }


 
 

  
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1> Nemzeti Koronavírus Depó</h1>
    <a class='asbutton' href="index.php">Vissza a főoldalra</a>
    <a class='asbutton' href="regist.php">Regisztráció</a>
    <h2>Bejelentkezés</h2>
 
    <?php if (count($errors)>0):?>
      <?php foreach  ($errors as $mess):?>
        <span style="color:red"><?= $mess ?></span><br>
        <?php endforeach; ?> 
      <?php endif; ?> <br>

    <form action="" novalidate method="post"> 
  <div>
    <label for="email">Email cím: </label>
    <input type="text" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>">
   
  </div> 
  <div>
    <label for="password">Jelszó: </label>
    <input type="password" name="password" id="password">
 
  </div>
  <div>
    <button type="submit">Bejelentkezés</button>
  </div>
</form>




</body>
<footer><img style="height:198px" src="jobban.jpg" alt=""></footer>
</html>