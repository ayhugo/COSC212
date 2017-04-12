<?php
$conn = new mysqli('sapphire', 'hayre', 'timedout2', 'hayre_dev');
if ($conn->connect_errno) {
    echo "<p>Something went wrong connecting1</p>"; // Something went wrong connecting
}
?>