<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="js/Validation.js"></script>
    <script src="js/XMLReader.js"></script>
    <script src="js/admin.js"></script>
    <?php
    include('htaccess/validateFunctions.php');
    ?>
</head>
<body>
<header>Round Robin Tournament</header>
<nav>
    <ul>
        <li><a href="index.php">Fixtures</a>
        <li><a href="standings.php">Standings</a>
        <li><a class = 'active'>Admin</a>
        <li><a href="newVenue.php">New Tournament</a>
    </ul>
</nav>

<div id="main">
    <h1>Round Robin - Administration</h1>
<p>
    Use the form below to enter the schedule and results for the round robin tournament.
    For games that have not been played, simply uncheck the 'Played' box, and the scores will be ignored.
</p>
<?php

/* bool formOk is initialised and set to false */
$formOK = false;

/* array errorMessage is initialised */
$errorMessage = array("<p>There were some errors when processing your form:</p><ul>");

/* makes sure that the form has been submitted before validating it */
if (isset($_POST['submit'])) {
    $formOK = true;
    
    $teamCount = $_POST['teamCount'];
    
    /* formOk is currently set to true. 
     * teamCount returns the number of teams when the form is submitted.
     * 
     * If there is more than one team submitted loop through all the teams and check them against
     * the validateFunctions. If any of the functions return false or there is only one team 
     * then formOK is set to false and an error message is pushed onto the error array.
     */
    if ($teamCount > 0) {
        for ($i = 0; $i < $teamCount+1; $i++) {
            if (!isEmpty($_POST['team'.$i])) {
                $formOK = false;
                array_push($errorMessage, "<li>Teams entered must not be empty");
            }
            if (!checkTeams($i)){
                $formOK = false;
                array_push($errorMessage, "<li>Two teams cannot have the same name");
            }
        }
    }
    if ($teamCount == 0){
        $formOK = false;
        array_push($errorMessage, "<li>Please enter more than one team");
    }
    
}
array_push($errorMessage, "</ul>");

/* if the form doesn't validate then print out the error messages as a list */
if (!$formOK){
    $arrlength = count($errorMessage);
    /* makes sure that there is an error before printing the array*/
    if ($arrlength > 2) {
        for ($x = 0; $x < $arrlength; $x++) {
            echo $errorMessage[$x];
        }
    }
}
/* If the form validates then update the xml files. */
if ($formOK) {
    $tournament = simplexml_load_file('tournament.xml');
    $matches = $tournament->xpath('match');
    
    /* deletes the contents of the tournament xml file */
    foreach ($matches as $item) {
        unset($item[0]);
    }

    $count = 0;
    $teams = array();
    
    /* Pushes all the teams into an array*/
    for ($i=0; $_POST['team'.$i] != null; $i++){
        array_push($teams, $_POST['team'.$i]);
    }
    
    $teamLength = count($teams);
    
    /* Nested for loop that creates the round robin tournament and updates the xml. 
     * Each team in a round robin must play all other teams once.
     */
    for ($x = 0; $x < $teamLength-1; $x++){
        for ($y = $x+1; $y < $teamLength; $y++){
            $match = $tournament->addChild('match');
            $team1 = $match->addChild('team', $_POST['team' . $x]);
            $team2 = $match->addChild('team', $_POST['team' . $y]);
        }
    }

    $tournament->saveXML('tournament.xml');

}
?>

<form id="" action="validateForm.php" method="post" novalidate>
    <?php 
    /* Creates the admin form from the xml using PHP
     ************************************************/
 
    $tournament = simplexml_load_file('tournament.xml');
    $venues = simplexml_load_file('venues.xml');
    $count = 0;
    
    /* Loops through the tournament xml and assigns each element to a variable */
    foreach ($tournament->match as $item){
        $day = $item->date->day;
        $month = $item->date->month;
        $year = $item->date->year;
        $venue = $item->venue;
        $team1 = $item->team[0];
        $team2 = $item->team[1];
        $score1 = $team1->attributes();
        $score2 = $team2->attributes();
    echo "<fieldset><legend>$team1 vs $team2</legend><p><label for='day$count'>Date:</label><select id = 'day$count' name='day$count'>";
            /* For loop to create the date select options.
             * If there is a day present in the xml then that option is selected */ 
            for ($i=1; $i<32; $i++){
                 if ($day == $i) {
                     echo "<option value='$i' selected>$i</option>";
                 } else {
                     echo "<option value='$i'>$i</option>";
                 }
             }
    echo "</select>
    <label class = 'narrow' for='month$count'></label>
    <select  id ='month$count' name='month$count'>";
        $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
        /* For loop to create the month select options.
         * If there is a month present in the xml then that option is selected */
        for ($i=0; $i<12; $i++){
            if ($i == $month) {
                echo "<option value='$i' selected>$months[$i]</option>";
            } else {
                echo "<option value='$i'>$months[$i]</option>";
            }
        }
    echo "</select>

    <label class='narrow' for='year$count'></label>
    <select name='year$count'>";
        /* For loop to create the date select options.
         * If there is a year present in the xml then that option is selected */
        for ($i=2014; $i<2022; $i++){
            if ($year == $i) {
                echo "<option value='$i' selected>$i</option>";
            } else {
                echo "<option value='$i'>$i</option>";
            }
        }
    echo "</select>
    <span class='error' id='dateError$count'></span></p>
    <p>
    <label for='venue$count'>Venue</label>
    <select id='venue$count' name='venue$count'>";
        /* Foreach loop to load in the venues as select options */
        foreach ($venues->venue as $venue) {
            echo "<option value='$venue'>$venue</option>";
        }
echo "</select>
<span class='error' id='venueError$count'></span>
</p>
<p>
    <label for='played$count'>Played:</label>
    <input type='checkbox' name='played$count' id='played$count'";
            /* If there is a score then the game has been played and the checkbox is checked*/
            if ($score1 != null){
                echo " checked";
            }
        echo ">
</p>
<p>
<label for='team1_$count'>$team1</label>
<input type='hidden' name='team1name_$count' value='$team1'>
<input type='number' name='team1_$count' id='team1_$count' value='$score1'>
<label for='team2_$count'>$team2</label>
<input type='hidden' name='team2name_$count' value='$team2'>
<input type='number' name='team2_$count' id='team2_$count' value='$score2'>
<span class='error' id='scoreError$count'></span>
</p>
    
    </fieldset>";
    $count++;
        
    }
    echo  "<input type='submit' value='Update Schedule' name='submit'>";
?>
   
</form>

</div>

</body>
</html>