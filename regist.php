<?php

include('auth.php');


function validate($post, &$data, &$errors) {
    // username, password, fullname are not empty
    // ...
    if (!isset($post['username'])) {
        $errors['username'] = 'A név megadása kötelező';
      }
      else if (trim($post['username']) === '') {
        $errors['username'] = 'A név megadása kötelező';
      }
      else {
        $data['username'] = $post['username'];
      }


      if (!isset($post['address'])) {
        $errors['address'] = 'A cím megadása kötelező';
      }
      else if (trim($post['address']) === '') {
        $errors['address'] = 'A cím megadása kötelező';
      }
      else {
        $data['address'] = $post['address'];
      }


      $re = '/^[0-9]{9}$/m';
      $str = $post['taj'];
      if (!isset($post['taj'])) {
        $errors['taj'] = 'A tajszám megadása kötelező';
      }
      else if (trim($post['taj']) === '') {
        $errors['taj'] = 'A tajszám megadása kötelező';
      }
      else if (preg_match($re, $str) === 1){
        $data['taj'] = $post['taj'];
      }else{
          
          $errors['taj'] = 'A tajszám nem megfelelő';
      }


  
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
      else 

      if($post['password2']===$post['password']){
          $data['password'] = $post['password'];          
      }else{
        $errors['password2'] = 'A két jelszó nem egyezik meg';
      }


        




    $data = $post;
  
    return count($errors) === 0;
  }

//  print_r($_POST);
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$errors = [];
$data = [];
if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {
    if ($auth->user_exists($data['email'])) {
      $errors['global'] = "User already exists";
    } else {
      $auth->register($data);
      redirect('login.php');
    } 
  }
}





?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1> Nemzeti Koronavírus Depó</h1> 
  <a class='asbutton' href="index.php">Vissza a főoldalra</a>
   <a class='asbutton' href="login.php">Bejelentkezés</a> 
   <h2>Regisztráció</h2>
   

    <?php if (isset($errors['global'])) : ?>
    <p><span class="error"><?= $errors['global'] ?></span></p>
    <?php endif; ?> <br>

    <form action="" novalidate method="post">
      <p>Kérem a valóságnak megfelelő adatokat adjon meg. Az adatok ellenőrzésre kerülnek a személyes találkozás alkalmával, és a védőoltás beadása megtagadható.  </p>
  <div>
    <label for="username">Teljes név: </label>
    <input type="text" name="username" id="username" value="<?= $_POST['username'] ?? "" ?>"><br>
    <?php if (isset($errors['username'])) : ?>
      <span class="error"><?= $errors['username'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="password">Jelszó: </label>
    <input type="password" name="password" id="password"><br>
    <?php if (isset($errors['password'])) : ?>
      <span class="error"><?= $errors['password'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="password2">Jelszó újra: </label>
    <input type="password" name="password2" id="password2"><br>
    <?php if (isset($errors['password2'])) : ?>
      <span class="error"><?= $errors['password2'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="taj">Taj szám: </label>
    <input type="text" name="taj" id="taj" value="<?= $_POST['taj'] ?? "" ?>"><br>
    <?php if (isset($errors['taj'])) : ?>
      <span class="error"><?= $errors['taj'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="address">Lakcím: </label>
    <input type="text" name="address" id="address" value="<?= $_POST['address'] ?? "" ?>"><br>
    <?php if (isset($errors['address'])) : ?>
      <span class="error"><?= $errors['address'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="email">Email cím: </label>
    <input type="text" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>"><br>
    <?php if (isset($errors['email'])) : ?>
      <span class="error"><?= $errors['email'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <button type="submit">Regisztráció</button>
  </div>
  
</form>



</body>
<footer><img style="height:198px" src="jobban.jpg" alt=""></footer>
</html>