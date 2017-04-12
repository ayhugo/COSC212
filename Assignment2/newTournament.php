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
    <script type="text/javascript">
        function buttonClick() {
            document.getElementById('moreFields').onclick = moreFields;
            moreFields();
            document.getElementById('venueButton').onclick = setVenue;
            setVenue();
        }
        var count = 0;

        /*
         * Duplicates all form input fields
         */
        function moreFields() {
            var newFields = document.getElementById('readroot').cloneNode(true);
            newFields.id = '';
            newFields.style.display = 'block';
            var newField = newFields.childNodes;
            /* each field in the form gets duplicated
             * names of each field become name+count
             */
            for (var i=0;i<newField.length;i++) {
                var newName = newField[i].name;
                if (newName) {
                    newField[i].name = newName + count;
                }
            }
            var insertField = document.getElementById('writeroot');
            insertField.parentNode.insertBefore(newFields,insertField);
            /* Sets the hidden teamCount field to count */
            document.getElementById("teamCount").value = count;
            count++;
        }

        window.onload = moreFields;
        window.onload = buttonClick;
    </script>
    <?php
    include('htaccess/validateFunctions.php');
    ?>
</head>
<body>
<nav>
    <ul>
        <li><a href="index.php">Fixtures</a>
        <li><a href="standings.php">Standings</a>
        <li><a href="admin.php">Admin</a>
        <li><a class="active">New Tournament</a>
    </ul>
</nav>

<?php
/* bool formOk is initialised and set to false */
$formOK = false;

/* array errorMessage is initialised */
$errorMessage = array("<p>There were some errors when processing your form:</p><ul>");

/* checks that the form has been submitted */ 
if (isset($_POST['submit'])) {
    $formOK = true;
    
}
array_push($errorMessage,"</ul>" );
if (!$formOK){
    $arrlength = count($errorMessage);

    for($x = 0; $x < $arrlength; $x++) {
        echo $errorMessage[$x];
    }
}
if ($formOK) {
    $venues = simplexml_load_file('venues.xml');
    $venue = $venues->xpath('venue');
    foreach ($venue as $item){
        unset($item[0]);
    }
    foreach ($_POST['venue'] as $item)
    {
        $venues->addChild('venue', $item);
    }
    
    $venues->saveXML('venues.xml');
    echo "<div id='main'><div id='readroot' style='display: none'>

    

New team name: <input type='text' name='team'>
<input type='button' value='Remove Team' onclick='this.parentNode.parentNode.removeChild(this.parentNode);'> 
    

    

</div>

<form method='post' action='admin.php'>
    <fieldset><legend><h1>Create A New Tournament - Administration</h1></legend>
    <span id='writeroot'></span>
    <input type='hidden'  id='teamCount' name='teamCount' value='0'/><input type='button' id='moreFields' value='Add a New Team' />
    <br><br>
    </fieldset>
    <input type='submit' value='Create Tournament' name='submit'/>

</form></div>";
}

?>





</body>
