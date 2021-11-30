<?php 
    include('auth.php');
    session_start();
    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);

    $date_storage = new datesStorage();
    $date = $date_storage->findById($_GET['date']);
  
    ?>



    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Részletek</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>


    <h1> Nemzeti Koronavírus Depó</h1>
    
 
 <?php if ($auth->is_authenticated()):?>
     <a class='asbutton' href="login.php?ki='igen">Kijelentkezés</a>
     <?php if ($auth->authorize(["admin"])):?>
     <a class='asbutton' href="new.php">Új időpont felvétele</a>
     <?php endif ?>
     <a class='asbutton' href="index.php">Vissza a főoldalra</a>
     <p style="float:right;margin:20px">Bejelentkezve: <?php  print_r($auth->authenticated_user()['username']);   ?> </p>   

 <?php endif ?>

<h2>Időpont részletei</h2>
<strong  >Dátum:</strong> <span style="font-size:100%;color:black; "><?= $date['date']?> </span>  <br>
<strong  >Időpont:</strong> <span style="font-size:100%;color:black; "><?= $date['time']?> </span>   <br>
<strong  >Fő/kapacitás:</strong> <?=count($date['users']) ?>/<?=$date['capacity'] ?> <br>
<?php if (count($date["users"]) == $date['capacity']):?>
    <strong style="color:red;">Az időpont betelt</strong>
    <?php endif ?> 


<?php if (count($date["users"])>0):?>
<h3>Jelentkezett felhasználók:</h3>
 <?php foreach ($date["users"] as $user) : ?>
    <?php $thisuser = $user_storage->findOne(['email'=>$user]) ?>
    <div class='detail'>
    <strong><?=$thisuser["username"]?></strong>
    <li>TAJ szám: <?=$thisuser['taj']?></li>
    <li>Email cím:<?=$thisuser['email']?></li>
    
    
    
    </div>


        <?php endforeach ?>  
        <?php else : ?>
        <h3>Az időpontra eddig nem jelentkezett senki</h3>

        <?php endif ?>
    </body>

    <footer><img style="height:198px" src="jobban.jpg" alt=""></footer>
    </html>