<?php 
    include('auth.php');
    session_start();
    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);

    $date_storage = new datesStorage();
    $date = $date_storage->findById($_GET['date']);


   

  
  $errors = [];

  if (count($_POST) > 0) {        
    if (isset($_POST['agree'])) {  

        if(count($date['users'])>=$date['capacity']){
            $errors['full']="Sajnálattal közöljük, de a választott időpont időközben betelt. Kérjük fáradjon vissza a főoldalra és válasszon a többi opció közül.";
        }else{
           $updateuser =  $auth->authenticated_user();
        $updateuser['appointment']=$date['id'];
        $user_storage->update($updateuser['id'],$updateuser);
        //print_r($user_storage->findById($updateuser['id']));        
        $_SESSION["user"] = $user_storage->findById($updateuser['id']);
        $date['users'][]= $updateuser['email'];
        $date_storage->update($date['id'],$date);  
        redirect('succes.php');   
        }   
               
      }else{
            $errors['agree']= "A jelentkezés befejezéséhez el kell fogadnia a feltételeket"; 
    }       
    }
  
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<h1> Nemzeti Koronavírus Depó</h1>
<a class='asbutton' href="index.php">Vissza a főoldalra</a>
<a class='asbutton' href="login.php?ki='igen">Kijelentkezés</a>
<h2>Jelentkezés megerősítése</h2>

<h3>Az jelentkezés adatai:</h3>
<div style="max-width:250px">
    <li > <strong  >Dátum:</strong> <span style="font-size:100%;color:black; "><?= $date['date']?> </span> </li>  
    <li > <strong  >Időpont:</strong> <span style="font-size:100%;color:black; "><?= $date['time']?> </span> </li>  
    <li ><strong >Név:</strong><span style="font-size:100%;color:black; "><?=$_SESSION['user']['username']  ?> </span></li>
    <li ><strong >Lakcím:</strong><span style="font-size:100%;color:black; "><?=$_SESSION['user']['address'] ?> </span></li>
    <li ><strong >TAJ szám:</strong><span style="font-size:100%;color:black; "> <?=$_SESSION['user']['taj'] ?></span></li>   
</div>

<?php if (isset($errors['full']) ): ?>
      <span  style="float:none; color:red; font-size:110%;"  ><?= $errors['full'] ?></span>
    <?php endif; ?> <br>
<form action="" novalidate method="post">
<?php if (isset($errors['agree']) ): ?>
      <span  style="float:none;" class="error" ><?= $errors['agree'] ?></span>
    <?php endif; ?> <br>
<input style="float:none;" type="checkbox" name="agree" value="agree" ></input>
<input id='testNameHidden' type='hidden' value='No' name='testName'>
<label for="agree">Az időpontra jelentkezéssel elfogadom a feltételeket. </label><br><br>
<button type="submit">Jelentkezés megerősítése</button>


</form>

    
</body>
<footer><img style="height:198px" src="jobban.jpg" alt=""></footer>
</html>