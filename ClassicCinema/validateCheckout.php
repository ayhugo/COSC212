<?php
$_SESSION['deliveryName'] = $_POST['deliveryName'];
$_SESSION['deliveryEmail'] = $_POST['deliveryEmail'];
$_SESSION['deliveryAddress1'] = $_POST['deliveryAddress1'];
$_SESSION['deliveryCity'] = $_POST['deliveryCity'];
$_SESSION['deliveryPostcode'] = $_POST['deliveryPostcode'];
$_SESSION['cardType'] = $_POST['cardType'];

$scriptList = array("js/jquery-3.1.0.min.js", "js/cookies.js");
include("htaccess/header.php");
include("htaccess/validateFunctions.php");

?>
<div id="main">
    <?php
    
    $formOk = false;
    if (isset($_POST['submit'])) {
        $formOk = true;
        if (isEmpty($_POST['deliveryName'])) {
            $formOk = false;
            echo "<p>Please enter a name</p>";

        }
        if (!isEmail($_POST['deliveryEmail']) || isEmpty($_POST['deliveryEmail'])) {
            $formOk = false;
            echo "<p>Please enter a valid email</p>";
        }

        if (isEmpty($_POST['deliveryAddress1'])) {
            $formOk = false;
            echo "<p>Please enter a delivery address</p>";
        }

        if (isEmpty($_POST['deliveryCity'])) {
            $formOk = false;
            echo "<p>Please enter a city</p>";
        }

        if (!checkLength($_POST['deliveryPostcode'], 4) || !isDigits($_POST['deliveryPostcode'])) {
            $formOk = false;
            echo "<p>Please enter a valid post code</p>";
        }

        if (!checkCardVerification($_POST['cardType'], $_POST['cardValidation'])) {
            $formOk = false;
            echo "<p>Please enter a vaild CVC</p>";
        }
        if (!checkCardNumber($_POST['cardType'], $_POST['cardNumber'])) {
            $formOk = false;
            echo "<p>Please enter a valid card number</p>";
        }
        if (!checkCardDate($_POST['cardMonth'], $_POST['cardYear'])) {
            $formOk = false;
            echo "<p>Please enter a valid credit card date</p>";
        }

        if ($formOk) {
            $cart = json_decode($_COOKIE['cart']);
            echo "<style>table, th, td {border: 1px solid black;border-collapse: collapse;}</style>";
            echo "<table><tr><th>Price</th><th>Title</th></tr>";
            foreach ($cart as $i) {
                echo "<tr><td>$i->price</td> <td>$i->title</td></tr>";
            }
            echo "</table>";
            $orders = simplexml_load_file('htaccess/orders.xml');
            $newOrder = $orders->addChild('order');
            $delivery = $newOrder->addChild('delivery');
            $items = $newOrder->addChild('items');
            foreach ($cart as $i) {
                $item = $items->addChild('item');
                $item->addChild('title', $i->title);
                $item->addChild('price', $i->price);
            }

            $delivery->addChild('name', $_POST['deliveryName']);
            $delivery->addChild('email', $_POST['deliveryEmail']);
            $delivery->addChild('address', $_POST['deliveryAddress1']);
            $delivery->addChild('city', $_POST['deliveryCity']);
            $delivery->addChild('postcode', $_POST['deliveryPostcode']);

            $orders->saveXML('htaccess/orders.xml');

            setcookie('cart', '', time() - 3600, '/');
            unset($_COOKIE['cart']);

            $_SESSION = array();
            session_destroy();
        }
    }

    ?>
</div>
<?php include("htaccess/footer.php"); ?>
</body>
</html>