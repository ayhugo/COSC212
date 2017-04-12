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

<p>
    <a href="index.php">Fixtures</a> |
    <a href="standings.php">Standings</a> |
    <a href="admin.php"> admin</a> |
    New Tournament
</p>

<h1>Create Venues - Administration</h1>
<?php

foreach ($_POST['venue'] as $item)
{
    echo "<p>$item</p>";
}
?>
</body>
</html>
