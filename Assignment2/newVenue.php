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
        <li><a href="admin.php">Admin</a>
        <li><a class="active">New Tournament</a>
    </ul>
</nav>



    <script type="text/javascript">
    var counter = 1;
    function addVenue() {
        var select = document.getElementById("venue");
        var option = document.createElement("option");
        var value = $("#newVenue").val();
        if (value != "") {
            option.text = value;
            option.value = value;
            select.add(option);
            counter++;
            select.size=counter;
        }
    }
    
    function removeVenue() {
        var select = document.getElementById("venue");
        select.remove(select.selectedIndex);
        counter--;
        select.size=counter;
    }

    function selectAll()
    {
        var selectBox = document.getElementById("venue");
    
        for (var i = 0; i < selectBox.options.length; i++)
        {
            selectBox.options[i].selected = true;
        }
    }
</script>
<div id="main">
    <p>Add the venues of the round robin and click <b>create venues</b> to continue. One venue has been provided.</p>
<form method="post" action="newTournament.php" novalidate>
    <fieldset><legend><h1>Create Venues - Administration</h1></legend>
    <select multiple class="select" id="venue" name="venue[]" size="1">
        <option VALUE="venue1">Venue1</option>
    </select><button type="button" onclick="removeVenue();">Remove selected venue</button>
    <br>
    <input type = "text" id="newVenue" />
    <button type="button" onclick="addVenue();">Add Venue</button>
    
    <br>
    </fieldset>
    <input type="submit" value="Create venues"  name = "submit" onclick="selectAll();"/>

</form>

</div>


</body>
