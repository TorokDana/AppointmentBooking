<?php 
    include('auth.php');
    session_start();
    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);

    $date_storage = new datesStorage();
    $dates = $date_storage->FindAll();

    function validate($post, &$data, &$errors) {
        // username, password, fullname are not empty
        // ...
        if (!isset($post['date'])) {
            $errors['date'] = 'A időpont megadása kötelező';
          }
          else if (trim($post['date']) === '') {
            $errors['date'] = 'A időpont megadása kötelező';
          }
          else {
            $data['date'] = $post['date'];
          }

          if (!isset($post['time'])) {
            $errors['time'] = 'A időpont megadása kötelező';
          }
          else if (trim($post['time']) === '') {
            $errors['time'] = 'A időpont megadása kötelező';
          }
          else {
            $data['time'] = $post['time'];
          }


          if (!isset($post['capacity'])) {
            $errors['capacity'] = 'A férőhelyek megadása kötelező';
          }
          else if (trim($post['capacity']) === '') {
            $errors['capacity'] = 'A férőhelyek megadása kötelező';
          }
          else if(intval(trim($post['capacity']))>0) {
            $data['capacity'] = $post['capacity'];
          }else{
            $errors['capacity'] = 'Legalább 1 férőhely kötelező';
          }

          

          $data = $post;
          $data["users"]=(array) null;
  
          return count($errors) === 0;
        }

    $errors = [];
    $data = [];

    if (count($_POST) > 0) {
        if (validate($_POST, $data, $errors)) {
          $date_storage->add($data);
           redirect('index.php');
          } 
         
        
      }
    
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új időpont</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>


    <h1> Nemzeti Koronavírus Depó</h1>
    <a class='asbutton' href="login.php?ki='igen">Kijelentkezés</a>
    <a class='asbutton' href="index.php">Vissza a főoldalra</a>
    <p style="float:right;margin:20px">Bejelentkezve: <?php  print_r($auth->authenticated_user()['username']);   ?> </p>
    
    <h2>Új időpont felvétele</h2>

    <form action=""  novalidate method="post">
    <div>
    <label for="date">Dátum: </label>
    <input type="date" name="date" id="date" value="<?= $_POST['date'] ?? "" ?>"><br>
    <?php if (isset($errors['date'])) : ?>
      <span class="error"><?= $errors['date'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="time">Időpont: </label>
    <input  type="time" name="time" id="time" value="<?= $_POST['time'] ?? "" ?>"><br>
    <?php if (isset($errors['time'])) : ?>
      <span class="error" ><?= $errors['time'] ?></span>
    <?php endif; ?> <br>
  </div>

  <div>
    <label for="capacity">Férőhelyek száma: </label>
    <input  style="width:66px" type="number" name="capacity" id="capacity" value="<?= $_POST['capacity'] ?? "" ?>"><br>
    <?php if (isset($errors['capacity'])) : ?>
      <span class="error" ><?= $errors['capacity'] ?></span>
    <?php endif; ?> <br>
  </div>
    
   <button type="submit">Jóváhagyás</button>
    
    </form>

    


</body>
<footer><img style="height:198px" src="jobban.jpg" alt=""></footer>