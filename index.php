
 <?php 
    include('auth.php');
    session_start();
    $user_storage = new UserStorage();
    $auth = new Auth($user_storage);

    $date_storage = new datesStorage();
    $dates = $date_storage->FindAll();
    
    if ($auth->is_authenticated() &&(!$auth->authenticated_user()['appointment']=="")  ){
        $date = $date_storage->findById($auth->authenticated_user()['appointment']);
    }

    
    if(isset($_POST['hidden'])){
        $updateuser =  $auth->authenticated_user();
        $updateuser['appointment']="";
        $user_storage->update($updateuser['id'],$updateuser);                
        $_SESSION["user"] = $user_storage->findById($updateuser['id']);

        $date = $date_storage->findById($auth->authenticated_user()['appointment']);
       
        if (($key = array_search( $updateuser['email'], $date['users'])) !== false) {
            unset($date['users'][$key]);
        }

        $date_storage->update($date['id'],$date);
        redirect('index.php');

       

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
 
    <?php if ($auth->is_authenticated()):?>
        <a class='asbutton' href="login.php?ki='igen">Kijelentkezés</a>
        <?php if ($auth->authorize(["admin"])):?>
        <a class='asbutton' href="new.php">Új időpont felvétele</a>
        <?php endif ?>
        <p style="float:right;margin:20px">Bejelentkezve: <?php  print_r($auth->authenticated_user()['username']);   ?> </p>
       
        <?php else :?>
    <a class='asbutton' href="login.php">Bejelentkezés</a> <a class='asbutton' href="regist.php">Regisztráció</a>
    <?php endif ?>
   
    
    <h2>Ön az alábbi oldalon a koronavírus elleni védőoltásra regisztrálhat</h2>
    <img style="height:250px; float:right;" src="doctor.png" alt="">
    <h3>10-ből 2 orvos ezt ajánlja.</h3>
    
    <p style="text-align:justify;">
A SARS-COV-19 vírus, illetve a járványhelyzet miatti hatások sok ember életét megkeserítik. A tudósok között konszenzus van, a pandémia egyetlen módon szorítható vissza: vakcinával. Mivel azonban globális járványról van szó, az első hónapokban rendkívül nehéz lesz oltóanyaghoz jutni, a kontingensek mérete és az oltás lebonyolítása is rengeteg kérdést vet fel. Erre a problémára kínálunk megoldást a COVID-19 oltóanyag előfoglalási programunkkal - foglaljon nálunk COVID-19 vakcinát, hogy az elsők között kapja meg az oltást! Oltóközpontunk piacvezető, 13 éves fennállásunk alatt több mint 70.000 pácienst oltottunk be, több mint 200.000 vakcinával. Az előfoglalás koncepciójával értelemszerűen abból a feltételezésből indulunk ki, hogy (lévén szinte az összes piacvezető gyógyszergyár ezen dolgozik) belátható időn belül elérhető lesz egy, sőt akár több, Magyarországon is engedélyezett oltóanyag. Az engedélyezett vakcinát (vakcinákat) mi piaci forrásból a gyártótól, vagy a gyógyszerforgalmazóktól szerezzük majd be.</p>


    <?php if ($auth->is_authenticated() &&(!$auth->authenticated_user()['appointment']=="")) : ?>
        
        <div style="background-color: rgb(195, 235, 255); padding:20px; border-radius:4px;">
        <h3>Önnek van foglalása!</h3>
        <small>Ameddig önnek érvényes időpontja van nem regisztrálhat más időpontra.</small>
        <p>A foglalás adatai:</p>
        <li > <strong  >Dátum:</strong> <span style="font-size:100%;color:black; "><?= $date['date']?> </span> </li>  
        <li > <strong  >Időpont:</strong> <span style="font-size:100%;color:black; "><?= $date['time']?> </span> </li> 
        <form style="background-color: rgb(195, 235, 255);"action="" novalidate method="post"> 
        <input id='hidden' type='hidden' value='hidden' name='hidden'>
        <button type="submit">Jelentkezés lemondása</button>
        </form>
        <a href="lemondas"></a>  
        </div>
    <?php endif ?>



   <h2>Időpontok:</h2>
    
    <div id="calendar">
    
    </div>

    <div class='center'><a class='asbutton3' id="pre" href="">Előző hónap</a><a class='asbutton3' id="next" href="">Következő hónap</a></div>
    
    

    <script>


let monthcounter = 0;

const pre=document.getElementById("pre"); 
const next=document.getElementById("next"); 
const calendar = document.getElementById("calendar"); 
pre.addEventListener('click', precalendar)


async function precalendar(e){
    
    e.preventDefault()
    monthcounter -= 1
    const response = await fetch(`calendar.php?month=${monthcounter}`)
    const text = await response.text()
    calendar.innerHTML = text
   
    

}

next.addEventListener('click', nextcalendar)

async function nextcalendar(e){
      
    e.preventDefault()
    monthcounter += 1
    const response = await fetch(`calendar.php?month=${monthcounter}`)
    const text = await response.text()
    calendar.innerHTML = text
   
    


}


async function getCalendar() {
  const calendar = document.getElementById("calendar"); 
  const response = await fetch(`calendar.php`)
  const text = await response.text()
  calendar.innerHTML = text
  
}


getCalendar()




    </script>

</body>
<footer><img style="height:198px" src="jobban.jpg" alt=""></footer>
</html>