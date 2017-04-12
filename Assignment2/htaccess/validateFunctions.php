<?php
/*
 * Loads in the venues from the xml and checks the the submitted venue is in the xml
 */
function validateVenue($key){
    $venues = simplexml_load_file('venues.xml');
    foreach ($venues->venue as $venue) {
        if (strpos($venue, $_POST['venue' . $key]) !== false) {
            return true;
        }
    }
    return false;
}

/*
 * Checks the day is between 0-31, month 0-11 and year 2014-2021
 * Also checks all values are numbers.
 */
function validateDate($key){
    if ($_POST['day'.$key] < 0 || $_POST['day'.$key] > 31 || $_POST['month'.$key] < 0 || $_POST['month'.$key] > 11 || $_POST['year'.$key] < 2014 || $_POST['year'.$key] > 2021 
        || !is_numeric(($_POST['day' . $key])) || !is_numeric(($_POST['month' . $key])) || !is_numeric(($_POST['year' . $key]))){
        return false;
    } else {
        return true;
    }
}

/*
 * Checks if the scores are not negative and is they're numbers.
 */
function validateScore($key)
{
    if (($_POST['team1_' . $key]) < 0 || ($_POST['team2_' . $key]) < 0 || !is_numeric(($_POST['team1_' . $key])) || !is_numeric($_POST['team2_' . $key])){
        return false;
    } else {
        return true;
    }

    
}

/*
 * Nested for loop.
 * checks the date of each match against every other match. 
 * If they are equal then check if the venues or teams clash
 */
function validateClashes($key1){
    
    for ($key2 = $key1+1; $_POST['day'.$key2] != null; $key2++) {
        if ($_POST['day' . $key1] == $_POST['day' . $key2] && $_POST['month' . $key1] == $_POST['month' . $key2] && $_POST['year' . $key1] == $_POST['year' . $key2]) {
            if ($_POST['venue' . $key1] == $_POST['venue' . $key2] || $_POST['team1name_' . $key1] == $_POST['team2name_' . $key2]) {
                return false;
            } else {
                return true;
            }

        }
    }
    return true;
    
}

/* returns false if what ever being submitted is empty */
function isEmpty($key){
    if ($key == ""){
        return false;
    }
    return true;
}

/*
 * Nest for loop.
 * Checks each team against every other team.
 * Returns false is two teams are equal.
 */
function checkTeams($key1) {
    for ($key2 = $key1+1; $_POST['team'.$key2] != null; $key2++) {
        if ($_POST['team'.$key1] == $_POST['team'.$key2]){
            return false;
        }
    }
    return true;
}

?>