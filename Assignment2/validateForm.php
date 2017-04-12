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

<nav>
    <ul>
        <li><a href="index.php">Fixtures</a>
        <li><a href="standings.php">Standings</a>
        <li><a class="active">Admin</a>
        <li><a href="newVenue.php">New Tournament</a>
    </ul>
</nav>
<div id="main">
<h1>Round Robin - Administration</h1>

<?php
/* bool formOk is initialised and set to false */
 $formOK = false;
/* array errorMessage is initialised */
 $errorMessage = array("<p>There were some errors when processing your form:</p><ul>");

/* makes sure that the form has been submitted before validating it */
if (isset($_POST['submit'])) {
    $formOK = true;

    $count = 0;

    /* formOk is currently set to true. 
     * 
     * Loop through the matches by checking if there is still a day$count being subbmited 
     * and check the teams against the validateFunctions. 
     * If any of the functions return false then formOK is set to false 
     * and an error message is pushed onto the error array.
     */
    while ($_POST['day' . $count] != null){
        if (!validateVenue($count)){
            $formOK = false;
            array_push($errorMessage,"<li>Please enter a valid venue: venue$count");
        }
        
        if (isset($_POST['played'.$count])) {
            if (!validateScore($count)) {
                $formOK = false;
                array_push($errorMessage, "<li>Please enter a valid score: team1_$count vs team2_$count");
            }
        } else {
            if (!isEmpty($_POST['team'.$count])){
                $formOK = false;
                array_push($errorMessage, "<li>Played must be checked to enter scores");
            }
        }
        
        if (!validateDate($count)){
            $formOK = false;
            array_push($errorMessage,"<li>Please enter a valid date: date$count");
        }

        if (!validateClashes($count)){
            $formOK = false;
            array_push($errorMessage,"<li>Only one veune can be used during the day" );
        }
        
        $count++;
    }
}
array_push($errorMessage,"</ul>" );
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
/* If the form validates then update the xml files */
if ($formOK) {
    $tournament = simplexml_load_file('tournament.xml');
    $matches = $tournament->xpath('match');
    foreach ($matches as $item) {
       unset($item[0]);
    }
    
    $count = 0;
    
    /* Loops through all the matches by checking if there is a day$count submitted and adds them to the xml. */
    while ($_POST['day'.$count] != null) {
        
            $match = $tournament->addChild('match');
            $date = $match->addChild('date');

            $date->addChild('day', $_POST['day' . $count]);
            $date->addChild('month', $_POST['month' . $count]);
            $date->addChild('year', $_POST['year' . $count]);

            $match->addChild('venue', $_POST['venue' . $count]);
        
        /* If the game has been played then add the scores to the xml. */
        if (isset($_POST['played' . $count])) {
            for ($i = 1; $i < 3; $i++) {
                $team = $match->addChild('team', $_POST['team' . $i . 'name_' . $count]);
                $team->addAttribute('score', $_POST['team' . $i . '_' . $count]);
            }
        } else {
            /*If the game hasn't been played then only add the teams and not scores. */
            for ($i = 1; $i < 3; $i++) {
                $team = $match->addChild('team', $_POST['team' . $i . 'name_' . $count]);
            }
        }
        $count++;
    
    }
    
    $tournament->saveXML('tournament.xml');
    echo "<p>The following changes have been made</p>";
    $matchCount = 0;
    
    /*
     * Foreach to loop through the tournament xml and assign each element to a variable
     */
    foreach ($tournament->match as $item){
        $matchCount++;
        $day = $item->date->day;
        $month = $item->date->month;
        $year = $item->date->year;
        $venue = $item->venue;
        $team1 = $item->team[0];
        $team2 = $item->team[1];
        $score1 = $team1->attributes();
        $score2 = $team2->attributes();
        /* prints out each match */
        echo "<ul style='list-style-type: none;'>
                <li><b>match $matchCount</b></li>
                <li>$day $month $year</li>
                <li>$venue</li>
                <li>$team1 - $score1  $team2 - $score2</li>
                </ul>";
        
    }
    
}
    
?>
</div>
</body>
</html>