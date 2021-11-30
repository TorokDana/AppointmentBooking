<?php

include('auth.php');
session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);

$date_storage = new datesStorage();
$dates = $date_storage->FindAll();








    $dateArray = [];


     $dateComponents = getdate();

     if (isset($_GET['month'])){
        $gotMonth = $_GET['month'];
        
       if($gotMonth >0){
           for($i=0; $i<$gotMonth ; $i++){
             if($dateComponents['mon']== 12 ){
            $dateComponents['mon']= 1;
            $dateComponents['year'] +=1;
           }else{
            $dateComponents['mon'] +=1;
           }  
           }
           


       }else if ($gotMonth <0) {
        for($i=0; $i>$gotMonth ; $i--){
            if($dateComponents['mon']== 1 ){
           $dateComponents['mon']= 12;
           $dateComponents['year'] -=1;
          }else{
           $dateComponents['mon'] -=1;
          }  
          }


       }
     
    
        
       
    }


     $month = $dateComponents['mon']; 			     
     $year = $dateComponents['year'];

     echo build_calendar($month,$year,$dateArray);





function build_calendar($month,$year,$dateArray) {
    $date_storage = new datesStorage();
     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('Hétfő','Kedd','Szerda','Csütörtök','Péntek','Szombat','Vasárnap');

     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);

     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);

     // What is the name of the month in question?
     $monthName = $dateComponents['month'];

     // What is the index value (0-6) of the first day of the
     // month in question.
     //$dayOfWeek = $dateComponents['wday'];

     $dayOfWeek = ($dateComponents['wday'] + 6) % 7;

     // Create the table tag opener and day headers

     $calendar = "<table class='calendar'>";
     $calendar .= "<caption>$monthName $year</caption>";
     $calendar .= "<tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<th class='header'>$day</th>";
     } 

     // Create the rest of the calendar

     // Initiate the day counter, starting with the 1st.

     $currentDay = 1;

     $calendar .= "</tr><tr>";

     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.

     if ($dayOfWeek > 0) { 
          $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>"; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {

          // Seventh column (Saturday) reached. Start a new row.

          if ($dayOfWeek == 7) {

               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";

          $calendar .= "<td class='day' rel='$date'>";
          $calendar .= "$currentDay";

          //to-do
          $user_storage = new UserStorage();
          $auth = new Auth($user_storage);
          $dates = $date_storage->FindAll();
          foreach ($dates as $appointment) {
            $d=strtotime($appointment['date']);
            if ($year==date("Y",$d) && $month==date("m",$d) && $currentDayRel == date("d",$d)){
               // $calendar .=date("Y-m-d",$d);









               if ($auth->authorize(["admin"]) ){
                    if(count($appointment['users'])<$appointment['capacity']){
                        $calendar .='<li style="color:green">';
                    }else{
                        $calendar .='<li style="color:red">';
                    }

                    $calendar .= $appointment['time'];
                    $calendar .= " ";
                    $calendar .= count($appointment['users']);
                    $calendar .= "/";
                    $calendar .= $appointment['capacity'];
                    $calendar .= " ";
                    $calendar .= '<a class="asbutton2" href="detail.php?date=';
                    $calendar .= $appointment['id'];
                    $calendar .= '">Részletek</a></li>';


               }else{

                if(count($appointment['users'])<$appointment['capacity']){
                    $calendar .='<li style="color:green">';

                    if(!$auth->is_authenticated() ){

                    $calendar .= $appointment['time'];
                    $calendar .= " ";
                    $calendar .= count($appointment['users']);
                    $calendar .= "/";
                    $calendar .= $appointment['capacity'];
                    $calendar .= " ";
                    $calendar .= '<a class="asbutton2" href="login.php?date=';
                    $calendar .= $appointment['id'];
                    $calendar .= '">Jelentkezés</a></li>';

                    }else{

                        if(! $auth->authenticated_user()['appointment']==""){
                            $calendar .= $appointment['time'];
                            $calendar .= " ";
                            $calendar .= count($appointment['users']);
                            $calendar .= "/";
                            $calendar .= $appointment['capacity'];
                            $calendar .= '</li>';
                        }else{
                            $calendar .= $appointment['time'];
                            $calendar .= " ";
                            $calendar .= count($appointment['users']);
                            $calendar .= "/";
                            $calendar .= $appointment['capacity'];
                            $calendar .= " ";
                            $calendar .= '<a class="asbutton2" href="confirmation.php?date=';
                            $calendar .= $appointment['id'];
                            $calendar .= '">Jelentkezés</a></li>';

                        }



                    }



                }else{
                    $calendar .='<li style="color:red">';

                    $calendar .= $appointment['time'];
                    $calendar .= " ";
                    $calendar .= count($appointment['users']);
                    $calendar .= "/";
                    $calendar .= $appointment['capacity'];
                    $calendar .= '</li>';


                }



               }

                












            }

                
            
            
            
          }

          $calendar .= "</td>";

          // Increment counters
 
          $currentDay++;
          $dayOfWeek++;

     }
     
     

     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != 7) { 
     
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>"; 

     }
     
     $calendar .= "</tr>";

     $calendar .= "</table>";

     return $calendar;

}

?> 

