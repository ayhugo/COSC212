<!DOCTYPE html>

<html lang="en">
<?php
$scriptList = array("js/jquery-3.1.0.min.js");
include("htaccess/header.php");
include("htaccess/connect.php");
?>
<div id="main">
<?php
$formOk = false;
if (isset($_POST['submit'])) {
$formOk = true;
$username = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);
$confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);

if ($username === '') {
$formOk = false;
echo "<p>Please provide a username</p>";
}
if ($email === "") {
$formOk = false;
echo "<p>Please provide an email</p>";
}
if ($confirmPassword != $password) {
$formOk = false;
echo "<p>passwords do not match </p>";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$formOk = false;
echo "<p>Please enter a valid email</p>";
}
if ($password === "" || strlen($password) < 7 || preg_match('/+[a-z]+[0-9]+/', $password)){
$formOk = false;
echo "<p>Password must contain 8 charaters and at least a letter and a number </p>";
}
if ($formOk) {
$query = "SELECT * FROM Users WHERE username = '$username'";
$result = $conn->query($query);
if ($result->num_rows === 0) {
// OK, there is no user with that username
$query = "INSERT INTO Users (username, password, email) VALUES ('$username', SHA('$password'), '$email')";
$conn->query($query);
if ($conn->error) {
echo "<p>Something went wrong2</p>";// Something went wrong
}
} else {
echo "<p>username taken</p>";// Problem -- username is already taken
}
$result->free();
$conn->close();
}
}
if (!$formOk) {
echo '<form method="POST" action="register.php">
    <p>
        <label for="name">Name:</label>
        <input type ="text" name="name">
    </p>
    <p>
        <label for="email">Email:</label>
        <input type="text" name="email">
    </p>
    <p>
        <label for="password">Password:</label>
        <input type="password" name="password">
        </p>
        
    <p>
        <label for="confirmPassword">Comfirm Password:</label>
        <input type="password" name ="confirmPassword">
    </p>
    <p>
        password must be at least 8 characters long and contain one letter and number.
    </p>
    <input type="submit" name="submit">
</form>';

}
if ($formOk){
echo "<p>user registered</p>";
}
?>



</div>

<?php include("htaccess/footer.php"); ?>

</body>
</html>