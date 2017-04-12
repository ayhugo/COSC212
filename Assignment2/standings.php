<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assignment 1 - Sample Solution</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="js/XMLReader.js"></script>
    <script src="js/standings.js"></script>
</head>
<body>

<header>Round Robin Tournament</header>
<nav>
    <ul>
        <li><a href="index.php">Fixtures</a>
        <li><a class="active">Standings</a>
        <li><a href="admin.php">Admin</a>
        <li><a href="newVenue.php">New Tournament</a>
    </ul>
</nav>

<div id="main">
    <h1>Round Robin Standings</h1>
<table id="standings">
    <tr>
        <th>Rank</th>
        <th>Team</th>
        <th>Played</th>
        <th>Won</th>
        <th>Drawn</th>
        <th>Lost</th>
        <th>Points</th>
        <th>For</th>
        <th>Against</th>
        <th>Diff</th>
    </tr>
</table>
</div>

</body>
</html>