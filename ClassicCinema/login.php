<?php
include("htaccess/header.php");
include ("htaccess/validateFunctions.php");
$scriptList = array("js/jquery-3.1.0.min.js");

include("htaccess/connect.php");
?>
<div
<!DOCTYPE html>

<html lang="en">
<div id ="main">
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formOk = true;
        $username = $conn->real_escape_string($_POST['loginUser']);
        $password = $conn->real_escape_string($_POST['loginPassword']);

        if (isEmpty($_POST['loginUser']) || isEmpty($_POST['loginPassword'])) {
            $formOk = false;
        }
//    if (isset($_POST['submit'])) {
//        $formOk = true;
//       ;
//    }

        if ($formOk) {
            $query = "SELECT * FROM Users WHERE username = '$username' AND password = SHA('$password')";
            $result = $conn->query($query);
            if ($result->num_rows === 0) {
                // OK, there is no user with that username
                echo "<p>username or password incorrect </p>";
            } else {
                $conn->query($query);
                if ($conn->error) {
                    echo "<p>Something went wrong</p>";
                } else {

                    $_SESSION['username'] = $_POST['loginUser'];
                    $_SESSION['password'] = $_POST['loginPassword'];
                    echo "<p>Welcome ".$_SESSION['username']."</p>";
                }
            }
            $conn->query($query);
            if ($conn->error) {
                echo "<p>Something went wrong</p>";
            }

            $result->free();
            $conn->close();
        } else {
            header("location:" . $_SESSION['lastPage']);
        }
//    if (isset($_POST['loginPassword']) || isset($_POST['loginUser'])) {
//
//        $lastPage = $_SESSION['lastPage'];
//        header('Location:' . $lastPage);
//        exit;
//    } else {
//        header('Location: index.php');
//        exit;
//
//    }
    }
    ?>
</div>

<?php include("htaccess/footer.php"); ?>

</body>
</html>
