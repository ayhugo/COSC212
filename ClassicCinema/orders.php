<!DOCTYPE html>

<html lang="en">
<?php
$scriptList = array("js/jquery-3.1.0.min.js");
include("htaccess/header.php");
?>


<div id="main">
    <?php
    $orders = simplexml_load_file('htaccess/orders.xml');

    foreach ($orders->order as $order) {
        $name = $order->delivery->name;
        $email = $order->delivery->email;
        $address = $order->delivery->address;
        $city = $order->delivery->city;
        $postcode = $order->delivery->postcode;
        echo "<p>Name: $name</p>";
        echo "<p>Email: $email</p>";
        echo "<p>address: $address</p>";
        echo "<p>city: $city</p>";
        echo "<p>postcode: $postcode</p>";


        foreach ($order->items->item as $item){
            $title = $item->title;
            $price = $item->price;

            echo "<p>Order: $title $price</p>";
        }
        echo "<p>--------------------------</p>";
    }


    ?>

</div>

<?php include("htaccess/footer.php"); ?>

</body>
</html>